@extends('front.layout.base')

@section('css')

@endsection

@section('content')
<div class="pay_way">
	<div class="wechat_pay weui-cell">
		<div class="weui-cell__hd"><img src="{{ asset('images/30.png') }}" alt=""></div>
		<div class="weui-cell__bd">微信支付</div>
		<div class="weui-cell__ft"><input type="radio" id="wechat" class="radio" name='pay_way' value="1"><label for="wechat"></label></div>
	</div>
{{-- 	<div class="line">
		<div></div>
	</div> --}}
{{-- 	<div class="wechat_pay weui-cell">
		<div class="weui-cell__hd"><img src="{{ asset('images/32.png') }}" alt=""></div>
		<div class="weui-cell__bd">支付宝支付</div>
		<div class="weui-cell__ft"><input type="radio" id="alipay" class="radio" name='pay_way' value="1"><label for="alipay"></label></div>
	</div> --}}
	<form>
		<input type="hidden" name="pay_platform" value="" />
		<input type="hidden" name="price" value="{!! $price !!}" />
	</form>
	<div class="bottom enter_pay">确认支付 <div class="price"><span>¥</span>{!! $price !!}</div></div>
</div>

@endsection


@section('js')
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('ap.js') }}"></script>
<script type="text/javascript">
		var pay_url = '/ajax/settle_check';
		@if($paynow)
			pay_url = '/ajax/settle_now';		
		@endif
		$('.enter_pay').click(function(){
			var pay_platform = $('input[name=pay_platform]').val();
			if($.empty(pay_platform))
			{
				$.alert('请先选择支付方式','error');
				return;
			}
			$.zcjyRequest(pay_url,function(res){
				if(res)
				{
					//微信支付
					if(pay_platform == '微信')
					{
							  if (typeof WeixinJSBridge === 'undefined') { // 微信浏览器内置对象。参考微信官方文档
				                if (document.addEventListener) {
				                  document.addEventListener('WeixinJSBridgeReady', onBridgeReady(res), false)
				                } 
				                else if (document.attachEvent) {
				                  document.attachEvent('WeixinJSBridgeReady', onBridgeReady(res));
				                  document.attachEvent('onWeixinJSBridgeReady', onBridgeReady(res));
				                }
				            } 
				              else {
				                onBridgeReady(res);
				              }	
					}
					//支付宝支付
					else
					{
						if(res){
					 		_AP.pay(res);
					 	}
					}
				}
			},$('form').serialize());
		});

		$('input[name=pay_way]').click(function(){
			var val = '支付宝';
			if($(this).attr('id') == 'wechat')
			{
				val = '微信';
			}
			$('input[name=pay_platform]').val(val);
		});

		function onBridgeReady(message) {
			    data = JSON.parse(message);
			    /* global WeixinJSBridge:true */
			    WeixinJSBridge.invoke(
			      'getBrandWCPayRequest', {
			        'appId': data.appId, // 公众号名称，由商户传入
			        'timeStamp': data.timeStamp, // 时间戳，自1970年以来的秒数
			        'nonceStr': data.nonceStr, // 随机串
			        'package': data.package,
			        'signType': data.signType, // 微信签名方式：
			        'paySign': data.paySign // 微信签名
			      },
			      function (res) {
			        // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回ok，但并不保证它绝对可靠。
			        if (res.err_msg === 'get_brand_wcpay_request:ok') 
			        {
			            $.alert('支付成功');

			            setTimeout(function () {
			                window.location.href = '/user/index';
			            }, 1000);
			        } else if(res.err_msg == "get_brand_wcpay_request:cancel"){
			        	alert('您已取消支付,请重新添加课程进行支付');
			        	location.reload();
			        }else {
			        	alert('支付失败,错误信息: ' + res.err_msg);
			        	location.reload();
			        }
			      }
			    );
  		}
</script>
@endsection
