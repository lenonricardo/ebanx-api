<?php

namespace App\Services\Event\Handlers;

use App\Services\Event\EventHandler;
use App\Services\Event\EventHandlerInterface;

class TransferHandler extends EventHandler implements EventHandlerInterface
{
    public const TYPE = 'transfer';

    public function handleEvent(array $params): array
    {
        $this->account->makeTransfer($params);

        return [
            "origin" => [
                "id" => $params['origin'],
                "balance" => intVal($this->account->getCurrentAmount($params['origin']))
            ],
            "destination" => [
                "id" => $params['destination'],
                "balance" => intVal($this->account->getCurrentAmount($params['destination']))
            ]
        ];
    }
}