<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Config;
use Log;

use EasyWeChat\Factory;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Overtrue\EasySms\EasySms;
use Illuminate\Support\Facades\Cookie;
use Hash;
//支付宝
use AlipayTradeService;
use AlipayTradeWapPayContentBuilder;

class CommonController extends Controller
{
    //用户登录
    public function testLoginUser(Request $request){
           $input = $request->all();
           $varify = app('zcjy')->varifyInputParam($input,['id']);
           if($varify){
                return zcjy_callback_data($varify,1);
           }
           #更新用户信息
           $user = $this->updateUserInfo($input);
           if(!empty($user)){
           #给予token
           $token = zcjy_base64_en($user->id.'__'.strtotime($user->created_at).'__'.$user->openid.'__'.time());
           $key = app('zcjy')->generateApiKey($user);
           return zcjy_callback_data(['token' => $token,'key'=>$key]);
           }
           else{
            return zcjy_callback_data('该用户不存在',1);
           }
    }

    public function testToken(Request $request){
        $input = $request->all();
        if(isset($input['token'])){
            return zcjy_base64_de($input['token']);
        }
        else{
            return '参数不对';
        }
    }

    //通过token换取密钥
    public function getKeyByToken(Request $request){
        $input = $request->all();
        $user = zcjy_api_user($input);
        if(empty($user)){
            return zcjy_callback_data('获取密钥失败,用户信息不存在',1);
        }
        $key = app('zcjy')->generateApiKey($user);
        return zcjy_callback_data($key);
    }


    private function updateUserInfo($input)
    {
        $user = User::find($input['id']);
        return $user;
    }

    //获取用户信息
    public function meInfo(Request $request){
        $user = zcjy_api_user($request->all());
        #查看记录
        $seelogs = $user->seelogs()->orderBy('created_at','desc')->get();
        foreach ($seelogs as $key => $val) {
          $val['section'] = $val->section()->first();
          if(!empty($val['section'])){
            $val['subject'] = $val['section']->subject()->first();
          }
          else{
            $val['subject'] = null;
          }
          if(!empty($val['subject'])){
            $val['job'] = $val['subject']->job()->first();
          }
          else{
            $val['job'] = null;
          }
        }
        $packages = $user->packages()->orderBy('created_at','desc')->get();
        return zcjy_callback_data(['user'=>$user,'packages'=>$packages,'seelogs'=>$seelogs]);
    }

    //注册为会员
    public function regMember(Request $request){

        $input = $request->all();

        $varify = app('zcjy')->varifyInputParam($input,['mobile','job','code']);

        if($varify){
          return zcjy_callback_data($varify,1);
        }

        $user = zcjy_api_user($input);
   
        // if(session()->get('zcjycode'.$user->id) != $input['code']){
        //     return zcjy_callback_data('验证码输入有误',1);
        // }
        
        if($input['code'] != $user->show_time){
             return zcjy_callback_data('验证码输入有误',1);
        }

        if(array_key_exists('job',$input) && empty($input['job'])){
             return zcjy_callback_data('请选择职位',1);
        }

        $user->update($input);
        return zcjy_callback_data('注册成功');
    }


  

    //发起套餐购买 
    public function buyPackage(Request $request){
        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,['package_id','pay_type']);
        if($varify){
          return zcjy_callback_data($varify,1);
        }
        $package = app('zcjy')->JobPackageRepo()->findWithoutFail($input['package_id']);
        if(empty($package)){
          return zcjy_callback_data('没有找到该套餐记录',1);
        }

        $user = zcjy_api_user($input);

        if($input['pay_type'] == '微信'){

                    $order = $this->generateOrder($user,$package,$input);

                    $body = '支付订单'.$order->number.'费用';

                    $attributes = [
                        'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
                        'body'             => $body,
                        'detail'           => '订单编号:'.$order->number,
                        'out_trade_no'     => $order->number,
                        'total_fee'        => intval( $order->price * 100 ), // 单位：分
                        'notify_url'       => $request->root().'/api/weixin_notify_pay', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                        'openid'           => $user->openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
                        'attach'           => '支付订单',
                    ];

                    $payment = Factory::payment(Config::get('wechat.payment.default'));
                    $result = $payment->order->unify($attributes);

                    //Log::info($result);
                    if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
                        $prepayId = $result['prepay_id'];
                        $json = $payment->jssdk->bridgeConfig($prepayId);
                        return zcjy_callback_data($json);
                    }
                    else{
                       return zcjy_callback_data('支付失败',1);
                    }

        }
        elseif($input['pay_type'] == '支付宝'){
                        $order = $this->generateOrder($user,$package,$input);

                        $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
                        $payRequestBuilder->setBody('会员购买');
                        $payRequestBuilder->setSubject('订单编号:'.$order->number);
                        $payRequestBuilder->setOutTradeNo($order->number);
                        $payRequestBuilder->setTotalAmount($order->price);
                        $payRequestBuilder->setTimeExpress("1m");

                        $config = Config::get('alipay');
                        $payResponse = new AlipayTradeService($config);
                        $redirecturl = $payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

                        //Log::info($redirecturl);

                        return zcjy_callback_data($redirecturl); 
        }
        else{
            return zcjy_callback_data('支付参数错误',1);
        }
    }


    #生成订单
    private function generateOrder($user,$package,$input){
        #先把之前未支付的干掉
        app('zcjy')->UserBuyLogRepo()->model()::where('user_id',$user->id)->where('pay_status','未支付')->delete();

        $input['price'] = $package->price;
        $input['job_id'] = $package->job_id;
        $input['package_name'] = $package->job_name.'['.$package->month.'个月]';
        $input['package_month'] = $package->month;
        $input['user_id'] = $user->id;
        $input['pay_platform'] = $input['pay_type'];

        $order =  app('zcjy')->UserBuyLogRepo()->create($input);
        $order->update(['number'=>time().'_'.$order->id]);
        return $order;
    }






    /**
     * 同步通知 -支付宝
     */
    public function alipayWebReturn(Request $request)
    {
        //支付成功跳转
        return redirect(env('CLIENT', '/'));
    }

    //发起套餐支付
    public function payPackage(Request $request,$id){
        $log = app('zcjy')->UserBuyLogRepo()->findWithoutFail($id);
        if(empty($log)){
            return zcjy_callback_data('没有找到该套餐记录',1);
        }
        if($log->pay_status=='已支付'){
            return zcjy_callback_data('该套餐已支付成功!请不要重复支付!',1);
        }
        $user = zcjy_api_user($request->all());
        $log->update(['pay_status'=>'已支付']);
        app('zcjy')->generateUserPackage($user,$log);
        return zcjy_callback_data('支付成功');
    }

    //获取热门发现
    public function posts(Request $request){
        $skip = 0;
        $take = 12;
        $input = $request->all();
        if(isset($input['skip'])){
          $skip = $input['skip'];
        }
        if(isset($input['take'])){
          $take = $input['take'];
        }
        return zcjy_callback_data(app('zcjy')->PostRepo()->getCachePosts($skip,$take));
    }
    
    // 文章详情
    public function postDetail(Request $request,$post_id){
        return zcjy_callback_data(app('zcjy')->PostRepo()->getCachePost($post_id));
    }


    //发送短信验证码
    public function sendCode(Request $request)
    {
        $inputs = $request->all();
        $mobile = null;
        if (array_key_exists('mobile', $inputs) && $inputs['mobile'] != '') {
            $mobile = $inputs['mobile'];
        }else{
            return zcjy_callback_data('请输入手机号',1);
        }
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'aliyun',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'aliyun' => [
                    'access_key_id' => Config::get('zcjy.ACCESS_KEY_ID'),
                    'access_key_secret' => Config::get('zcjy.ACCESS_KEY_SECRET'),
                    'sign_name' =>'e融通',
                ]
            ],
        ];
        $easySms = new EasySms($config);
        $num = rand(1000, 9999); 
        $easySms->send($mobile, [
            'content'  => '您的验证码为: '.$num,
            'template' => Config::get('zcjy.SMS_TEMPLATE'),
            'data' => [
                'code' => $num
            ],
        ]);
        //当前微信用户
        $user = zcjy_api_user($inputs);

        $user->update(['show_time'=>$num]);
        //session()->put('zcjycode'.$user->id,$num);

        return zcjy_callback_data('发送验证码成功');
    }

  
}
