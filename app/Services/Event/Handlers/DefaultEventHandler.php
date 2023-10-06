<?php

namespace App\Services\Event\Handlers;

use App\Services\Event\EventHandlerInterface;

class DefaultEventHandler implements EventHandlerInterface
{
    public function event(array $params): array
    {
        return [];
    }

    public function handleEvent(array $params): array
    {
        return [];
    }
}