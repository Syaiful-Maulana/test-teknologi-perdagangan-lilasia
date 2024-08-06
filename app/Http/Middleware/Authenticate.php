<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthorized user.
     */
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json(['code' => 401, 'message' => 'Unauthenticated', 'data' => null], 401));
    }
}
