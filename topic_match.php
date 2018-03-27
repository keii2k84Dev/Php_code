<?php

require_once __DIR__ . '/consumer_skeleton/SignalHandler.php';
require_once __DIR__ . '/consumer_skeleton/Consumer.php';
require_once __DIR__.'/vendor/autoload.php';

try {
    $control = new Consumer();
    $control->runConsumer();
} catch (\Exception $e) {
}
