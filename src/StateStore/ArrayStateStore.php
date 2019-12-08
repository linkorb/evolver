<?php

namespace Evolver\StateStore;

class ArrayStateStore implements StateStoreInterface
{
    protected $states;

    public function __construct(array $states)
    {
        $this->states = $states;
    }

    public function getState(string $key): array
    {
        if (!isset($this->states[$key])) {
            return [];
        }
        return $this->states[$key];
    }

    public function setState(string $key, array $state): void
    {
        $this->states[$key] = $state;
    }

    public function reset(): void
    {
        $this->states = [];
    }
}
