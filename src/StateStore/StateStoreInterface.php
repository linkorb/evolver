<?php

namespace Evolver\StateStore;

interface StateStoreInterface
{
    public function getState(string $key): array;
    public function setState(string $key, array $state): void;
    public function reset(): void;
}
