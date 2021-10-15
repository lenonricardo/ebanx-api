<?php

namespace App\Exceptions;

use Exception;

class AccountException extends Exception
{
    public function render()
    {
        return response()->json(0, 404);
    }
}