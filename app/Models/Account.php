<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    private $id;
    private $accountAmount;

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

        $account = $request->session()->get($this->id);

        if (!$account) {
            $request->session()->push($this->id, $this->accountAmount);
        } else {
            $amount = $account[$this->id] + $this->accountAmount;
            $accounts->put($this->id, $amount);
        }

    }
}
