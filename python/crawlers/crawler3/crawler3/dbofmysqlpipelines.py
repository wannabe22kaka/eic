# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html
import json
import codecs
from scrapy.xlib.pydispatch import dispatcher
from scrapy import signals

from datetime import date



from __future__ import unicode_literals

from twisted.enterprise import adbapi
import datetime
import logging
import MySQLdb.cursors

class Crawler3Pipeline(object):
    def __init__(self):
        dispatcher.connect(self.spider_closed, signals.spider_closed)
        self.pagecount = 1
        self.globalIndex = 0
        self.todaystr = date.today().strftime("%Y%m%d")
        self.fileName = "newsnaver" + self.todaystr + ".json"
        self.file = codecs.open(self.fileName, 'wb', encoding='utf-8')
        #mysql
        try:
            self.conn = MySQLdb.connect(user='hive', passwd='740412', db='crawling_rawdatabase', host='localhost', charset="utf8", use_unicode=True)
            self.cursor = self.conn.cursor()
            isExist = create_today_table(todaystr)
            if isExist == True
               getrowcount_table
        except MySQLdb.Error, e:
            print "Error %d: %s" % (e.args[0], e.args[1])
            sys.exit(1)
        #self.init_jsonstring()

    def process_item(self, item, spider):
    #    line = json.dumps(dict(item), ensure_ascii=False) + "\n" #Item을 한줄씩 구성
    #    print("item:"+ line)
    #    self.file.write(line) #파일에 기록
        #self.init_jsonstring()
        self.refine_crawldata(item)
        return item

    def spider_closed(self, spider):
        self.close_jsonstring()
        self.file.close()   #파일 CLOSE

    def init_jsonstring(self):
        #line = "{\n" +"\"domain\":\"naver\",\n" +"\"crawldate\":\"" + self.todaystr + "\",\n" + "\"datas\": [" + "\n"
        self.file.write(line) #파일에 기록

    def close_jsonstring(self):
        #line = "{}\n" +"]," + "\n" + "\"totalcount\":" + str(self.globalIndex)  + "\n" +"}\n"
        line = "{\"index\": \"" + str(self.globalIndex)+ "\" , \"uploadtime\": \"\" , \"title\": \"end\" , \"link\": \"\" , \"writing\": \"\"}"
        self.file.write(line) #파일에 기록
    def getrowcount_table(self, today):
        self.cursor.execute("select count(*) from %scrawling_rawdata",stringday)
        count = self.cursor.fetchone()
        return count

    def create_today_table(self, today):
        CREATE TABLE IF NOT EXISTS members ( id int , email varchar(45) )
        self.cursor.execute("select * from %scrawling_rawdata",stringday)
        result = self.cursor.fetchone()
        exist = False
        if result:
            print("table already exist")
            exist = True
        else:
            try:
                self.cursor.execute("CREATE TABLE IF NOT EXISTS `%scrawling_rawdata` (`index` tinyint DEFAULT NULL,`title` varchar(200) DEFAULT NULL,`link` varchar(500) DEFAULT NULL,`writing` varchar(20) DEFAULT NULL,`uploadtime` varchar(20) DEFAULT NULL, PRIMARY KEY (`index`)) ENGINE=InnoDB DEFAULT CHARSET=utf8",stringday)
                self.conn.commit()
                self.cursor.execute("CREATE TABLE IF NOT EXISTS `%scrawling_rawdataindex` (`index` int DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8",stringday)
                self.conn.commit()
                exist = True
            except MySQLdb.Error, e:
                exist = False
                print "Error %d: %s" % (e.args[0], e.args[1])

        return exist

    def stuck_data_onmysql(self,data)
        self.cursor.execute("select * from crawling_database ")

    def refine_titles(self, titlelist):
        list = []
        for data in titlelist:
            print(str(len(data)))
            if len(data) < 12:
                print("find wrong title")
            else:
                list.append(data)
                print(data)

        return list

    def refine_links(self, titlelist):
        list = []
        beforestring = ""
        for data in titlelist:
            print(data)
            if beforestring != data:
                list.append(data)
                beforestring = data

        return list

    def refine_crawldata(self,item):
        title = self.refine_titles(item['title'])
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
                data['index'] = str(self.globalIndex)
                data['title'] = title[count]
                data['link'] = link[count]
                data['writing'] = writing[count]
                data['uploadtime'] = date[count]
                '''if count == endcount:
                    line = json.dumps(dict(data), ensure_ascii=False) + "\n" #Item을 한줄씩 구성
                    self.file.write(line)
                    print("item:"+ line)
                    #self.close_jsonstring()
                    self.pagecount = self.pagecount + 1

                else:
                    line = json.dumps(dict(data), ensure_ascii=False) + "," + "\n" #Item을 한줄씩 구성
                    self.file.write(line)
                    print("item:"+ line)
'''
                line = json.dumps(dict(data), ensure_ascii=False) + "," + "\n" #Item을 한줄씩 구성
                self.file.write(line)
                print("item:"+ line)

                count = count +1
                self.globalIndex = self.globalIndex + 1
                continue
            else:
                break;
