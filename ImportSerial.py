import serial
import datetime as dt
from sqlalchemy import create_engine

con = create_engine('mysql+pymysql://javierq:abcd1234@ec2-54-68-139-60.us-west-2.compute.amazonaws.com')

arduino = serial.Serial('/dev/ttyACM0')
arduino.baudrate = 9600

i = 0
while i<21:
    data = arduino.readline()
    pieces = data.split(',')
    print '{0}: {1}'.format(pieces,dt.datetime.now())
    if len(pieces) == 2:
        sql = "insert into read_port.temp2 (temp,date_key) values('{0},{1}','{2}')".format(pieces[0],pieces[1],dt.datetime.now())
        con.connect().execute(sql)
        i += 1
