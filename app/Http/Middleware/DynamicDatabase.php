<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DynamicDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $databaseName = 'database_' . $userId;
            $tablePrefix = 'e' . $userId . '_';
            config(['database.connections.dynamic.database' => $databaseName]);
            config(['database.connections.dynamic.prefix' => $tablePrefix]);
            DB::setDefaultConnection('dynamic');
        } else {
            DB::setDefaultConnection('main');
        }
        return $next($request);
    }
}
