import scrapy
import sys
from datetime import date

sys.path.append("/home/jongeun/crawlers/crawler2/crawler2")


from items import Crawler2Item
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

todaystr = date.today().strftime("%Y%m%d")

class crawlingSpider(scrapy.Spider):


    name = "naver"
    allowed_domains = ["naver.co.kr"]

    start_urls = [
        "http://news.naver.com/main/list.nhn?sid2=262&sid1=101&mid=shm&mode=LS2D&date="+todaystr+"&page=1",
    ]

    def parse(self, response):
        for sel in response.xpath('//*[@id="main_content"]/div[2]/ul[1]'):
            item = NavercrawlerItem()
            item['title'] = sel.xpath('li/dl/dt/a/text()').extract()
            item['link'] = sel.xpath('li/dl/dt/a/@href').extract()
            item['writing'] = sel.xpath('li/dl/dd/span[1]/text()').extract()
            item['date'] = sel.xpath('li/dl/dd/span[2]/text()').extract()
            yield item

        for href in response.xpath('//*[@id="main_content"]/div[3]/a/@href'):
            url = response.urljoin(href.extract())
            #url= "http://news.naver.com/main/list.nhn?sid2=262&sid1=101&mid=shm&mode=LS2D&date="+todaystr+"&page="+pagecount
            print("url:"+url)
            yield scrapy.Request(url, callback=self.parse_dir_contents)

    def parse_dir_contents(self, response):
        print("parse_dir_contents!!!!!!!")
        for sel in response.xpath('//*[@id="main_content"]/div[2]/ul[1]'):
            item = NavercrawlerItem()
            item['title'] = sel.xpath('li/dl/dt/a/text()').extract()
            item['link'] = sel.xpath('li/dl/dt/a/@href').extract()
            item['writing'] = sel.xpath('li/dl/dd/span[1]/text()').extract()
            item['date'] = sel.xpath('li/dl/dd/span[2]/text()').extract()
            yield item


    """
    name = "yonhapnews"
    allowed_domains = ["yonhapnews.co.kr"]

    start_urls = [
        "http://www.yonhapnews.co.kr/economy/0320000001.html",
    ]


        #posts = hxs.select("//ul[@class="main_content"]/div']")
        #for sel in response.xpath('//*[@id="main_content"]/div[3]'):B
    def parse(self, response):
        print("parse!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!")
        items = []
        for sel in response.xpath('//*[@id="content"]/div[3]/div[1]/div/div[2]/ul'):
            item = NavercrawlerItem()
            item['title'] = sel.xpath('li/div/strong/a/text()').extract()
            item['link'] = sel.xpath('li/div/strong/a/@href').extract()
            item['writing'] = sel.xpath('li/div/p[2]/a/text()').extract()
            item['date'] = sel.xpath('li/div/p[2]/span/text()').extract()
            yield  item
    """

"""
    def parse_articles_follow_next_page(self, response):


        next_page = response.xpath('//*[@id="main_content"]/div[3]/a/@href')
        if next_page:
            url = response.urljoin(next_page[0].extract())
            print("url" + url)
            yield scrapy.Request(url, self.parse_articles_follow_next_page)
""""



"""
        for sel in response.xpath('//*[@id="main_content"]/div[2]/ul[1]'):
            item = NavercrawlerItem()
            item['title'] = sel.xpath('li/dl/dt/a/text()').extract()
            item['link'] = sel.xpath('li/dl/dt/a/@href').extract()
            item['writing'] = sel.xpath('li/dl/dd/span[1]/text()').extract()
            item['date'] = sel.xpath('li/dl/dd/span[2]/text()').extract()
            yield item


    #counting list of number of webpage
    def parse(self, response):
        for sel in response.xpath('//*[@id="main_content"]/div[2]/ul[1]'):
            pagecount = response.urljoin(sel.xpath('a/text()').extract())
            print("pagecount!!!!!!!!!!!!!!!!!!!!!!!!!:" + pagecount)
            url = "http://news.naver.com/main/list.nhn?sid2=262&sid1=101&mid=shm&mode=LS2D&date="+todaystr+"&page="+pagecount
            print("url:"+url)
            yield scrapy.Request(url, callback=self.parse_dir_contents)
"""
