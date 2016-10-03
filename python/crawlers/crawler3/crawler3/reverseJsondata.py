# -*- coding: utf-8 -*-
import json
import codecs
from datetime import date
import datetime
import logging
import MySQLdb.cursors
from pprint import pprint
from guppy import hpy

class reversedata(object):
    def __init__(self,daystring):
        self.mdaystring = daystring

        self.fileName = "newsnaver" + daystring + ".json"
        self.file = codecs.open(self.fileName, 'wb', encoding='utf-8')
        self.stack = []
        self.beforeindex = 0
        self.globalindex = 0
        self.newglobalindex = 0
        self.string_crawling_rawdata_table = daystring + "crawling_rawdata"
        self.string_crawling_rawdata_count_table= daystring + "crawling_rawdatacount"
        self.string_db_name = "crawling_rawdatabase"
        self.sqlstring = ""
        print(self.string_crawling_rawdata_table)
        try:
          self.conn = MySQLdb.connect(user='hive', passwd='740412', db='crawling_rawdatabase', host='localhost', charset="utf8", use_unicode=True)
          self.cursor = self.conn.cursor()
          isExist = self.create_today_table(self.mdaystring)
          if isExist == True:
             self.beforeindex = self.getcrawling_rawdataindex()
             print("self.beforeindex:%d" % (self.beforeindex))
        except MySQLdb.Error, e:
          print "Error %d: %s" % (e.args[0], e.args[1])
          #sys.exit(1)


    def getGlobalIndex(self):
        return index
    def stackData(self,data,index):
        self.stack.append(data)
        self.globalindex = index

    def ConvertString(self, data):
        returnString = data['index'] + "\t" + data['uploadtime'] + "\t" + data['title'] + "\t" + data['link'] + "\t" + data['writing']
        return returnString

    def createReverseJsonData(self):
        count = self.globalindex
        data  = {}
        print("size:"+ str(len(self.stack)))

        while True:
            popdata = self.stack.pop()
            popdata['index'] = self.newglobalindex
            data['index'] = popdata['index']
            data['title'] = popdata['title']
            data['link'] = popdata['link']
            data['writing'] = popdata['writing']
            data['uploadtime'] = popdata['uploadtime']
            line = json.dumps(dict(data), ensure_ascii=False) + "\n" #Item을 한줄씩 구성
            #line  = self.ConvertString(data) + "\n"
            self.file.write(line)
            self.newglobalindex = self.newglobalindex + 1
            if self.newglobalindex > self.beforeindex:
                self.savecrawling_jsondata(popdata)
            if len(self.stack) == 0:
                if self.newglobalindex > self.beforeindex:
                    self.savecrawling_rawdataindex(self.newglobalindex)
                break;

        h = hpy()
        print h.heap()

    def create_today_table(self, day):
        try:
        #    self.sqlstring = "CREATE TABLE IF NOT EXISTS " + str(self.string_crawling_rawdata_table) + "(`cindex` varchar(5)NOT NULL,`title` varchar(200) DEFAULT NULL,`link` varchar(500) DEFAULT NULL,`writing` varchar(20) DEFAULT NULL,`uploadtime` tinyint(8) DEFAULT NULL, PRIMARY KEY (`cindex`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
            self.sqlstring = "CREATE TABLE IF NOT EXISTS " + str(self.string_crawling_rawdata_table) + "(`cindex` int(11) NOT NULL, `title` varchar(200) DEFAULT NULL,`link` varchar(200) DEFAULT NULL,`writing` varchar(20) DEFAULT NULL,`uploadtime` int(11) DEFAULT NULL, PRIMARY KEY (`cindex`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;"
            self.cursor.execute(self.sqlstring)
            self.sqlstring = "CREATE TABLE IF NOT EXISTS " + str(self.string_crawling_rawdata_count_table) + "(`id` tinyint(4) NOT NULL AUTO_INCREMENT, `crawlingdatacount` varchar(5) DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8"
            self.cursor.execute(self.sqlstring)
            result = self.cursor.fetchone()
            exist = False
            if not result:
                exist = True
                self.getcrawling_rawdataindex()
        except MySQLdb.Error, e:
            exist = False
            print "Error %d: %s" % (e.args[0], e.args[1])

        return exist

    def getcrawling_rawdataindex(self):
        self.sqlstring = "SELECT crawlingdatacount FROM " + str(self.string_crawling_rawdata_count_table) + " WHERE id=1"
        self.cursor.execute(self.sqlstring)
        result = self.cursor.fetchone()
        if result:
            resultstirng = "".join(result)
            print(resultstirng)
            print("getcrawling_rawdataindex!!!!")
            return int(resultstirng)
        else:
            return 0

    def savecrawling_rawdataindex(self, index):
        self.sqlstring = "select * from " + self.string_db_name + "." + str(self.string_crawling_rawdata_count_table) + " WHERE id=1"
        self.cursor.execute(self.sqlstring)
        result = self.cursor.fetchone()
        if result:
            print("rawdataindex already exist")
            string1 = "UPDATE `%s` " % self.string_crawling_rawdata_count_table
            self.sqlstring = string1 + "SET crawlingdatacount=%s WHERE id=1"
            print(self.sqlstring)
            self.cursor.execute(self.sqlstring,str(index))
            self.conn.commit()
        else:
            print("else savecrawling_rawdataindex!!!")
            self.sqlstring = "INSERT INTO " +  self.string_db_name + "." + str(self.string_crawling_rawdata_count_table) + "(crawlingdatacount) values(%s)"
            self.cursor.execute(self.sqlstring,(str(index)))
            self.conn.commit()

    def savecrawling_jsondata(self, item):
        string1 = "INSERT INTO " + self.string_db_name + "." + str(self.string_crawling_rawdata_table)
        string2 = " (cindex, title, link, writing, uploadtime) values(%s, %s, %s, %s, %s)"
        self.sqlstring = string1 + string2
        string3 = self.sqlstring % (item['index'], item['title'],item['link'],item['writing'],item['uploadtime'])
        #print(self.sqlstring);
        #self.cursor.execute(self.sqlstring)
        self.cursor.execute(self.sqlstring,(item['index'], item['title'],item['link'],item['writing'],item['uploadtime']))
        self.conn.commit()
