<?php

namespace Evolver\EventStore;

use Evolver\Event;

class ArrayEventStore implements EventStoreInterface
{
    protected $events;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function addEvent(Event $event)
    {
        $this->events[] = $event;
    }
}
