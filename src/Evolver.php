<?php

namespace Evolver;

use Evolver\Event;
use Evolver\EventStore\EventStoreInterface;
use Evolver\StateStore\StateStoreInterface;
use Evolver\ProjectorInterface;

class Evolver
{
    protected $eventStore;
    protected $stateStore;

    protected $projectors = [];

    public function __construct(EventStoreInterface $eventStore, StateStoreInterface $stateStore)
    {
        $this->eventStore = $eventStore;
        $this->stateStore = $stateStore;
    }

    public function getEventStore(): EventStoreInterface
    {
        return $this->eventStore;
    }

    public function getStateStore(): StateStoreInterface
    {
        return $this->stateStore;
    }

    public function setState(string $key, array $state): void
    {
        $this->stateStore->setState($key, $state);
    }

    public function getState(string $key): array
    {
        return $this->stateStore->getState($key);
    }

    public function addProjector(ProjectorInterface $projector)
    {
        $config = $projector::getConfig();
        $this->projectors[$config['name']] = $projector;
    }

    public function project(): void
    {

        $state = $this->stateStore->getState('evolver');
        if (!isset($state['playhead'])) {
            $state['playhead'] = 0;
        }

        $maxEventId = $this->eventStore->getMaxEventId();

        $i = $state['playhead'];
        while ($i<$maxEventId) {
            $i++;
            $event = $this->eventStore->getEvent($i);
            $this->projectEvent($event);

            $state['playhead'] = $i;
            $this->stateStore->setState('evolver', $state);
        }
    }

    public function projectEvent(Event $event)
    {
        foreach ($this->projectors as $projector) {
            $projector->project($event);
        }
    }

    public function addEvent(Event $event)
    {
        $this->eventStore->addEvent($event);
        $this->project();
    }
}
