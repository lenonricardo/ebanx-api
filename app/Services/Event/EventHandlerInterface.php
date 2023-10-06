<?php

namespace App\Services\Event;

interface EventHandlerInterface
{
    public function event(array $params): array;

    public function handleEvent(array $params): array;
}
