@extends('front.layout.base')
@section('css')
	<style>
		body{
			background-color:#fff;
			padding-top:20px;
		}
		.weui-grid__label{
			font-size: 20px;
			color:#333;
		}
		.weui-grid:before,.weui-grid:after{
			border:0;
		}
	</style>
@endsection
@section('content')
	<div class="weui-grids index-function-grids">
		@if(count($cats))
			@foreach($cats as $cat)
		    <a href="@if(count($cat->child_cats)>1) /cat/{!! $cat->id !!} @else /courses/{!! isset($cat->child_cats[0]['id']) ? $cat->child_cats[0]['id'] : $cat->id !!} @endif" class="weui-grid">
		        <div class="weui-grid__icon">
		            <img src="{!! $cat->image !!}" onerror="javascript:this.src='{{ asset('images/1.jpg') }}';" alt="">
		        </div>
		        <p class="weui-grid__label">{!! $cat->name !!}</p>
		    </a>
		    @endforeach
	    @endif
	</div>
@endsection


@section('js')

@endsection
