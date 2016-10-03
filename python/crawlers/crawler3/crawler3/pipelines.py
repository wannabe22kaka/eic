# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html
import json
import codecs
from scrapy.xlib.pydispatch import dispatcher
from scrapy import signals
import time
import sys

from datetime import date
from datetime import datetime
from dateutil import tz

from reverseJsondata import reversedata

def edit_day(day):
    length = len(day)
    return day[4:length]


todaystr = edit_day(sys.argv[3])
class Crawler3Pipeline(object):
    def __init__(self):
        dispatcher.connect(self.spider_closed, signals.spider_closed)
        self.pagecount = 1
        self.globalIndex = 0
        #20160815 self.todaystr = date.today().strftime("%Y%m%d")
        self.todaystr = todaystr
        self.reversedata = reversedata(self.todaystr)

    def getUTCtimefromdatetime(self, datestring):
        convertstring = datestring  + ":00"
        #현재 시간은 한국 시간으로 표시 되므로 9시간 차이나게 다시 utc로 돌린다
        utc = datetime.strptime(convertstring, '%Y-%m-%d %H:%M:%S')
        converttime = time.mktime(utc.timetuple())
        convertsecondtime = int(converttime)
        print(int(convertsecondtime))
        return convertsecondtime


    def process_item(self, item, spider):
        self.refine_crawldata(item)
        return item

    def spider_closed(self, spider):
        self.close_jsonstring()

    def close_jsonstring(self):
        self.reversedata.createReverseJsonData()

    def refine_titles(self, titlelist):
        list = []
        for data in titlelist:
            print(str(len(data)))
            if len(data) > 12:
                list.append(data)


        return list

    def refine_links(self, titlelist):
        list = []
        beforestring = ""
        for data in titlelist:
            if beforestring != data:
                list.append(data)
                beforestring = data

        return list

    def refine_crawldata(self,item):
        title = self.refine_titles(item['title'])
        item['title'] = title
        link = self.refine_links(item['link'])
        writing = item['writing']
        date = item['uploadtime']


        totalcount = len(date)
        print("datecount:" + str(totalcount))
        count = 0

        endcount = totalcount - 1

        for titleitem in title:
            if count < totalcount:
                data  = {}
                print(count)
                data['title'] = title[count]
                data['link'] = link[count]
                data['writing'] = writing[count]
                data['uploadtime'] = self.getUTCtimefromdatetime(date[count])
                count = count +1
                self.globalIndex = self.globalIndex + 1
                self.reversedata.stackData(data,self.globalIndex)
                continue
            else:
                break;
