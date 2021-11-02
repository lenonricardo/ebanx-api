<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AccountException;
use Illuminate\Support\Facades\Cache;

class Account extends Model
{
    private $id;
    private $accountAmount;

    public function makeDeposit()
    {
        $this->id = request()->input('destination');
        $this->accountAmount = request()->input('amount') + $this->getAccountData($this->id);

        $this->updateData();
    }

    public function makeWithdraw()
    {
        $this->id = request()->input('origin');
        $originData = $this->getAccountData($this->id);

        if (is_null($originData)) {
            throw new AccountException();
        }

        $this->accountAmount = $originData - request()->input('amount');
        $this->updateData();
    }

    public function makeTransfer()
    {
        $originData = $this->getAccountData(request()->input('origin'));

        if (is_null($originData)) {
            throw new AccountException();
        }

        $this->makeDeposit();
        $this->makeWithDraw();
    }

    public function getAccountInfo()
    {
        return [
            'id' => $this->id,
            'balance' => $this->accountAmount
        ];
    }

    public function getTransferInfo()
    {
        return [
            "origin" => [
                "id" => request()->input('origin'),
                "balance" => intVal($this->getAccountData(request()->input('origin')))
            ],
            "destination" => [
                "id" => request()->input('destination'),
                "balance" => intVal($this->getAccountData(request()->input('destination')))
            ]
        ];
    }

    public function getAccountData($id)
    {
        return Cache::get("account_$id", null);
    }

    private function updateData()
    {
        Cache::put("account_$this->id",  $this->accountAmount);
    }
}
