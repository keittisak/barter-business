<?php

namespace App\Http\Middleware;

use Closure;
use DB;

class AccessLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        DB::table('access_log')->insert([
            'url' => $request->url(),
            'query_string' => json_encode($request->all()),
            'header' => ($request->header() ? json_encode($request->header()) : null),
            'method' => $request->method(),
            'user_id' => ($request->user() ? $request->user()->id : null),
            'is_ajax' => ($request->ajax() ? 1 : 0),
            'ip' =>  $request->ip(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        return $next($request);
        
    }
}
