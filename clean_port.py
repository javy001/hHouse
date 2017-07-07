import pandas as pd 
from sqlalchemy import create_engine
import datetime as dt

con = create_engine('mysql+pymysql://javierq:abcd1234@ec2-54-68-139-60.us-west-2.compute.amazonaws.com')

sql = "select * from read_port.temp"

df = pd.read_sql(sql,con)

def get_value(x):
    list1 = x.split(':')
    if len(list1)>1:
        try:
            return float(list1[1])
        except ValueError:
            return None
    else:
        return None
        
def get_metric(x):
    list1 = x.split(':')
    if len(list1)>1:
        return list1[0]
    else:
        return None

df['value'] = df.temp.apply(get_value)
df['metric'] = df.temp.apply(get_metric)
df['date_key'] = df.date_key.apply(lambda x:  dt.datetime(x.year,x.month,x.day,x.hour,0,0))


df = df.groupby(['date_key','metric']).mean()
df['date_key'] = df.index.get_level_values(0)
df['metric'] = df.index.get_level_values(1)
df.to_sql(name='clean_temp',con=con,index=False,schema='read_port',if_exists='append')

sql = "delete from read_port.temp"
c = con.connect()
c.execute(sql)