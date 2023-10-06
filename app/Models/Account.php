<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AccountException;
use Illuminate\Support\Facades\Cache;

class Account extends Model
{
    private $id;
    private $accountAmount;

    public function makeDeposit(array $params): void
    {
        $this->id = $params['destination'];
        $this->accountAmount = $params['amount'] + $this->getCurrentAmount($this->id);

        $this->updateAmount();
    }

    public function makeWithdraw(array $params): void
    {
        $this->id = $params['origin'];
        $originData = $this->getCurrentAmount($this->id);

        if (is_null($originData)) {
            throw new AccountException();
        }

        $this->accountAmount = $originData - $params['amount'];
        $this->updateAmount();
    }

    public function makeTransfer(array $params): void
    {
        $originData = $this->getCurrentAmount($params['origin']);

        if (is_null($originData)) {
            throw new AccountException();
        }

        $this->makeDeposit($params);
        $this->makeWithDraw($params);
    }

    public function getAccountInfo(): array
    {
        return [
            'id' => $this->id,
            'balance' => $this->accountAmount
        ];
    }

    public function getCurrentAmount($id): int|null
    {
        return Cache::get("account_$id", null);
    }

    private function updateAmount(): void
    {
        Cache::put("account_$this->id",  $this->accountAmount);
    }
}
