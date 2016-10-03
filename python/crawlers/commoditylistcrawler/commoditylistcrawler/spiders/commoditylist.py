# -*- coding: utf-8 -*-
import scrapy
import sys
from datetime import date

sys.path.append("/home/jongeun/crawlers/commoditylistcrawler/commoditylistcrawler")


from items import CommoditylistcrawlerItem
from scrapy.selector import Selector
from scrapy.spiders import Spider
from scrapy.selector import HtmlXPathSelector

reload(sys)
sys.setdefaultencoding('utf-8')


import logging
import MySQLdb.cursors


from dbcontroller import DBcontroller

class commoidtylistSpider(scrapy.Spider):
    name = "commoditylist"
    allowed_domains = ["www.indexmundi.com"]

    url = "http://www.indexmundi.com/commodities/?commodity=Bananas&months=360"


    start_urls = [
       url,
    ]


    def parse(self, response):
        for sel in response.xpath('//*[@id="listCompare"]/option'):
            item = CommoditylistcrawlerItem()
            #//a[contains(@href, "image")]/@href'
            item['value'] = sel.xpath('@value').extract()
            yield item
