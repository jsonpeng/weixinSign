<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Log;
use Config;
//微信
use EasyWeChat\Factory;

class MainController extends Controller
{

    public function __construct()
    {
     
    }

    //微信支付通知
    public function payWechatNotify(Request $request){
        $payment = Factory::payment(Config::get('wechat.payment.default'));
        $response = $payment->handlePaidNotify(function($message, $fail){
            // Log::info($message);
            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    app('zcjy')->OrderRepo()->dealSuccessOrder($message['out_trade_no']);
                // 用户支付失败
                } 
                elseif (array_get($message, 'result_code') === 'FAIL') {
                    
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });

        return $response;
    }

    /**
     * 异步通知 -支付宝
     */
    public function alipayWebNotify(Request $request)
    {
        $inputs = $request->all();
    
        if(isset($inputs['trade_status'])){
            if ($inputs['trade_status'] == 'TRADE_SUCCESS') 
            {
                 app('zcjy')->OrderRepo()->dealSuccessOrder($inputs['out_trade_no']);
            
            }
        }
        return 'success';
    }

    /**
     * 同步通知 -支付宝
     */
    public function alipayWebReturn(Request $request)
    {
        //支付成功跳转
        return redirect(env('CLIENT', '/'));
    }   

    //文案分类
    public function postCat($cat_name=null)
    {
         $first_cat = app('zcjy')->CatRepo()->getFirstCat();

         if(empty($first_cat)){
            return redirect('/');
         }

         if(empty($cat_name))
         {
            $cat_name = $first_cat->name;
         }

         $cats = app('zcjy')->CatRepo()->getCacheAllCats();

         $posts = app('zcjy')->CatRepo()->getCacheCatPosts($cat_name);
         // dd(auth('web')->user());
         return view('front.environment',compact('cats','cat_name','posts'));
    }

    //专家资料
    public function experts(Request $request)
    {

        $experts = app('zcjy')->ExpertRepo()->all();
        // $cats = app('zcjy')->CatRepo()->getCacheAllCats();
        return view('front.experts',compact('experts'));
    }

    //专家资料详情
    public function expertDetail(Request $request,$id)
    {
        $expert = app('zcjy')->ExpertRepo()->findWithoutFail($id);
        if(empty($expert))
        {
            return redirect('/');
        }
        return view('front.expert_detail',compact('expert'));
    }

    //文案分类
    public function postDetail($id)
    {
         $post = app('zcjy')->PostRepo()->getCachePost($id); 
         if(empty($post))
         {
            return redirect('/');
         }
         return view('front.environment_detail',compact('post'));
    }


    //首页教学活动
    public function index()
    {
        return view('front.index');
    }

    //开设课程分类
    public function courseCats()
    {
        $cats = app('zcjy')->CourseCatRepo()->getCacheTypeCats('课程班',true);
        return view('front.course.cat',compact('cats'));
    }

    //对应对应分类下的课程
    public function courseCatShow($id)
    {
        $cats = app('zcjy')->CourseCatRepo()->getCacheChildCats($id);
        return view('front.course.cat_list',compact('cats'));
    }

    //对应分类下的详细课程列表
    public function courses($id)
    {
        $courses = app('zcjy')->CourseRepo()->getCachecatCourses($id);
        return view('front.course.course_list',compact('courses'));
    }


    //课程班详情
    public function courseDetail(Request $request,$id)
    {

        $course = app('zcjy')->CourseRepo()->getCacheCourseDetail($id);

        if(empty($course))
        {
            return redirect('/');
        }

        $user = auth('web')->user();

        $attention_status = false;

        if(!empty($user)){
            $attention_status = app('zcjy')->CourseRepo()->attionCourseStatus($user->id,$id);
        }

        $course_attachs = $course->attachs;

        $input = $request->all();
   
        return view('front.course.course_detail',compact('course','user','attention_status','course_attachs','input'));

    }

    //确认报名
    public function enterSign()
    {
        $all_attr = app('zcjy')->CourseRepo()->userAddedCourses(auth('web')->user());

        $courses = $all_attr['courses'];

        if(count($courses) == 0)
        {
            return redirect('/');
        }

        $price = $all_attr['price'];
        // dd($all_attr);
        return view('front.course.enter_sign',compact('courses','price'));
    }

    //报名须知
    public function signGuide()
    {
        return view('front.course.sign_guide');
    }

    //选择付款方式
    public function choosePay(Request $request)
    {
        $user = auth('web')->user();
        $paynow = $request->get('paynow');
        // app('zcjy')->OrderRepo()->deleteUnPayOrder($user->id);
        if(empty($paynow)){
            $all_attr = app('zcjy')->CourseRepo()->userAddedCourses($user);

            $courses = $all_attr['courses'];

            if(count($courses) == 0)
            {
                return redirect('/');
            }

            $price = $all_attr['price'];
        }
        else{
            $order = app('zcjy')->OrderRepo()->userNoPayOrder($user);
            if(empty($order))
            {
                return redirect('/');
            }

            if($order->pay_status == '已支付')
            {
                 return redirect('/');
            }
            $price = $order->price;
        }

        return view('front.course.choose_pay',compact('price','paynow'));
    }

    //兴趣小组
    public function likeGroups($type='兴趣小组')
    {

        $groups = app('zcjy')->CourseCatRepo()->getCacheTypeCats($type);

        $groups =  app('zcjy')->CourseRepo()->getCachecatCourses($groups[0]['id']);

        return view('front.like_groups',compact('groups'));
    }
    
    //兴趣小组详情
    public function likeGroupDetail($id)
    {
        $course = app('zcjy')->CourseRepo()->getCacheCourseDetail($id);

        if(empty($course)){
            return redirect('/');
        }

        $user = auth('web')->user();

        $attention_status = false;

        if(!empty($user)){
            $attention_status = app('zcjy')->CourseRepo()->attionCourseStatus($user->id,$id);
        }
        $insideUserVarify = app('zcjy')->insideUserVarify($user);
        return view('front.like_group_detail',compact('course','user','attention_status','insideUserVarify'));
    }

}
