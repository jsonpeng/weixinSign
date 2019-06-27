@extends('front.layout.base')

@section('css')
	<style>
		.weui-cell{
			padding:5px 15px;
		}
		.collected{
			display: none;
		}
		.changePhone{
			font-weight: normal;
		}
	</style>
@endsection
@section('content')
<div class="userCenter">
	<div class="top_part">
		<div class="user_img"><img src="{{ asset('images/8.jpg') }}" alt=""></div>
		<?php $user = optional($user); ?>
		<div class="user_info weui-cell">
			<div class="user_pic">
				<div><img onerror="javascript:this.src='{{ asset('images/1.jpg') }}';" src="{!! $user->head_image !!}" alt=""></div>
				<div>{!! $user->nickname !!}</div>
			</div>
			<a class="changePhone" href="/user/edit_mobile">修改手机号</a>
		</div>
	</div>
	<div class="menu">
		<a class="weui-cell" href="/user/orders">
			<div class="weui-cell__hd"><img src="{{ asset('images/9.png') }}" alt=""></div>
			<div class="weui-cell__bd">我的订单</div>
			<div class="weui-cell__ft"><img src="{{ asset('images/10.png') }}" alt=""></div>
		</a>
		<div class="line"><div></div></div>
		<a class="weui-cell" href="/user/course_biao">
			<div class="weui-cell__hd"><img src="{{ asset('images/11.png') }}" alt=""></div>
			<div class="weui-cell__bd">课程表</div>
			<div class="weui-cell__ft"><img src="{{ asset('images/10.png') }}" alt=""></div>
		</a>
		<div class="line"><div></div></div>
		<a class="weui-cell" href="/user/collect">
			<div class="weui-cell__hd"><img src="{{ asset('images/12.png') }}" alt=""></div>
			<div class="weui-cell__bd">我的收藏</div>
			<div class="weui-cell__ft"><img src="{{ asset('images/10.png') }}" alt=""></div>
		</a>
	</div>

</div>


@endsection


@section('js')

@endsection
