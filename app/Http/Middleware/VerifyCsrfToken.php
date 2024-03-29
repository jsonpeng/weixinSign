<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    // ...
	    'wechat',
        '/weixin',
        '/weixin_auth_callback',
        '/weixin/auth_callback',
        //'/getRootSlug/*',
        '/notify_wechcat_pay',
        '/ajax/uploads',
        '/notify',
        'swagger/*',
        'alipay_notify',
        'weixin_notify_pay'
	];
}
