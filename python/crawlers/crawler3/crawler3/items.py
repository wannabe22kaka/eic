# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy


class Crawler3Item(scrapy.Item):
    index = scrapy.Field()
    title = scrapy.Field()
    link = scrapy.Field()
    writing = scrapy.Field()
    uploadtime = scrapy.Field()
