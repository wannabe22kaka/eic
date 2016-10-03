# -*- coding: utf-8 -*-

# Define your item pipelines here
#
# Don't forget to add your pipeline to the ITEM_PIPELINES setting
# See: http://doc.scrapy.org/en/latest/topics/item-pipeline.html

import json
import codecs
class Crawler1Pipeline(object):
        def __init__(self):
            self.file = codecs.open('data.json', 'wb', encoding='utf-8')
        #def process_item(self, item, spider):
        #    return item

        def process_item(self, item, spider):
            line = json.dumps(dict(item), ensure_ascii=False) + "\n" #Item을 한줄씩 구성
            self.file.write(line) #파일에 기록
            return item
        def spider_closed(self, spider):
            self.file.close()   #파일 CLOSE
