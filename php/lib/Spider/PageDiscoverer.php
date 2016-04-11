<?php

namespace Spider;

use Symfony\Component\DomCrawler\Crawler;
use VDB\Spider\Discoverer\CrawlerDiscoverer;
use VDB\Spider\Uri\DiscoveredUri;
use VDB\Spider\Resource;
use VDB\Uri\Uri;


/**
 * Created by PhpStorm.
 * User: chenqisheng
 * Date: 16/4/11
 * Time: 15:59
 */
class PageDiscoverer extends CrawlerDiscoverer
{
    /** @var string */
    protected $xxoo_page = "http://jandan.net/ooxx/page-";
    protected $xxoo_root = "http://jandan.net/ooxx/";

    /**
     * @return Crawler
     */
    protected function getFilteredCrawler(Resource $resource)
    {

    }

    /**
     * @param Resource $resource
     * @return DiscoveredUri[]
     */
    public function discover(Resource $resource)
    {
        $uris = array();
        if ($resource->getUri()->__toString() == $this->xxoo_root) {
            $countText = $resource->getCrawler()->filter('span.current-comment-page')->text();
            $pageCount = (int)(substr($countText, 1, count($countText) - 2));
            //ugly
            for ($i = $pageCount - 100; $i <= $pageCount; $i++) {
                $uris[] = new DiscoveredUri(new Uri($this->xxoo_page . $i, $resource->getUri()->toString()));
            }
        }
        return $uris;
    }
}