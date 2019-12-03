<?php

namespace Evolver\Example;

use Evolver\Event;

require_once __DIR__ . '/../vendor/autoload.php';

include __DIR__ . '/events.php';

function dump($data)
{
    $json = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    echo $json . PHP_EOL;
}

// print_r($events);
//exit();

$eventStore = new \Evolver\EventStore\ArrayEventStore($events);
$stateStore = new \Evolver\StateStore\ArrayStateStore([]);

// print_r($events);exit();

$evolver = new \Evolver\Evolver($eventStore, $stateStore);

$evolver->addProjector(
    new \Evolver\Example\StreamProjector($evolver)
);


foreach ($events as $event) {
    $evolver->project($event);
}

$state = $evolver->getState('stream:stream1');
dump($state);

$state = $evolver->getState('user:alice');
dump($state);
$state = $evolver->getState('user:bob');
dump($state);
