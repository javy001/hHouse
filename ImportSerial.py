import serial
import datetime as dt

arduino = serial.Serial('/dev/ttyACM0')
arduino.baudrate = 9600

while True:
    data = arduino.readline()
    pieces = data.split('\t')
    for i in range(len(pieces)):
        print pieces[i]
