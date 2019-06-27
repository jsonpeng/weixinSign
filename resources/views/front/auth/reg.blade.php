@extends('front.layout.base')
@section('css')
	<style>
		body{
			background-color:#fff;
		}
		.login_btn{
			padding:10px 40px;
			text-align: center;
			margin-top: 10px;
		}
		.login .verification_code input{
			width:8em;
		}
	</style>
@endsection

@section('content')
<div class="login">
	<div class="bg_img">
		<img src="{{ asset('images/34.png') }}" alt="">
	</div>
	<div class="switch_tab weui-cell">
		<div class="weui-cell__bd">
	{{-- 		<a class="switch_item" href="/user/login">登录</a> --}}
			<a class="switch_item active" href="javascript:;">注册</a>
		</div>
	</div>
	<form action="">
		<div class="weui-cell name">
			<div class="weui-cell__bd">
				<div class="small_pic"><img src="{{ asset('images/36.png') }}" alt=""></div>
				<input type="text" name="name" placeholder="请输入姓名">
			</div>
		</div>
		<div class="Idcard weui-cell">
			<div class="weui-cell__bd">
				<div class="small_pic"><img src="{{ asset('images/38.png') }}" alt=""></div>
				<input type="text" name="idcard_num" maxlength="18" placeholder="请输入身份证">
			</div>
		</div>
		<div class="mobile weui-cell">
			<div class="weui-cell__bd">
				<div class="small_pic"><img src="{{ asset('images/37.png') }}" alt=""></div>
				<input type="number" maxlength="11" name="mobile" maxlength="11" placeholder="请输入手机号">
			</div>
		</div>
		<div class="verification_code weui-cell">
			<div class="weui-cell__bd">
				<div class="small_pic"><img src="{{ asset('images/39.png') }}" alt=""></div>
				<input type="number" name="code" placeholder="请输入验证码">
				<div id="sendCode">获取验证码</div>
			</div>
		</div>
		<div class="unit weui-cell" style="display: none;">
			<div class="weui-cell__bd">
				<div class="small_pic"><img src="{{ asset('images/40.png') }}" alt=""></div>
				<input type="text" name="ret_unit" placeholder="请选择退休单位" readonly="readonly">
				{{-- <div class="up_down"><img src="{{ asset('images/41.png') }}" alt=""></div> --}}
				{{-- <div class="unit-list">
					<div>斗鱼科技有限公司</div>
					<div>小米</div>
					<div>百度科技有限公司</div>
					<div>阿里巴巴有限公司</div>
				</div> --}}
			</div>
		</div>
	</form>
	<div class="login_btn">
		<div class="weui-cell__bd">
			<div>注册</div>
		</div>
	</div>
</div>
@endsection


@section('js')
	<script>
		var canGetCode = true;
		var time = 60;
		var interval;

		$('input[name=idcard_num]').blur(function(){
				var name  = $('input[name=name]').val();
				var idcard_num = $('input[name=idcard_num]').val();
				if(!$.empty(name) && !$.empty(idcard_num))
				{
					$.zcjyRequest('/ajax/find_nuit',function(res){
						if(res && res != null)
						{
							$('.unit').show(500);
							$('input[name=ret_unit]').val(res);
						}
						else{
							$('.unit').hide();
							$('input[name=ret_unit]').val('');
						}
					},{name:name,idcard_num:idcard_num});
				}
		});

		//点击获取验证码
		$('#sendCode').click(function(){
			var mobile = $('input[name=mobile]').val();
			if($.empty(mobile))
			{
				$.alert('请输入手机号','error');
				return;
			}
			if(mobile.length != 11)
			{
				$.alert('手机号格式不正确','error');
				return;
			}
			var that = this;
			if(canGetCode)
			{
				$.zcjyRequest('/ajax/send_code',function(res){

					if(res){
						interval =	setInterval(function(){
							sendCodeAction(that);
						},1000);
					}

				},{mobile:mobile});
			}
		});

		function sendCodeAction(obj)
		{
			canGetCode = false;
			time--;
			$(obj).text(time+'s后重试');
			if(time == 0)
			{
				window.clearInterval(interval);
				time = 60;
				canGetCode = true;
				$(obj).text('获取验证码');
			}
		}

		//点击注册
		$('.login_btn').click(function(){
			$.zcjyRequest('/ajax/prefect_reg',function(res)
			{
				if(res){
					$.alert(res);
					setTimeout(function(){
						location.href="/";
					},1000);
				}
			},$('form').serialize());
		});
	</script>
@endsection
