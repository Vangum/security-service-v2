<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if ($request->header('X-Inertia')) {
            return route('login');
        }

        if (! $request->expectsJson()) {
            return route('login');
        }

        return null;
    }
}
