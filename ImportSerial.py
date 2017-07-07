import serial
import datetime as dt

arduino = serial.Serial('/dev/ttyACMO')
arduino.baudrate = 9600

while True:
    data = arduino.readline()
    pieces = data.split('\t')
    print pieces
