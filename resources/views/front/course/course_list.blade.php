@extends('front.layout.base')

@section('css')
	<style>
		body{
			background-color:#fff;
		}
		.weui-cell{
			padding:5px 15px;
		}
	</style>
@endsection
@section('content')
	@if(count($courses))
		<div class="course">
			@foreach($courses as $course)
				<a class="second_course_item" href="/course/{!! $course->id !!}">
					<div class="weui-cell weui-cell_access info">
						<div class="weui-cell__bd">{!! $course->name !!}</div>
						<div class="weui-cell__ft"></div>
					</div>
					<div class="weui-cell intr" >
						<div class="weui-cell__bd">{!! $course->brief !!}</div>
					</div>
					<div class="weui-cell num">
						<div class="weui-cell__hd"><img src="{{ asset('images/16.png') }}" alt="">招生人数:  {!! $course->max_num !!}人</div>
					</div>
					<?php $attachs = $course->attachs; ?>
					@if(count($attachs))
						@foreach($attachs as $attach)
							<div class="weui-cell time">
								<div class="weui-cell__hd">
									<div class="part"><img src="{{ asset('images/17.png') }}" alt="">上课时间:  {!! $attach->weekday !!} {!! $attach->start_time !!}-{!! $attach->end_time !!}</div>
									<div class="part"><img src="{{ asset('images/18.png') }}" alt="">上课教室:  {!! $attach->classroom_name !!}</div>
									<div class="part"><img src="{{ asset('images/42.png') }}" alt="">老师:  {!! $attach->teacher_name !!}</div>
								</div>
							</div>
						@endforeach
					@endif
					<div class="weui-cell code">
						<div>编码:{!! $course->code !!}</div>
					</div>
				
					<div class="weui-cell apply_btn">
						<div class="weui-cell__bd"><span>¥</span>{!! app('zcjy')->CourseRepo()->coursePrice(auth('web')->user(),$course) !!}</div>
						<div class="weui-cell__ft">立即报名</div>
					</div>
				</a>
			@endforeach
		</div>
	@else
		<div class="no_content">这里空空如也~</div>
	@endif
@endsection


@section('js')
<script type="text/javascript">
	$('.apply_btn').click(function(e){
		e.stopPropagation();
        e.preventDefault();
      	location.href = $(this).parent().attr('href')+'?join=now';
	});
</script>
@endsection
