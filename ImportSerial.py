import serial
import datetime as dt
from sqlalchemy import create_engine

arduino = serial.Serial('/dev/ttyACM0')
arduino.baudrate = 9600

while True:
    data = arduino.readline()
    pieces = data.split(',')
    for i in range(len(pieces)):
        print '{0}: {1}'.format(pieces[i],dt.datetime.now())
