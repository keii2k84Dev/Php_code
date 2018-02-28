<?php

require_once __DIR__ . '/src/SignalHandler.php';
require_once __DIR__ . '/src/Consumer.php';
require_once __DIR__.'/vendor/autoload.php';

try {
    $control = new Consumer();
    $control->runConsumer();
} catch (\Exception $e) {
}
