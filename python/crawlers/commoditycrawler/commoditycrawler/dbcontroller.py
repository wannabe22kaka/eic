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


class DBcontroller(object):
    def __init__(self,tablename,dbname):
        self.tablename = tablename
        self.string_db_name = dbname
        print(self.tablename)
        print(self.string_db_name)
        try:
          self.conn = MySQLdb.connect(user='hive', passwd='740412', db=self.string_db_name, host='localhost', charset="utf8", use_unicode=True)
          self.cursor = self.conn.cursor()
          isExist = self.is_table("SELECT COUNT(*) FROM ")
          valuestring = " (country value varchar(20) NOT NULL, PRIMARY KEY (value)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
        except MySQLdb.Error, e:
          print "Error %d: %s" % (e.args[0], e.args[1])

    def create_table(self,valuestring):
        sqlvaluestring = valuestring
        try:
            self.sqlstring = "CREATE TABLE IF NOT EXISTS " + self.tablename + sqlvaluestring
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return exist

    def is_table(self,selectsql):
        try:
            self.sqlstring = selectsql + self.tablename;
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return exist

    def select_table(self,selectsql):
        try:
            #self.sqlstring = "SELECT value FROM " + self.tablename;
            self.sqlstring = selectsql + self.tablename;
            print(self.sqlstring)
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchall()
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return result


    def drop_table(self):
        try:
            self.sqlstring = "DROP TABLE FROM "+ self.tablename;
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return exist

    def excutesql(self,sqlstring,data):
        self.cursor.execute(sqlstring,data)
        self.conn.commit()
