@extends('front.layout.base')
@section('css')
	<style>
		body{
			background-color:#fff;
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
			<a class="switch_item active" href="javascript:;">登录</a>
			<a class="switch_item" href="/user/reg">注册</a>
		</div>
	</div>
	<form action="">
		<div class="weui-cell name">
			<div class="weui-cell__bd">
				<div class="small_pic"><img src="{{ asset('images/36.png') }}" alt=""></div>
				<input type="text" placeholder="请输入姓名">
			</div>
		</div>
		<div class="Idcard weui-cell">
			<div class="weui-cell__bd">
				<div class="small_pic"><img src="{{ asset('images/38.png') }}" alt=""></div>
				<input type="text" placeholder="请输入身份证">
			</div>
		</div>
	</form>
	<div class="login_btn">
		<div class="weui-cell__bd">
			<div>登录</div>
		</div>
	</div>
</div>
@endsection


@section('js')

@endsection
