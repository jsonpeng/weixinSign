<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Cache-control" content="no-cache,no-store,must-revalidate">
    
    <!-- WEUI -->
    <link rel="stylesheet" href="{{ asset('vendor/weui.min.css') }}">

    <!-- 自定义样式 -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <style type="text/css">

    </style>
        @yield('css')
    <!-- jquery -->
    <script src="{{asset('vendor/jquery-1.12.4.min.js')}}"></script>
    </head>
    <body>
        
        <div class="app-wrapper">
            @yield('content')
        </div>


        
    </body>

    <script src="{{ asset('vendor/layer/layer.js') }}"></script>
    <script src="{{ asset('js/zcjy.js') }}"></script>
    <script type="text/javascript">
            function varifyUser()
            {
                var user = '{!! auth('web')->user() !!}';
                if($.empty(user))
                {
                    $.alert('请先登录后使用','error');
                    setTimeout(function(){
                         location.href="/user/index";
                    },1000);
                    return true;
                }
                return false;
            }
    </script>
   

    @yield('js')
    <!-- 自定义代码 -->
    <script src="{{ asset('js/main.js') }}"></script>

</html>
