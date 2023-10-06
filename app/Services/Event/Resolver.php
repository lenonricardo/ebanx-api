<?php

namespace App\Services\Event;

use App\Services\Event\Handlers\DefaultEventHandler;
use App\Services\Event\Handlers\DepositHandler;
use App\Services\Event\Handlers\TransferHandler;
use App\Services\Event\Handlers\WithdrawHandler;

class Resolver
{
    public static function resolve(): EventHandlerInterface
    {
      return new DepositHandler(
          new WithdrawHandler(
              new TransferHandler(
                  new DefaultEventHandler()
          )
        )
      );
    }

}