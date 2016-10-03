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


class CommoditylistcrawlerPipeline(object):
    def __init__(self):
        self.tablename = "commoditylist"
        self.fileName = "commoditylist" + ".json"
        self.string_db_name = "commoditydb"
        self.file = codecs.open(self.fileName, 'wb', encoding='utf-8')
        try:
          self.conn = MySQLdb.connect(user='hive', passwd='740412', db=self.string_db_name, host='localhost', charset="utf8", use_unicode=True)
          self.cursor = self.conn.cursor()
          isExist = self.is_table(self.tablename)
          if isExist == True:
              isExist = self.drop_table(self.tablename)
          else:
              isExist = self.create_table(self.tablename)

          if isExist == True:
              isExist = self.create_table(self.tablename)

        except MySQLdb.Error, e:
          print "Error %d: %s" % (e.args[0], e.args[1])

    def process_item(self, item, spider):
        line = json.dumps(dict(item)) + "\n"
        self.file.write(line)
        self.insert_data(item)
        return item

    def create_table(self, table):
        try:
            self.sqlstring = "CREATE TABLE IF NOT EXISTS " + table + "( country value varchar(20) NOT NULL, PRIMARY KEY (value)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return exist

    def is_table(self, table):
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

    def drop_table(self, table):
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
        string2 = " (value) values(%s)"
        self.sqlstring = string1 + string2
        print "sql!!"
        print self.sqlstring
        string3 = self.sqlstring % (item['value'])
        print "string3!!"
        print string3
        self.cursor.execute(self.sqlstring,(item['value']))
        self.conn.commit()
