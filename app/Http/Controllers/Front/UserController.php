<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
     
    }

    //微信跳转回调
    public function weixinAuthCallback(Request $request)
    {
        return app('zcjy')->weixinAuthCallback($request->get('target_url'));
    }

    //注册
    public function reg(){
        return view('front.auth.reg');
    }



    //登录
    public function login(){
        return view('front.auth.login');
    }

    //个人中心
    public function index(){
        $user = auth('web')->user();
        return view('front.usercenter.index',compact('user'));
    }

    //我的订单
    public function orders(Request $request){
        #所有需要的数据
        $all_attr = app('zcjy')->OrderRepo()->userOrders(auth('web')->user());
        #已报名成功的订单
        $pay_orders = $all_attr['pay'];
        #没有支付的订单
        $nopay_orders = $all_attr['nopay'];
        #没有支付的订单的总价
        $nopay_all_price = $all_attr['nopay_all_price'];
        #是否直接进入支付
        $check = $request->get('check');
        return view('front.usercenter.orders',compact('pay_orders','nopay_orders','nopay_all_price','check'));
    }

    //课程表
    public function courseBiao(Request $request){
        $input = $request->all();
        $courses_biao = app('zcjy')->OrderRepo()->userCourseBiao(auth('web')->user());
        // dd($courses_biao);
        return view('front.usercenter.course_biao',compact('courses_biao','input'));
    }

    //我的收藏
    public function collect(){
        $user = auth('web')->user();
        $courses = app('zcjy')->CourseRepo()->userAttionedCourses(auth('web')->user()->id);
        // dd($courses);
        return view('front.usercenter.collect',compact('user','courses'));
    }

    //修改手机号
    public function editMobile(){
        return view('front.usercenter.edit_mobile');
    }

    



}
