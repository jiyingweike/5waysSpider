#coding=utf-8
import urllib
import re

def getHtml(url):
    page = urllib.urlopen(url)
    html = page.read()
    return html

def getImg(html):
    reg = r'(http:[^\s]*?.jpg)'
    #reg = r'src="(.+?\.jpg)" pic_ext'
    imgre = re.compile(reg)
    imglist = re.findall(imgre,html)
    x = 0
    for imgurl in imglist:
        #urllib.urlretrieve(imgurl,'%s.jpg' % x)
        print(imgurl)
        x+=1
#提取10页图片
for i in range(1930,1940):
    html=getHtml("http://jandan.net/ooxx/page-"+str(i)+"#comments")
    getImg(html)

