<?php

namespace App\Services\Event\Handlers;

use App\Services\Event\EventHandler;
use App\Services\Event\EventHandlerInterface;

class WithdrawHandler extends EventHandler implements EventHandlerInterface
{
    public const TYPE = 'withdraw';

    public function handleEvent(array $params): array
    {
        $this->account->makeWithdraw($params);

        return [
            'origin' => $this->account->getAccountInfo()
        ];
    }
}