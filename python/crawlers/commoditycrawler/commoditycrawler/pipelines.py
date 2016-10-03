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

sys.path.append("/home/jongeun/crawlers/commoditycrawler/commoditycrawler")
from dbcontroller import DBcontroller
import time
from datetime import date
from datetime import datetime
from dateutil import tz


class CommoditycrawlerPipeline(object):
    def __init__(self):
        self.tablename = "commoditydata"
        self.controller = DBcontroller(self.tablename,"commoditydb")


    def process_item(self, item, spider):
        print type(item['date'])
        datestring = " ".join(item['date'])
        print type(datestring)
        print(datestring)
        if datestring != "":
            print "date!!"
            print item['date']
            print item['price']
            liststring = self.editdatedata(item['date'])
            print "liststring[0]"
            print liststring[0]
            print "liststring[1]"
            print liststring[1]
            month  = self.stringMonthToNumstring(liststring[0])
            datestring = self.createDateString(liststring[1],month)
            #timestamp = self.getUTCtimefromdatetime(datestring)
            ratechagelist = " ".join(item['ratechange']).split(" ")
            if self.isNumber(ratechagelist[0]) == True:
                item['ratechange'] = ratechagelist[0]
            else:
                item['ratechange'] = "0"

            price = self.replaceString(" ".join(item['price']))
            if self.isNumber(price) == True:
                item['price'] = price
            else:
                item['price'] = "0"
            item['time'] = datestring
            self.controller.excutesql(self.getInsert_stmt(),self.getData_stmt(item))
        return item

    def isNumber(self,s):
      try:
        float(s)
        return True
      except ValueError:
        return False

    def replaceString(self, str):
        return str.replace(",", "")

    def stringMonthToNumstring(self, monthstring):
        resultMonthMap = {
          "Jan":"01",
          "Feb":"02",
          "Mar":"03",
          "Apr":"04",
          "May":"05",
          "Jun":"06",
          "Jul":"07",
          "Aug":"08",
          "Sep":"09",
          "Oct":"10",
          "Nov":"11",
          "Dec":"12"
        }
        result = resultMonthMap.get(monthstring)
        return result

    def createDateString(self,year,month):
        datestring = year + "-" +month + "-" + "01 00:00:01"
        return datestring

    def getUTCtimefromdatetime(self, datestring):
        print(datestring)
        convertstring = datestring
        #현재 시간은 한국 시간으로 표시 되므로 9시간 차이나게 다시 utc로 돌린다
        utc = datetime.strptime(convertstring, '%Y-%m-%d %H:%M:%S')
        converttime = time.mktime(utc.timetuple())
        convertsecondtime = int(converttime)
        print(int(convertsecondtime))
        return convertsecondtime


    def editdatedata(self,datedata):
        #datedata.split(' ');
        convertlistobjecttoString = " ".join(datedata)
        liststring = convertlistobjecttoString.split(' ')
        return liststring

    def getInsert_stmt(self):
        insert_stmt = (
        "INSERT INTO " + self.tablename + " (commodity,time,ratechange,price)"
        "values(%s,%s,%s,%s)"
        )
        return insert_stmt

    def getData_stmt(self,item):
        data_stmt = (item['commodity'],item['time'],float(item['ratechange']),float(item['price'])
        )
        return data_stmt
