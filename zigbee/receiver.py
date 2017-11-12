import logging
from threading import Thread

from packet_parser import Parser


#  Copyright (c) 2015 Supakorn Yukonthong
class Receiver(Thread):
    def __init__(self, serial, data_queue):
        super(Receiver, self).__init__()
        self.daemon = True
        self.serial = serial
        self.data_queue = data_queue
        self.byte_code_parser = Parser(log_level=logging.INFO)

    def run(self):
        packet_buffer = []
        payload_length = 0
        previous_byte = ''
        packets = []
        while True:
            if payload_length > 0:
                incoming_byte = self.serial.read(1)
                packet_buffer.append(incoming_byte)
                payload_length = payload_length - 1
                if payload_length <= 0:
                    packets.append(packet_buffer)
                    packet_buffer = []
                    # reset header
                    previous_byte = ''
            elif previous_byte == chr(0x54) and payload_length <= 0:
                incoming_byte = self.serial.read(1)
                if incoming_byte == chr(0xfe):
                    command_byte_high = self.serial.read(1)
                    command_byte_low = self.serial.read(1)
                    payload_length_byte = self.serial.read(1)

                    packet_buffer.append(chr(0x54))
                    packet_buffer.append(chr(0xfe))
                    packet_buffer.append(command_byte_high)
                    packet_buffer.append(command_byte_low)
                    packet_buffer.append(payload_length_byte)

                    payload_length = ord(payload_length_byte)
                else:
                    previous_byte = incoming_byte
            else:
                incoming_byte = self.serial.read(1)
                previous_byte = incoming_byte

            for packet in packets:
                data = self.byte_code_parser.parse(packet)
                self.data_queue.put(data)

            packets = []