# -*- coding: utf-8 -*-
import scrapy
import sys
from datetime import date

sys.path.append("/home/jongeun/crawlers/commoditycategorycrawler/commoditycategorycrawler")

from items import CommoditycategorycrawlerItem
from scrapy.selector import Selector
from scrapy.spiders import Spider
from scrapy.selector import HtmlXPathSelector

reload(sys)
sys.setdefaultencoding('utf-8')


import logging
import MySQLdb.cursors
from dbcontroller import DBcontroller

class commoidtycategorycrawlerSpider(scrapy.Spider):
    name = "commoditycategory"
    allowed_domains = ["www.indexmundi.com"]
    url = url = "http://www.indexmundi.com/commodities/?commodity=Bananas&months=360"
    start_urls = [
        url,
    ]
    def getcategoryfromhref(self,href):
        standardstring = "?commodity="
        initindex = len(standardstring)
        if href.find("months") == -1:
            return ""
        else:
            endindex = href.index('&')
            commodity = href[initindex:endindex]
            return commodity

    def parse(self, response):
        categorylist = ["10", "33", "37", "46","48", "52","54", "57"
        ,"70", "83","100", "105", "107"]
        for num in categorylist:
            category = response.xpath('//*[@id="dropdown'+ num +'"]/a/text()').extract();
            for sel in response.xpath('//*[@id="dropdown-lvl'+ num + '"]/div/ul/li'):
                    commoidty = self.getcategoryfromhref(" ".join(sel.xpath('a/@href').extract()))
                    if commoidty != "":
                        item = CommoditycategorycrawlerItem()
                        item['category'] = " ".join(category)
                        item['commodity'] = commoidty
                        yield item


    def generatoritem(self,num,response):
        category = response.xpath('//*[@id="dropdown'+ num +'"]/a/text()').extract();
        for sel in response.xpath('//*[@id="dropdown-lvl'+ num + '"]/div/ul/li'):
                item = CommoditycategorycrawlerItem()
                item['category'] = category;
                item['commodity'] = sel.xpath('a/text()').extract();
                yield item
