<?php

namespace Evolver;

interface ProjectorInterface
{
    public static function getConfig();

    /**
     * Update state based on event
     */
    public function project(Event $event);
}
