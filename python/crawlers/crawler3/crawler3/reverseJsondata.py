# -*- coding: utf-8 -*-
import json
import codecs
from datetime import date
import datetime
import logging
import MySQLdb.cursors
from pprint import pprint
from guppy import hpy
import csv

class reversedata(object):
    def __init__(self,daystring):
        self.mdaystring = daystring
        self.stack = []
        #self.string_crawling_rawdata_table = "standard_data"
        #self.string_db_name = "standard_data"
        self.sqlstring = ""
        self.finallytimestamp = 0
        self.fileName = "newsnaver" + self.mdaystring + ".txt"  
        self.file = codecs.open(self.fileName, 'wb', encoding='utf-8')
        #self.csvfile = csv.writer(self.file)
        #print(self.string_crawling_rawdata_table)
        #try:
          #self.conn = MySQLdb.connect(user='hive', passwd='740412', db=self.string_db_name, host='localhost', charset="utf8", use_unicode=True)
          #self.cursor = self.conn.cursor()
          #self.getKeywordDirectionMapData()
        #except MySQLdb.Error, e:
          #print "Error %d: %s" % (e.args[0], e.args[1])
          #sys.exit(1)


    def stackData(self,data,index):
        self.stack.append(data)
        self.globalindex = index

    def ConvertString(self, data):
        returnString = data['index'] + "\t" + data['uploadtime'] + "\t" + data['title'] + "\t" + data['link'] + "\t" + data['writing']
        return returnString

    def classificationPostiveNegativeWord(self, title):
        trimtitle = title.strip()
        print(trimtitle)
        # splittoken = self.stringSplitToken(title)
        # self.getKeywordDirectionMapData()
        # self.isExistMapData(splittoken)

        # for isExistMapData(self)

        # for word in splittoken:
        #     if isExistMapData(word):
        #         insert

        while True:
            positive = input("input positive is 1 or negative is 0 : ")
            if positive == 1 or positive == 0:
                print(positive)
                return trimtitle + "," + str(positive) +"\n"
                break;

    def stringSplitToken(self, title):
        return title.split(' ')

    def getKeywordDirectionMapData(self):
        sql = "select * from KeywordDirection"
        self.cursor.execute(sql)
        rows = self.cursor.fetchall()
        self.keywordDirectionMapData = dict((x, y) for x, y in rows)
        self.conn.close()
        
    def isExistMapData(self, titleKeyword):
        return titleKeyword in self.keywordDirectionMapData.keys() 

    # def insertToKeywordAndDirectionData(self, token, direction):
    #     string1 = "INSERT INTO " + str(self.string_crawling_rawdata_table)
    #     string2 = "(keyword, direction) values(%s,%s)"
    #     self.sqlstring = string1 + string2
    #     self.cursor.execute(self.sqlstring,(token, direction)
    #     self.conn.commit()

    # def insertTitleStringDirectionData(self, title, direction):
    #     string1 = "INSERT INTO " + str(self.string_crawling_rawdata_table)
    #     string2 = "(keyword, direction) values(%s,%s)"
    #     self.sqlstring = string1 + string2
    #     self.cursor.execute(self.sqlstring,(token, direction)
    #     self.conn.commit()


    def createReverseJsonData(self):
        data  = {}
        print("size:"+ str(len(self.stack)))
        while self.stack:
            popdata = self.stack.pop()
            data['title'] = popdata['title']
            data['link'] = popdata['link']
            data['writing'] = popdata['writing']
            data['uploadtime'] =str(popdata['uploadtime'])
            data['portal'] = popdata['portal']
            csvRow = self.classificationPostiveNegativeWord(popdata['title'])
            self.file.write(csvRow)
            #self.file.write(csvRow)
            #self.savecrawling_jsondata(data)

    def closeOutputData(self):
         self.file.close()

    # def getFinallyTimeStamp(self, id):
    #     sqlstring = "select JSON_EXTRACT(jsondata, '$.uploadtime')  from " + self.string_db_name + "." + self.string_crawling_rawdata_table + " where id=" + str(id)
    #     print(sqlstring)
    #     result = self.executequery(sqlstring)
    #     if result:
    #         print(result)
    #         resultstirng = "".join(result)
    #         _finallyStamp = int(resultstirng) + 1
    #         return _finallyStamp
    #     else:
    #         return 0



    # def getFinallyID(self):
    #     self.sqlstring = "select count(*) from " + self.string_db_name + "." + self.string_crawling_rawdata_table
    #     result = self.executequery(self.sqlstring)

    #     if result:
    #         resultstirng = " ".join(str(v) for v in result)
    #         if resultstirng ==  "0":
    #             return 0;
    #         else:
    #             return int(resultstirng)



    # def savecrawling_data(self, item):
    #     string1 = "INSERT INTO " + str(self.string_crawling_rawdata_table)
    #     string2 = "(title, link, writing, time, portal) values(%s,%s,%s,%s,%s)"
    #     self.sqlstring = string1 + string2
    #     self.cursor.execute(self.sqlstring,(item['title'],item['link'],item['writing'],item['uploadtime'],item['portal']))
    #     self.conn.commit()



    # def savecrawling_jsondata(self, item):
    #     string1 = "INSERT INTO " + self.string_db_name + "." + str(self.string_crawling_rawdata_table)
    #     string2 = "(time,jsondata) values(from_unixtime(%s),%s)"
    #     self.sqlstring = string1 + string2
    #     line = json.dumps(dict(item), ensure_ascii=False)
    #     type(line)
    #     #self.cursor.execute(self.sqlstring,(item['uploadtime'],line))
    #     #self.conn.commit()


    # #this won't get result of query
    # def executequery(self, sqlquery,factor):
    #     self.cursor.execute(sqlquery,factor)
    #     self.conn.commit()

    # #this will get result of query
    # def executequery(self, sqlquery):
    #     self.cursor.execute(sqlquery)
    #     self.conn.commit()
    #     result = self.cursor.fetchone()
    #     return result

    # def insertToKeywordData(self, item):
    #     string1 = "INSERT INTO " + self.string_db_name + "." + str(self.string_crawling_rawdata_table)
    #     string2 = "(keyword, direction) values(%s,%s)"
    #     self.sqlstring = string1 + string2
    #     line = json.dumps(dict(item), ensure_ascii=False)
    #     type(line)      

