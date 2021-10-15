<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AccountException;
use Illuminate\Support\Facades\Cache;

class Account extends Model
{
    private $id;
    private $origin;
    private $accountAmount = 0;
    private $request;

    public function __construct()
    {
        $this->request = request();
    }

    public function getAmount()
    {
        return $this->accountAmount;
    }

    public function getId()
    {
        return $this->id;
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

    public function makeDeposit()
    {
        $this->id = $this->request->input('destination');
        $this->accountAmount = $this->request->input('amount');

        $amount = $this->getAccountData($this->id);

        if ($amount) {
            $this->accountAmount += $amount;
        }

        $this->updateData();
    }

    public function makeWithdraw()
    {
        $this->id = $this->request->input('origin');

        $amount = $this->getAccountData($this->id);

        if (!$amount) {
            throw new AccountException();
        }

        $this->accountAmount = $amount - $this->request->input('amount');
        $this->updateData();
    }

    public function makeTransfer()
    {
        $origin = $this->getAccountData($this->request->input('origin'));

        if (!$origin) {
            throw new AccountException();
        }

        $this->makeDeposit();
        $this->makeWithDraw();
    }

    private function updateData()
    {
        Cache::store('redis')->put("account_$this->id",  $this->accountAmount);
    }

    private function getAccountData($id)
    {
        return Cache::get("account_$id");
    }
}
