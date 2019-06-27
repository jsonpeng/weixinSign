@extends('front.layout.base')

@section('css')
	<style>
		body{
			background-color:#f0f0f0;
		}
		.expertItem{
			background-color: #fff;
			border-radius: 6px;
			box-shadow: 0px 4px 9.2px 0.8px rgba(0, 0, 0, 0.1);
		}
		.expertItem .weui-cell__hd{
			height:68px;
			width:68px;
			border-radius: 50%;
			overflow:hidden;
			background-color:#999;
			margin-right: 10px;
		}
		.expertItem .weui-cell__hd img{
			display: block;
			width:100%;
		}
		.expertItem .name{font-size: 20px;color:#333;}
		.expertItem .retirement-unit{font-size: 17px;color: #737373}
		.expertItem .Birthplace{font-size: 17px;color: #a8a8a8;}
	</style>
@endsection

@section('content')

<div class="experts weui-cell">
	<div class="weui-cell__bd">

		@foreach($experts as $expert)
			<a class="expertItem weui-cell" href="/expert/{!! $expert->id !!}">
				<div class="weui-cell__hd"><img src="{!! $expert->image !!}" alt=""></div>
				<div class="weui-cell__bd">
					<div class="name">{!! $expert->name !!}</div>
					<div class="retirement-unit">退休单位：{!! $expert->re_unit !!}</div>
					<div class="Birthplace">籍贯：{!! $expert->jiguan !!}</div>
				</div>
			</a>
		@endforeach

	</div>
</div>
@endsection


@section('js')

@endsection
