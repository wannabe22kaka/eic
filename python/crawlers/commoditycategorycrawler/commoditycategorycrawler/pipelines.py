# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html
import sys
sys.path.append("/home/jongeun/crawlers/commoditycategorycrawler/commoditycategorycrawler")

from dbcontroller import DBcontroller

class CommoditycategorycrawlerPipeline(object):

    def __init__(self):
        self.tablename = "commoditycategory"
        self.controller = DBcontroller(self.tablename,"commoditydb")

    def process_item(self, item, spider):
        self.controller.excutesql(self.getInsert_stmt(),self.getData_stmt(item))
        return item


    def getInsert_stmt(self):
        insert_stmt = (
        "INSERT INTO " + self.tablename + " (commodity,category)"
        "values(%s,%s)"
        )
        return insert_stmt

    def getData_stmt(self,item):
        data_stmt = (item['commodity'],item['category'])
        return data_stmt
