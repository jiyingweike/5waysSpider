<?php

use Spider\PageDiscoverer;
use Symfony\Component\EventDispatcher\Event;
use VDB\Spider\Event\SpiderEvents;
use VDB\Spider\StatsHandler;
use VDB\Spider\Spider;

require_once('Bootstrap.php');

$xxoo_root = "http://jandan.net/ooxx/";

// Create Spider
$spider = new Spider($xxoo_root);

// Add a URI discoverer. Without it, the spider does nothing. In this case, we want <a> tags from a certain <div>
$spider->getDiscovererSet()->set(new PageDiscoverer(""));

// Let's add something to enable us to stop the script
$spider->getDispatcher()->addListener(
    SpiderEvents::SPIDER_CRAWL_USER_STOPPED,
    function (Event $event) {
        echo "\nCrawl aborted by user.\n";
        exit();
    }
);

// Add a listener to collect stats to the Spider and the QueueMananger.
// There are more components that dispatch events you can use.
$statsHandler = new StatsHandler();
$spider->getQueueManager()->getDispatcher()->addSubscriber($statsHandler);
$spider->getDispatcher()->addSubscriber($statsHandler);

// Execute crawl
$spider->crawl();

// Build a report
echo "\n  ENQUEUED:  " . count($statsHandler->getQueued());
echo "\n  SKIPPED:   " . count($statsHandler->getFiltered());
echo "\n  FAILED:    " . count($statsHandler->getFailed());
echo "\n  PERSISTED:    " . count($statsHandler->getPersisted());

//Here Xxoo Images
foreach ($spider->getDownloader()->getPersistenceHandler() as $resource) {
    echo "\n - downloaded url:" . $resource->getUri();
    $images = [];
    foreach ($resource->getCrawler()->filterXpath("//a[@class='view_img_link']")->extract('href') as $node) {
        $images [] = $node;
    }
    $imagesStr = implode(", ", $images);
    echo "\n - images:" . $imagesStr;
}
