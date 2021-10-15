<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AccountException;
use Illuminate\Support\Facades\Cache;

class Account extends Model
{
    private $id;
    private $accountAmount;
    private $request;

    public function __construct()
    {
        $this->request = request();
        $this->accountAmount = 0;
    }

    public function makeDeposit()
    {
        $this->id = $this->request->input('destination');
        $this->accountAmount += $this->request->input('amount') + $this->getAccountData($this->id);

        $this->updateData();
    }

    public function makeWithdraw()
    {
        $this->id = $this->request->input('origin');
        $originData = $this->getAccountData($this->id);

        if (is_null($originData)) {
            throw new AccountException();
        }

        $this->accountAmount = $originData - $this->request->input('amount');
        $this->updateData();
    }

    public function makeTransfer()
    {
        $originData = $this->getAccountData($this->request->input('origin'));

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
                "id" => $this->request->input('origin'),
                "balance" => intVal($this->getAccountData($this->request->input('origin')))
            ],
            "destination" => [
                "id" => $this->request->input('destination'),
                "balance" => intVal($this->getAccountData($this->request->input('destination')))
            ]
        ];
    }

    public function getAccountData($id)
    {
        return Cache::get("account_$id", null);
    }

    private function updateData()
    {
        Cache::store('redis')->put("account_$this->id",  $this->accountAmount);
    }
}
