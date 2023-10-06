<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Exceptions\AccountException;
use App\Services\Event\Resolver as EventResolver;
use Illuminate\Support\Facades\Cache;


class AccountController extends Controller
{
    public const RESET_RESPONSE_OK = 'OK';

    public function reset() {
        Cache::flush();

        return self::RESET_RESPONSE_OK;
    }

    public function balance(Request $request)
    {
        $account = new Account();
        $amount = $account->getCurrentAmount($request->input('account_id'));

        if (is_null($amount)) {
            throw new AccountException();
        }

        return intVal($amount);
    }

    public function event(Request $request)
    {
        $response = EventResolver::resolve()->event($request->all());

        return response()->json($response, 201);
    }
}
