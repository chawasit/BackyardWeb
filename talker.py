import json
from Queue import Queue
from threading import Thread, Event
import requests

import paho.mqtt.client as MQTTCLient
import time

from math import log

from zigbee.receiver import Receiver
from utils import create_serial

config = {}

try:
    config_file = open('config.json', 'r')
    config = json.loads(config_file.read())
except:
    print "Invalid Config"
    exit(1)

temperature = 0
humidity = 0
pump = False
auto_pump = True

temperature_time = time.time()
humidity_time = time.time()
pump_time = time.time()

status = Event()

weight = 3.0


def process_data(queue):
    global temperature, humidity, temperature_time, humidity_time, pump_time, weight
    while True:
        if not queue.empty():
            data = queue.get()
            command_code = data['CMD']
            if command_code == 9:
                short_address = str(data['SHORT_ADDR'])
                sensor1 = data['SENSOR1']
                sensor2 = data['SENSOR2']
                try:
                    node_name = config['nodes'][short_address]
                    if node_name == 'temperature':
                        temperature_time = time.time()
                        if sensor1 == 0:
                            continue
                        r = 10.0e2 / (1023.0 / float(sensor1) - 1)
                        b = 4.0e3
                        r0 = 10.0e3
                        t0 = 298.0
                        temperature = (1.0 / ((1.0 / t0) + ((1.0 / b) * log(r / r0)))) - 273
                    elif node_name == 'humidity':
                        new_humidity = int(sensor1)
                        humidity = int(((weight - 1) * humidity + new_humidity) / weight)
                        humidity_time = time.time()
                    elif node_name == 'pump':
                        pump_time = time.time()

                except Exception as e:
                    print "Error ({})".format(str(e))
            elif command_code == 8:
                status.set()


def logger(mqtt_client):
    time.sleep(5)
    global temperature, humidity, pump, auto_pump
    temperature_online = True
    humidity_online = True
    pump_online = True
    while True:
        try:
            r = requests.post(config['log']['value'], data={
                'temperature': temperature,
                'humidity': humidity,
                'pump': 1 if pump else 0
            })
            print "Logger sent {}".format(r.status_code)
            mqtt_client.publish(config['mqtt']['root_topic'] + "/value", json.dumps({
                'temperature': temperature,
                'humidity': humidity,
                'pump': pump,
            }))
            print "temp={}, humidity={}, pump={}, auto={}".format(temperature, humidity, pump, auto_pump)

            if time.time() - temperature_time > 30:
                if temperature_online:
                    send_notification("Temperature Node is offline")
                    temperature_online = False
            elif not temperature_online:
                send_notification("Temperature Node is online")
                temperature_online = True

            if time.time() - humidity_time > 30:
                if humidity_online:
                    send_notification("Humidity Node is offline")
                    humidity_online = False
            elif not humidity_online:
                send_notification("Humidity Node is online")
                humidity_online = True

            if time.time() - pump_time > 30:
                if pump_online:
                    send_notification("Pump Node is offline")
                    pump_online = False
            elif not pump_online:
                send_notification("Pump Node is online")
                pump_online = True

        except Exception as e:
            print "Logger Error " + str(e)
        time.sleep(5)


def send_notification(message):
    try:
        webhook = config["webhook"]
        print webhook, message
        res = requests.post(webhook, data={'value1': message})
        print "Send webhook {}".format(res.status_code)
        res = requests.post(config['log']['notification'], data={'message': message})
        print "Send noti logger {}".format(res.status_code)
        mqtt_client.publish(config['mqtt']['root_topic'] + "/notification", json.dumps({
            'message': message,
        }))
    except Exception as e:
            print "Logger Error " + e.value


def auto_pump_condition():
    global temperature, humidity, pump
    time.sleep(3)
    prev_pump = pump
    sent = False
    while True:
        pump_is_responding = True
        should_turn_on_pump = (humidity < config['pump']['lower'])
        should_turn_off_pump = (humidity > config['pump']['upper'])

        if auto_pump:
            if should_turn_on_pump and not pump:
                pump = True
                # pump_is_responding = on_pump(serial_connection)
                send_notification("Humidity is low. Turn on pump!")
            if should_turn_off_pump and pump:
                pump = False
                # pump_is_responding = off_pump(serial_connection)
                send_notification("Humidity is Good Now. Turn off pump")
        # else:
        #     if pump is not prev_pump:
        #         if pump:
        #             pump_is_responding = on_pump(serial_connection)
        #         else:
        #             pump_is_responding = off_pump(serial_connection)

        pump_is_responding = on_pump(serial_connection) if pump else off_pump(serial_connection)

        if not pump_is_responding and not sent:
            sent = True
            send_notification("Pump is not responding. Pls check!")
        elif pump_is_responding:
            sent = False
        prev_pump = pump

        time.sleep(3)


def send_command(serial, command):
    status.clear()
    command = command + '\n'
    command = command.encode('ascii')
    serial.write(command)
    status.wait(3)

    return status.is_set()


def on_pump(serial):
    for address, name in config['nodes'].iteritems():
        if name == "pump":
            return send_command(serial, "ONOFFPORT 255 0 {} 01".format(address))
    return False


def off_pump(serial):
    for address, name in config['nodes'].iteritems():
        if name == "pump":
            return send_command(serial, "ONOFFPORT 255 0 {} 00".format(address))
    return False


def on_connect(client, userdata, flags, rc):
    print("Connected with result code " + str(rc))
    client.subscribe(config['mqtt']['root_topic'] + "/#")


def on_message(client, userdata, msg):
    global pump, auto_pump
    topic = msg.topic[len(config['mqtt']['root_topic']) + 1:]
    payload = msg.payload

    if topic == 'pump':
        if payload == "1":
            pump = True
            auto_pump = False
        elif payload == "0":
            pump = False
            auto_pump = False
        else:
            pump = False
            auto_pump = True
        print "Set pump to pump{} auto{}".format(pump, auto_pump)


mqtt_client = MQTTCLient.Client(transport=config['mqtt']['transport'])
mqtt_client.on_connect = on_connect
mqtt_client.on_message = on_message
mqtt_client.connect(config['mqtt']['host'], config['mqtt']['port'], config['mqtt']['keep_alive'])
mqtt_client.loop_start()

serial_connection = create_serial(config['serial'])

data_queue = Queue()

receiver_thread = Receiver(serial_connection, data_queue)
receiver_thread.daemon = True
receiver_thread.start()

processor_thread = Thread(target=process_data, args=(data_queue,))
processor_thread.daemon = True
processor_thread.start()

logger_thread = Thread(target=logger, args=(mqtt_client,))
logger_thread.daemon = True
logger_thread.start()


auto_pump_condition_thread = Thread(target=auto_pump_condition)
auto_pump_condition_thread.daemon = True
auto_pump_condition_thread.start()

print "Hi Starting.."
try:
    while True:
        pass
except KeyboardInterrupt:
    print "Good Bye"
