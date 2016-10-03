# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy


class CommoditycrawlerItem(scrapy.Item):
    date = scrapy.Field()
    commodity = scrapy.Field()
    time = scrapy.Field()
    price = scrapy.Field()
    ratechange = scrapy.Field()
