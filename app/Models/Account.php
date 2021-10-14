<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AccountException;

class Account extends Model
{
    private $id;
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

    public function makeDeposit()
    {
        $this->id = $this->request->input('destination');
        $this->accountAmount = $this->request->input('amount');

        $amount = $this->request->session()->get("account_$this->id");

        if ($amount) {
            $this->accountAmount += $amount;
        }

        $this->updateData();
    }

    public function makeWithdraw()
    {
        $this->id = $this->request->input('origin');

        $amount = $this->request->session()->get("account_$this->id");

        if (!$amount) {
            throw new AccountException();
        }

        $this->accountAmount = $amount - $this->request->input('amount');
        $this->updateData();
    }

    public function updateData()
    {
        $this->request->session()->put("account_$this->id", $this->accountAmount);
    }
}
