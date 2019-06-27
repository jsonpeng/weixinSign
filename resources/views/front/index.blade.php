@extends('front.layout.base')

@section('css')
    <style>
        .weui-grid{width: 50%;padding:30px 10px;}
        .weui-grid__label{
        	font-size: 20px;
        	color:#333;
        }
        .index-function-grids{
        	background-color:#fff;
        	border-radius:8px;
        }
        .weui-grid:before{
        	border-right: 3px solid #f2f2f2;
        }
        .weui-grids .weui-grid:nth-child(even):before{
        	border: 0;
        }
        .weui-grids .weui-grid:nth-child(1):before{
        	bottom:0;
        	top:unset;
        }
        .weui-grids .weui-grid:before{
        	height:90%;
        }
        .weui-grids .weui-grid:nth-child(1):after,.weui-grids .weui-grid:nth-child(2):after{
        	width:90%;
        	border-bottom: 3px solid #f2f2f2;
        }
        .weui-grids .weui-grid:nth-child(1):after{
        	right:0;
        	left:unset;
        }
		.index_page .head_img .shortcut{
			width:90px;
			position: absolute;
			bottom:0;
			right:0;
		}
		.index_page .head_img .shortcut img{
			
		}
    </style>
@endsection
@section('content')
<div class="index_page">
	<div class="head_img">
		<img src="{{ asset('images/13.jpg') }}" alt="">
		<img src="{{ asset('images/15.png') }}" class="title_img" alt="">
		<a href="/user/index" class="shortcut"><img src="{{ asset('images/to_center.png') }}" alt=""></a>
	</div>
	<div class="weui-cell">
		<div class="weui-cell__bd">
			<div class="weui-grids index-function-grids">
			    <a href="/cat" class="weui-grid">
			        <div class="weui-grid__icon">
			            <img src="{{ asset('images/4.png') }}" alt="">
			        </div>
			        <p class="weui-grid__label">课程设置</p>
			    </a>
			    {{-- sign_guide --}}
			    <a href="/like_groups/活动" class="weui-grid">
			        <div class="weui-grid__icon">
			            <img src="{{ asset('images/5.png') }}" alt="">
			        </div>
			        <p class="weui-grid__label">老科技工作者之家</p>
			    </a>
			    <a href="/like_groups" class="weui-grid">
			        <div class="weui-grid__icon">
			           <img src="{{ asset('images/6.png') }}" alt="">
			        </div>
			        <p class="weui-grid__label">兴趣小组</p>
			    </a>
			    <a href="/like_groups/活动" class="weui-grid">
			        <div class="weui-grid__icon">
			           <img src="{{ asset('images/7.png') }}" alt="">
			        </div>
			        <p class="weui-grid__label">活动报名</p>
			    </a>
			</div>
		</div>
	</div>
</div>
{{-- <a href="/cat">开设课程</a>

<br />

<a href="/user/login">登录</a> --}}

@endsection


@section('js')

@endsection
