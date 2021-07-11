<?php

namespace App\Http\Middleware;

use App\Exceptions\BobException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \App\Exceptions\BobException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new BobException(
            '登陆过期，请重新登陆~',
            -1,
            401
        );
    }
}
