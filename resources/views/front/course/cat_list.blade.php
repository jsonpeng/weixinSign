@extends('front.layout.base')
@section('css')
	<style>
		body{
			background-color:#fff;
		}
		.weui-cell{
			padding:0 15px;
		}
	</style>
@endsection
@section('content')

	@if(count($cats))
		<div class="course">
			@foreach($cats as $cat)
				<a class="second_course_item" href="/courses/{!! $cat->id !!}">
					<div class="weui-cell weui-cell_access info">
						<div class="weui-cell__bd">{!! $cat->name !!}</div>
						<div class="weui-cell__ft"></div>
					</div>
					<div class="weui-cell intr" >
						<div class="weui-cell__bd">{!! $cat->content !!}</div>
					</div>
				</a>
			@endforeach
		</div>
	@else
		<div class="no_content">这里空空如也~</div>
	@endif

@endsection


@section('js')

@endsection
