<?php

namespace Evolver\EventStore;

use Evolver\Event;

interface EventStoreInterface
{
    public function addEvent(Event $event);
}
