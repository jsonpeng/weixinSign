<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\User;

use EasyWeChat\Factory;

class AuthMiddleware
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
         if (!Auth::guard('web')->check()) 
         {
                $user = null;
                #如果是本地调试
                if (Config::get('web.app_env') == 'local') {
                    #发起本地用户登录
                    $user = app('zcjy')->localWeixinUser();
                }
                else{
                    #获取当前微信用户
                    //$user = app('zcjy')->getCacheWeixinUser($request->ip());
                    $user = null;
                    #如果不在
                    if(empty($user))
                    {
                        #发起微信授权登录
                        return app('zcjy')->weixinAuthRedirect($request->fullUrl());
                    }
                }
             
         }
        return $next($request);
    }
    
}
