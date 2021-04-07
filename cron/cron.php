<?php

use Drewfx\Salesforce\Plugin;
use Drewfx\Salesforce\Service\CronService;

/* Boostrap */
require_once dirname(__DIR__, 5) . '/wp-config.php';
require_once dirname(__DIR__, 4) . '/wp-load.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

$plugin = new Plugin();
$container = $plugin->getContainer();

/** @var CronService $cron */
try {
    $cron = $container->get(CronService::class);
    $cron->execute();
    echo sprintf('[Success]: Completed Salesforce Cron');
} catch (Exception $e) {
    echo sprintf('[Failed]: %s%s %s', $e->getMessage(), PHP_EOL, $e->getTraceAsString());
}
