<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\User;

use EasyWeChat\Factory;

class ZcjyApiUserMiddleware
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
        $varify = app('zcjy')->zcjyApiUserVarify($request->all());
        if($varify){
            return zcjy_callback_data($varify,401);
        }
        return $next($request);
    }

   
    
}
