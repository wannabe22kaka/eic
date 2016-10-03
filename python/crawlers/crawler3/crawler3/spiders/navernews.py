import scrapy
import sys
from datetime import date

sys.path.append("/home/jongeun/crawlers/crawler3/crawler3")


from items import Crawler3Item
import time




time.strftime("%Y-%m-%d %H:%M:%S",
              time.gmtime(time.mktime(time.strptime("2008-09-17 14:04:00",
                                                    "%Y-%m-%d %H:%M:%S"))))
def local_to_utc(t):
    print(type(t))
    x = '%s' % (t[0])
    ultimatelytime = str(date.today().year)+"-" + x + ":00"
    print("ultimatelytime:" + ultimatelytime)
    return time.strftime("%Y-%m-%d %H:%M:%S",
                  time.gmtime(time.mktime(time.strptime(ultimatelytime,
                                                        "%Y-%m-%d %H:%M:%S"))))

def utc_to_local(t):
    secs = calendar.timegm(t)
    return time.localtime(secs)

def edit_day(day):
    length = len(day)
    return day[4:length]


#todaystr = date.today().strftime("%Y%m%d")
todaystr = edit_day(sys.argv[3])
class crawlingSpider(scrapy.Spider):
    name = "naver"
    allowed_domains = ["news.naver.com"]

    start_urls = [
       "http://news.naver.com/main/list.nhn?sid2=262&sid1=101&mid=shm&mode=LS2D&date="+todaystr+"&page=1",

    ]

    def parse(self, response):
        for sel in response.xpath('//*[@id="main_content"]/div[2]/ul[1]'):
            item = Crawler3Item()
            item['title'] = sel.xpath('li/dl/dt/a/text()').extract()
            item['link'] = sel.xpath('li/dl/dt/a/@href').extract()
            item['writing'] = sel.xpath('li/dl/dd/span[1]/text()').extract()
            item['uploadtime'] = sel.xpath('li/dl/dd/span[@class="date"]/text()').extract()
            yield item
        for href in response.xpath('//*[@id="main_content"]/div[3]/a/@href'):
            url = response.urljoin(href.extract())
            yield scrapy.Request(url, callback=self.parse_dir_contents)


    def parse_dir_contents(self, response):
        print("parse_dir_contents!!!!!!!")
        var3 = sys.argv[3]

        for sel in response.xpath('//*[@id="main_content"]/div[2]/ul[1]'):
            item = Crawler3Item()
            item['title'] = sel.xpath('li/dl/dt/a/text()').extract()
            item['link'] = sel.xpath('li/dl/dt/a/@href').extract()
            item['writing'] = sel.xpath('li/dl/dd/span[1]/text()').extract()
            item['uploadtime'] = sel.xpath('li/dl/dd/span[@class="date"]/text()').extract()
            yield item
