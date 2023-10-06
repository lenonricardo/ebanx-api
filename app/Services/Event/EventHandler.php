<?php

namespace App\Services\Event;

use App\Models\Account;

abstract class EventHandler
{
    protected Account $account;

    public function __construct(private EventHandlerInterface $nextHandler)
    {
        $this->account = new Account();
    }

    public function event(array $params): array
    {
        if (static::TYPE === $params['type']) {
            return $this->handleEvent($params);
        }

        return $this->nextHandler->event($params);
    }

}