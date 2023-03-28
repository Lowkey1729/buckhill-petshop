<?php

namespace App\Http\Middleware;

use App\Services\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(
            ($request->user()?->is_admin != UserType::admin()->value),
            401
        );

        return $next($request);
    }
}
