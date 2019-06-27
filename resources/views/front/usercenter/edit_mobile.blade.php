@extends('front.layout.base')

@section('css')
	<style>
		body{
			background-color:#fff;
		}
	</style>
@endsection

@section('content')
	
	<div class="change_phone_num">
		<form action="">
			<div class="phone_num weui-cell">
				<div class="weui-cell__bd">
					<input type="number" maxlength="11" name="mobile"  placeholder="请输入手机号">
				</div>
			</div>
			<div class="code weui-cell">
				<div class="weui-cell__bd">
					<input type="number" maxlength="8" name="code"  placeholder="请输入验证码">
					<div class="send_code" id="sendCode">获取验证码</div>
				</div>
			</div>
		</form>
		<div class="makeSure_btn">确认</div>
	</div>
@endsection


@section('js')
	<script>
		var canGetCode = true;
		var time = 60;
		var interval;
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

		//点击确认
		$('.makeSure_btn').click(function(){
			$.zcjyRequest('/ajax/update_mobile',function(res)
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
