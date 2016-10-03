# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# http://doc.scrapy.org/en/latest/topics/items.html

import scrapy


class ImportcrawlerItem(scrapy.Item):
    country = scrapy.Field()
    desc = scrapy.Field()
    units = scrapy.Field()
    percent = scrapy.Field()
