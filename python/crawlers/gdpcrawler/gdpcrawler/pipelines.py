# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html
import json
import codecs
import sys

import logging
import MySQLdb.cursors
from pprint import pprint
from guppy import hpy

def edit_day(day):
    length = len(day)
    return day[5:length]

year = edit_day(sys.argv[3])

class GdpcrawlerPipeline(object):
    def __init__(self):
        self.tablename = year + "GDP"
        self.fileName = year + "gdp.json"
        self.file = codecs.open(self.fileName, 'wb', encoding='utf-8')
        try:
          self.conn = MySQLdb.connect(user='hive', passwd='740412', db='imf_database', host='localhost', charset="utf8", use_unicode=True)
          self.cursor = self.conn.cursor()
          self.insert_YearList(year,"GDPList");
          isExist = self.is_gdp_table(self.tablename)
          if isExist == True:
              isExist = self.drop_gdp_table(self.tablename)
          else:
              isExist = self.create_gdp_table(self.tablename)

          if isExist == True:
              isExist = self.create_gdp_table(self.tablename)

        except MySQLdb.Error, e:
          print "Error %d: %s" % (e.args[0], e.args[1])



    def process_item(self, item, spider):
        line = json.dumps(dict(item)) + "\n"
        self.file.write(line)
        self.insert_data(item)
        return item

    def insert_YearList(self, year,tablename):
        try:
            self.sqlstring = "INSERT INTO " + tablename + "(year) VALUES (\'" + year + "\')";
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

    def create_gdp_table(self, table):
        try:
            self.sqlstring = "CREATE TABLE IF NOT EXISTS " + table + "( `country`  varchar(30) NOT NULL , `currency`  varchar(15) NOT NULL ,`currencyamount`  int(10) NOT NULL,`growrate` float(7,4) NULL,`currencyunit` varchar(15) NOT NULL , PRIMARY KEY (`country`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return exist


    def is_gdp_table(self, table):
        try:
            self.sqlstring = "SELECT COUNT(*) FROM " + table;
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return exist

    def drop_gdp_table(self, table):
        try:
            self.sqlstring = "DROP TABLE FROM "+ table;
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return exist

    def insert_data(self, item):
        string1 = "INSERT INTO " + self.tablename
        string2 = " (country, currency,currencyamount,growrate, currencyunit) values(%s, %s, %s, %s, %s)"
        self.sqlstring = string1 + string2
        string3 = self.sqlstring % (item['country'], item['currency'],item['currencyamount'],item['growrate'],item['currencyunit'])
        self.cursor.execute(self.sqlstring,(item['country'], item['currency'],item['currencyamount'],item['growrate'],item['currencyunit']))
        self.conn.commit()
