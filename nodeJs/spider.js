/**
 * Created by keepcleargas on 16/4/11.
 */

var Crawler = require("simplecrawler");
var cheerio = require('cheerio');

var xxoo_root = "http://jandan.net/ooxx";
var xxoo_page = "http://jandan.net/ooxx/page-"
var crawler = Crawler.crawl(xxoo_root);


crawler.interval = 500;

crawler.on("fetchcomplete", function (queueItem, data) {
    console.log("Completed fetching resource:", queueItem.url);
    var $ = cheerio.load(data.toString("utf8"));
    var images = $('a.view_img_link[href]').map(function () {
        return $(this).attr("href");
    }).get();
    //here xxoo images
    console.log("images:" + images);
});

crawler.complete = function (queueItem) {
    console.log("complete crawler");
}
crawler.discoverResources = function (buffer, queueItem) {
    var apiUrl = [];
    if (queueItem.url == xxoo_root) {
        var $ = cheerio.load(buffer.toString("utf8"));
        var countText = $("span.current-comment-page").html();
        var count = parseInt(countText.substr(1, countText.length - 1));
        for (var i = count; i > 0; i--) {
            console.log("add url:" + xxoo_page + i);
            apiUrl.push(xxoo_page + i);
        }
    }
    return apiUrl;
};


crawler.start();