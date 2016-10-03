import scrapy
import sys
from datetime import date

sys.path.append("/home/jongeun/crawlers/crawler1/crawler1")
sys.path.append("/home/jongeun/crawlers/crawlermodule")

from items import Crawler1Item
from modificationtime import local_to_utc,utc_to_local,modification_time,checktodaynew

"""
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
"""


class crawlingSpider(scrapy.Spider):

    todaystr = date.today().strftime("%Y%m%d")
    name = "mt"
    allowed_domains = ["m.mt.co.kr"]

    start_urls = [
        "http://news.mt.co.kr/newsList.html?type=1&comd=&pDepth=news&pDepth1=world&pDepth2=Weconomy&page=1",
    ]


        #posts = hxs.select("//ul[@class="main_content"]/div']")
        #for sel in response.xpath('//*[@id="main_content"]/div[3]'):B
    def parse(self, response):
        print("parse!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!")
        count = 0
        for sel in response.xpath('//*[@id="content"]/ul'):
            item = Crawler1Item()
            #item['title'] = sel.xpath('li/div/strong/a/text()').extract()
            #item['link'] = sel.xpath('li/a/@href').extract()
            #item['writing'] = sel.xpath('li/div/p/a/text()').extract()
            #modification_time(sel.xpath('li/div/p/span/text()').extract())
            #item['date'] = sel.xpath('li/div/p/span/text()').extract()
            item['date'] = modification_time(sel.xpath('li/div/p/span/text()').extract())
            count += 1
            print(count)
            return  item


"""
    #counting list of number of webpage
    def parse(self, response):
        for sel in response.xpath('//*[@id="main_content"]/div[3]'):
            pagecount = response.urljoin(sel.xpath('a/text()').extract())
            print("pagecount!!!!!!!!!!!!!!!!!!!!!!!!!:" + pagecount)
            url = "http://news.naver.com/main/list.nhn?sid2=262&sid1=101&mid=shm&mode=LS2D&date="+todaystr+"&page="+pagecount
            print("url:"+url)
            yield scrapy.Request(url, callback=self.parse_dir_contents)

    def parse_dir_contents(self, response):
        for sel in response.xpath('//*[@id="main_content"]/div[2]/ul[1]'):
            item = NavercrawlerItem()
            item['title'] = sel.xpath('li/dl/dt/a/text()').extract()
            item['link'] = sel.xpath('li/dl/dt/a/@href').extract()
            item['writing'] = sel.xpath('li/dl/dd/[@id="writing"]/text()').extract()
            item['date'] = sel.xpath('li/dl/dd/[@id="date"]/text()').extract()
            yield item
"""
