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

class GdpgrowratecrawlerPipeline(object):
    def __init__(self):
        self.tablename = year + "GDP"
        try:
          self.conn = MySQLdb.connect(user='hive', passwd='740412', db='imf_database', host='localhost', charset="utf8", use_unicode=True)
          self.cursor = self.conn.cursor()
        except MySQLdb.Error, e:
          print "Error %d: %s" % (e.args[0], e.args[1])


    def process_item(self, item, spider):
        #line = json.dumps(dict(item)) + "\n"
        self.update_data(item)
        return item

    def update_data(self,item):
        #update ABCDE set column1='xyz' where no='3'
        string1 = "update " + self.tablename
        string2 = " set growrate=%s where country=%s"
        self.sqlstring = string1 + string2
        string3 = self.sqlstring % (item['growrate'],item['country'])
        self.cursor.execute(self.sqlstring,(item['growrate'],item['country']))
        self.conn.commit()
