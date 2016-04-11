<?php
/**
 * Created by PhpStorm.
 * User: chenqisheng
 * Date: 16/4/11
 * Time: 16:39
 */
require __DIR__ . '/vendor/autoload.php';
$loader = new \Composer\Autoload\ClassLoader();
// register classes with namespaces
$loader->add('Spider', __DIR__ . '/lib');
// activate the autoloader
$loader->register();