<?php

namespace Evolver;

class Event
{
    protected $type;
    protected $payload;
    protected $meta;

    public function __construct(string $type, array $payload, array $meta)
    {
        $this->type = $type;
        $this->payload = $payload;
        $this->meta = $meta;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getMeta()
    {
        return $this->meta;
    }
}
