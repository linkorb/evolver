<?php

$events = [];

$events[] = [
    'type' => 'stream:create',
    'meta' => [
        'ip' => '1.2.3.4',
        'createdAt' => '2019-01-01 12:00:00',
        'createdBy' => 'alice',
    ],
    'payload' => [
        'streamId' => 'stream1',
        'title' => 'My first stream',
        'channel' => 'project-x',
        'visibility' => 'private',
        'user' => 'alice'
    ],
];


$events[] = [
    'type' => 'stream:add-user',
    'meta' => [
        'ip' => '9.9.9.9',
        'createdAt' => '2019-01-03 12:01:00',
    ],
    'payload' => [
        'streamId' => 'stream1',
        'user' => 'alice',
        'newUser' => 'bob',
    ],
];

$events[] = [
    'type' => 'stream:message',
    'meta' => [
        'ip' => '9.9.9.9',
        'createdAt' => '2019-01-01 12:10:00',
    ],
    'payload' => [
        'user' => 'alice',
        'streamId' => 'stream1',
        'text' => 'Hello @all! This is my beautiful channel',
        'messageId' => 'hello-world-message-1',
    ],
];

$events[] = [
    'type' => 'stream:react',
    'meta' => [
        'ip' => '9.9.9.9',
        'createdAt' => '2019-01-01 12:20:00',
    ],
    'payload' => [
        'streamId' => 'stream1',
        'user' => 'bob',
        'reaction' => 'like',
        'messageId' => 'hello-world-message-1',
    ],
];

$events[] = [
    'type' => 'stream:message',
    'meta' => [
        'ip' => '9.9.9.9',
        'createdAt' => '2019-01-01 12:30:00',
    ],
    'payload' => [
        'streamId' => 'stream1',
        'user' => 'bob',
        'text' => 'Awesome, @alice!',
    ],
];

$events[] = [
    'type' => 'stream:add-user',
    'meta' => [
        'ip' => '9.9.9.9',
        'createdAt' => '2019-01-03 13:10:00',
    ],
    'payload' => [
        'streamId' => 'stream1',
        'user' => 'bob',
        'newUser' => 'claire',
    ],
];


$eventArray = [];
foreach ($events as $e) {
    $event = new \Evolver\Event(
        $e['type'],
        $e['payload'],
        $e['meta']
    );
    $eventArray[] = $event;
}
$events = $eventArray;
