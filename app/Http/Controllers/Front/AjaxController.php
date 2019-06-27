<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Overtrue\EasySms\EasySms;

use Log;
use Config;
//支付宝
use \AlipayTradeService;
use \AlipayTradeWapPayContentBuilder;
//微信
use EasyWeChat\Factory;

class AjaxController extends Controller
{

    /**
     * [发送手机验证码]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
	public function sendMobileCode(Request $request)
	{

		$inputs = $request->all();

        $mobile = null;

        if (array_key_exists('mobile', $inputs) && $inputs['mobile'] != '') {
            $mobile = $inputs['mobile'];
        }
        else{
            return zcjy_callback_data('请输入手机号',1);
        }

        // return zcjy_callback_data('发送短信失败,当前系统正在维护中',1);

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
                    'sign_name' =>Config::get('SIGN_NAME') ? Config::get('SIGN_NAME') : '长江老年大学',
                ]
            ],
        ];

        // dd($config);

        $easySms = new EasySms($config);

        $num = rand(1000, 9999); 

        try {
            $easySms->send($mobile, [
                'content'  => '您的验证码为: '.$num,
                'template' => Config::get('zcjy.SMS_TEMPLATE'),
                'data' => [
                    'code' => $num
                ],
            ]);
        } catch (Exception $e) {
            return zcjy_callback_data('发送短信失败,请检查手机信号及运营商状况',1);
        }
 

        session()->put('zcjycode'.$mobile,$num);

        return zcjy_callback_data('发送验证码成功');
	}
    
    //修改手机号    
    public function updateMobile(Request $request)
    {
        $input = $request->all();

        $varify = app('zcjy')->varifyInputParam($input,['mobile','code']);

        if($varify)
        {
            return zcjy_callback_data($varify,1);
        }

        #验证码验证不对
        if(session('zcjycode'.$input['mobile']) != $input['code'])
        {
                return zcjy_callback_data('验证码错误',1);
        }

        $user = auth('web')->user();

        $user->update(['mobile'=>$input['mobile']]);

        return zcjy_callback_data('修改手机号成功');
    }
	//完善用户注册信息
	public function prefectRegUser(Request $request)
	{
		$input = $request->all();

        $varify = app('zcjy')->varifyInputParam($input,['name','idcard_num','mobile','code']);

        if($varify){
          	return zcjy_callback_data($varify,1);
        }

        #验证身份证号码
        if(strlen($input['idcard_num']) != 18)
        {
            return zcjy_callback_data('身份证号码格式输入错误',1);
        }

        #验证手机号
        if(strlen($input['mobile']) != 11)
        {
            return zcjy_callback_data('手机号格式输入错误',1);
        }

        #验证验证码
        if(session()->get('zcjycode'.$input['mobile']) != $input['code']){
            return zcjy_callback_data('验证码输入有误',1);
        }

        $user = auth('web')->user();

        #生日
        $input['birthday'] = getIDCardInfo($input['idcard_num']);

        #从导入用户中查下
      	$import_user = User::where('name',$input['name'])
        ->where('birthday',$input['birthday'])
        ->first();

      	// if(!empty($import_user) && $import_user->import_status == '已导入')
       //  {
       //        return zcjy_callback_data('该用户信息已被导入过',1);
       //  }

        if(!empty($import_user))
        {
            $input['type'] = '单位内部用户';
            $import_user->update(['import_status'=>'已导入']);
        }

        $input['import_status'] = '已导入';

        $user->update($input);

        return zcjy_callback_data('注册成功');
	}

    //发起课程收藏操作
    public function  actionAttentionCourse(Request $request,$id)
    {
        return app('zcjy')->CourseRepo()->actionAttionCourse(optional(auth('web')->user())->id,$id);   
    }

    //添加课程
    #普通课程添加
    #兴趣小组/活动报名
    public function addCourseAction(Request $request,$course_id)
    {
        return app('zcjy')->CourseRepo()->actionAddCourse(auth('web')->user(),$course_id);
    }

    //删除课程
    public function delCourseAction(Request $request,$course_join_id)
    {
        return app('zcjy')->CourseRepo()->actionDelCourse($course_join_id);
    }

    //寻找退休单位
    public function findRetUnit(Request $request)
    {
        $input = $request->all();

        $varify = app('zcjy')->varifyInputParam($input,['name','idcard_num']);

        if($varify){
            return zcjy_callback_data($varify,3);
        }

        $unit = null;

        #生日
        $birthday = getIDCardInfo($input['idcard_num']);

        $import_user = User::where('import_type','导入用户')
        ->where('name',$input['name'])
        ->where('birthday',$birthday)
        ->first();

        if(!empty($import_user))
        {
            $unit = $import_user->ret_unit;
        }

        return zcjy_callback_data($unit);
    }

    /**
     * 获取支付参数
     * @param  [type] $input [description]
     * @param  [type] $user  [description]
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    private function getPayParam($request,$user,$order)
    {
         $input = $request->all();

         if($input['pay_platform'] == '微信')
         {
     
                        $body = '支付订单'.$order->number.'费用';

                        $attributes = [
                            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
                            'body'             => $body,
                            'detail'           => '订单编号:'.$order->number,
                            'out_trade_no'     => $order->number,
                            'total_fee'        => intval( $order->price * 100 ), // 单位：分
                            'notify_url'       => $request->root().'/weixin_notify_pay', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
                            'openid'           => $user->openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
                            'attach'           => '支付订单',
                        ];

                        $payment = Factory::payment(Config::get('wechat.payment.default'));
                        $result = $payment->order->unify($attributes);
                        
                        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS')
                        {
                            $prepayId = $result['prepay_id'];
                            $param = $payment->jssdk->bridgeConfig($prepayId);
                         
                        }
                        else{
                           return zcjy_callback_data('支付失败',1);
                        }

            }
            elseif($input['pay_platform'] == '支付宝')
            {
                           
                            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
                            $payRequestBuilder->setBody('课程购买');
                            $payRequestBuilder->setSubject('订单编号:'.$order->number);
                            $payRequestBuilder->setOutTradeNo($order->number);
                            $payRequestBuilder->setTotalAmount((string)$order->price);
                            $payRequestBuilder->setTimeExpress("1m");

                            $config = Config::get('alipay');
                            $payResponse = new \AlipayTradeService($config);
                            $param = $payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

                          
            }
            else
            {
                return zcjy_callback_data('支付参数错误',1);
            }
            return $param;
    }

    //结算课程
    public function settleCheck(Request $request)
    {
        $input = $request->all();

        $varify = app('zcjy')->varifyInputParam($input,['pay_platform','price']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }

        $user = auth('web')->user();

        #创建订单
        $order = app('zcjy')->OrderRepo()->generateOrder($user,$input['price'],$input['pay_platform']);

        $param = $this->getPayParam($request,$user,$order);

        return zcjy_callback_data($param);
    }

    //马上发起支付
    public function settleNow(Request $request)
    {
        $input = $request->all();

        $varify = app('zcjy')->varifyInputParam($input,['pay_platform']);

        if($varify)
        {
            return zcjy_callback_data($varify,1);
        }

        $user = auth('web')->user();

        $order = app('zcjy')->OrderRepo()->userNoPayOrder($user);

        if(empty($order))
        {
            return zcjy_callback_data('该记录不存在',1);
        }

        if($order->pay_status == '已支付')
        {
             return zcjy_callback_data('已经支付过',1);
        }

        $param = $this->getPayParam($request,$user,$order);
        
        #更新支付平台
        $order->update(['pay_platform'=>$input['pay_platform']]);

        return zcjy_callback_data($param);
    }

}