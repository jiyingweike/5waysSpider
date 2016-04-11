package com.jiyingweike;

import us.codecraft.webmagic.Page;
import us.codecraft.webmagic.Site;
import us.codecraft.webmagic.Spider;
import us.codecraft.webmagic.pipeline.ConsolePipeline;
import us.codecraft.webmagic.processor.PageProcessor;

import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

/**
 * Author :keepcleargas
 * Date   :16/4/10.
 *
 * @see {url:http://webmagic.io/}
 */
public class JavaSpider implements PageProcessor {
    public static final String XXOO_ROOT = "http://jandan.net/ooxx/";
    public static final String XXOO_PAGE = "http://jandan.net/ooxx/page-";

    private Site site = Site
            .me()
            .setDomain("jandan.net/ooxx")
            .setSleepTime(500)
            .setUserAgent(
                    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.65 Safari/537.31");

    public static void main(String[] args) throws SQLException {
        Spider.create(new JavaSpider()).addPipeline(new ConsolePipeline()).addUrl(XXOO_ROOT).thread(5).run();
    }

    public void process(Page page) {
        String urlStr = page.getUrl().toString();
        if (urlStr.equals(XXOO_ROOT)) {
            String countStr = page.getHtml().css("span.current-comment-page", "text").toString();
            int count = Integer.parseInt(countStr.substring(1, countStr.length() - 1));
            for (int i = count; i > 0; i--) {
                //接口请求
                List<String> apiUrl = new ArrayList<String>();
                apiUrl.add(XXOO_PAGE + i);
                page.addTargetRequests(apiUrl);
            }
        } else if (urlStr.startsWith(XXOO_PAGE)) {
            //here xxoo images
            page.putField("images", page.getHtml().xpath("//a[@class='view_img_link']/@href").all());
        }
    }

    public Site getSite() {
        return site;
    }
}
