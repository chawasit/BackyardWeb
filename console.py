import cmd
import sys
import time
from Queue import Queue
from threading import Thread, Event

from utils import serial_ports, create_serial
from zigbee.receiver import Receiver


class ZigBeeNode:
    def __init__(self, short_address, sensor1, sensor2):
        self.short_address = short_address
        self.sensor1 = sensor1
        self.sensor2 = sensor2
        self.time = time.ctime()

    def __str__(self):
        return "Zigbee(short_address={}, sensor1={}, sensor2={},\n" \
               "       updated_at={})".format(self.short_address
                                       , self.sensor1
                                       , self.sensor2
                                       , self.time)


class ZigbeeConsole(cmd.Cmd):
    def __init__(self, serial):
        cmd.Cmd.__init__(self)
        self.prompt = 'ZigBee > '
        self.serial = serial

        self.data_queue = Queue()

        self.zigbee_receiver = Receiver(serial, self.data_queue)
        self.zigbee_receiver.start()

        self.parser = Thread(target=self.__data_parser)
        self.parser.daemon = True
        self.parser.start()

        self.response_event = Event()
        self.nodes = {}

    def __data_parser(self):
        while True:
            if not self.data_queue.empty():
                data = self.data_queue.get()
                command_code = data['CMD']
                if command_code == 9:
                    short_address = data['SHORT_ADDR']
                    sensor1 = data['SENSOR1']
                    sensor2 = data['SENSOR2']
                    self.nodes[short_address] = ZigBeeNode(short_address, sensor1, sensor2)
                elif command_code == 8:
                    self.response_event.set()
                elif command_code == 1:
                    short_address = data['SHORT_ADDR']
                    print "New node joined, ", short_address

    def __send_command(self, zigbee_command):
        self.response_event = Event()
        zigbee_command = zigbee_command + '\n'
        zigbee_command = zigbee_command.encode('ascii')
        self.serial.write(zigbee_command)
        self.response_event.wait(1)
        return self.response_event.is_set()

    def __know_short_address(self, text):
        if not text > 0:
            return [str(node.short_address) for node in self.nodes.itervalues()]
        else:
            return [str(node.short_address) for node in self.nodes.itervalues() \
                    if str(node.short_address).startswith(text)]

    def do_permitjoin(self, value):
        """permitjoin [seconds]
                wait for new node to join for [seconds] seconds"""
        is_ok = self.__send_command("PERMITJOIN {}".format(value))
        if not is_ok:
            print "Coordinator not response"

    def do_ping(self, short_address):
        """ping [short address]
                send identify command (node will beep)"""
        is_ok = self.__send_command("IDENTIFY 255 0 {} 1".format(short_address))
        if is_ok:
            print "Node {} is online".format(short_address)
        else:
            print "Node {} is offline".format(short_address)

    def do_on1(self, short_address):
        """on1 [short address]
                 set output1 of node [short address] to high"""
        is_ok = self.__send_command("ONOFFPORT 255 0 {} 11 01".format(short_address))
        if not is_ok:
            print "Node {} not response".format(short_address)

    def do_on2(self, short_address):
        """on2 [short address]
                 set output2 of node [short address] to high"""
        is_ok = self.__send_command("ONOFFPORT 255 0 {} 11 11".format(short_address))
        if not is_ok:
            print "Node {} not response".format(short_address)

    def do_off1(self, short_address):
        """off1 [short address]
                 set output2 of node [short address] to low"""
        is_ok = self.__send_command("ONOFFPORT 255 0 {} 11 00".format(short_address))
        if not is_ok:
            print "Node {} not response".format(short_address)

    def do_off2(self, short_address):
        """off2 [short address]
                 set output2 of node [short address] to low"""
        is_ok = self.__send_command("ONOFFPORT 255 0 {} 11 10".format(short_address))
        if not is_ok:
            print "Node {} not response".format(short_address)

    def do_sensor(self, short_address):
        """sensor [short address]
                show node sensor value"""
        try:
            print self.nodes[int(short_address)]
        except:
            print "Not found"

    def do_list(self, _):
        """list
                show all known nodes"""
        print "== update every 5 seconds =="
        for node in self.nodes.itervalues():
            print node

    def do_exit(self, _):
        return True

    def complete_sensor(self, text, *_):
        return self.__know_short_address(text)

    def complete_ping(self, text, *_):
        return self.__know_short_address(text)

    def complete_on1(self, text, *_):
        return self.__know_short_address(text)

    def complete_on2(self, text, *_):
        return self.__know_short_address(text)

    def complete_off1(self, text, *_):
        return self.__know_short_address(text)

    def complete_off2(self, text, *_):
        return self.__know_short_address(text)


if __name__ == '__main__':
    try:
        if len(sys.argv) < 2:
            print "Searching for serial ports..."
            serials = serial_ports()
            for index in range(len(serials)):
                print "[{}] {}".format(index, serials[index])

            select = raw_input("Enter serial port no.")
            serial_connection = create_serial(serials[int(select)])
        else:
            serial_connection = create_serial(sys.argv[1])

        zigbee = ZigbeeConsole(serial_connection)
        zigbee.cmdloop()
    except KeyboardInterrupt:
        pass
