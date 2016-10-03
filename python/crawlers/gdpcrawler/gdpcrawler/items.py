# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy


class GdpcrawlerItem(scrapy.Item):
    country = scrapy.Field()
    currency = scrapy.Field()
    currencyunit = scrapy.Field()
    currencyamount = scrapy.Field()
    growrate = scrapy.Field()
