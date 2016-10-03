# -*- coding: utf-8 -*-
import scrapy
import sys
from datetime import date



sys.path.append("/home/jongeun/crawlers/commoditycrawler/commoditycrawler")

from items import CommoditycrawlerItem
from scrapy.selector import Selector
from scrapy.spiders import Spider
from scrapy.selector import HtmlXPathSelector

reload(sys)
sys.setdefaultencoding('utf-8')


import logging
import MySQLdb.cursors
from dbcontroller import DBcontroller

class commoidtydataSpider(scrapy.Spider):
    name = "commoditydata"
    allowed_domains = ["www.indexmundi.com"]

    def getcategoryfromURL(self,url):
        standardstring = "http://www.indexmundi.com/commodities/?commodity="
        initindex = len(standardstring)
        endindex = url.index('&')
        commodity = url[initindex:endindex]
        return commodity


    def start_requests(self):
        self.count = 0;
        controller = DBcontroller("commoditycategory","commoditydb")
        print("controller")
        recs = controller.select_table("SELECT commodity FROM ")
        #set url from commoditylist
        for rec in recs:
            url = "http://www.indexmundi.com/commodities/?commodity=%s&months=360" % (rec)
            yield scrapy.Request(url, self.parse)

    def parse(self, response):
        commodity = self.getcategoryfromURL(response.url)
        for sel in response.xpath('//*[@id="panelMain"]/div[4]/table[contains(tbody, "")]/tr'):
            item = CommoditycrawlerItem()
            item['commodity'] = commodity;
            item['date'] = sel.xpath('td[1]/text()').extract();
            item['price'] = sel.xpath('td[2]/text()').extract();
            item['ratechange'] = sel.xpath('td[3]/text()').extract();
            yield item
