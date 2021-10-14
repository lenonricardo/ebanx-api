<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    private $id;
    private $accountAmount = 0;

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
            'amount' => $this->accountAmount
        ];
    }

    public function makeDeposit($request)
    {
        $this->id = $request->input('destination');
        $this->accountAmount = $request->input('amount');

        $amount = $request->session()->get("account_$this->id");

        if (!$amount) {
            $request->session()->put("account_$this->id", $this->accountAmount);
        } else {
            $this->accountAmount += $amount;
            $request->session()->put("account_$this->id", $this->accountAmount);
        }
    }
}
