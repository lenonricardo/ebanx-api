<?php

namespace App\Exceptions;

use Exception;

class AccountException extends Exception
{
    public function render()
    {
        return response()->json([
            'erro' => 'Account not found'
        ], 404);
    }
}