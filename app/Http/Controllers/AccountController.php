<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;


class AccountController extends Controller
{
    private $account;

    public function __construct()
    {
        $this->account = new Account();
    }

    public function balance(Request $request)
    {
        return response()->json([
            'test' => $request->session()->get('teste')
        ]);
    }

    public function event(Request $request)
    {
        $request->session()->put('teste', $request->input('teste'));
        $this->account->setAmount($request->input('teste'));

        return response()->json([
            'test1' => $this->account->getAmount()
        ]);
    }
}
