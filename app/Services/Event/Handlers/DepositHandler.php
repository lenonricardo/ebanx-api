<?php

namespace App\Services\Event\Handlers;

use App\Services\Event\EventHandler;
use App\Services\Event\EventHandlerInterface;

class DepositHandler extends EventHandler implements EventHandlerInterface
{
    public const TYPE = 'deposit';

    public function handleEvent(array $params): array
    {
        $this->account->makeDeposit($params);

        return [
            'destination' => $this->account->getAccountInfo()
        ];
    }
}