<?php

require_once __DIR__ . '/src/SignalHandler.php';
require_once __DIR__ . '/src/Consumer.php';

//Logger::get()->info("----  consumer start. ----");
try {
    $control = new Consumer();
    $control->runConsumer();
} catch (\Exception $e) {
//    Logger::get()->warning(' consumer: ' . $e->getMessage());
}
//Logger::get()->info("----  consumer end. ----");
