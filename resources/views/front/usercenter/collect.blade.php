@extends('front.layout.base')

@section('css')
	<style>
		.course{
			padding:10px 15px;
		}
		.course .order_price{
			background-color:#f5f5f5;
			color:#666;
			font-size:18px;
			border-bottom-left-radius:10px;
			border-bottom-right-radius: 10px;
		}
		.course .second_course_item{
			padding:15px 0 0 0;
			border-radius:5px;
			overflow: hidden;
		}
		.course .order_price{
			border-radius:0;
		}
		.course .order_price .weui-cell__bd{
			display: flex;
			justify-content: flex-end;
			color:#8c8c8c;
			font-size: 18px;
		}
		.course .order_price .weui-cell__bd img{
			width:20px;
			display: block;
			margin-right:5px;
		}
		.course .order_price .weui-cell__bd div,.course .order_price .weui-cell__bd a{
			display: flex;
			align-items: center;
			padding:0 10px;
			border:1px solid #ccc;
			border-radius:15px;
			margin-left: 10px;
			color:#8c8c8c;
	</style>
@endsection
@section('content')
<div class="course needPay">
	@if(count($courses))
		@foreach($courses as $course)
			<div class="second_course_item">
				<div class="weui-cell info">
					<div class="weui-cell__bd">{!! $course->name !!}</div>
					<div class="weui-cell__ft"></div>
				</div>
				<div class="weui-cell intr" >
					<div class="weui-cell__bd">{!! $course->brief !!}</div>
				</div>
				<div class="weui-cell num">
					<div class="weui-cell__hd"><img src="{{ asset('images/16.png') }}" alt="">招生人数:  {!! $course->max_num !!}人</div>
				</div>
					@if($course->Type == '课程班')

						<?php $attachs = $course->attachs; ?>

							@if(count($attachs))
								@foreach($attachs as $attach)
								<div class="weui-cell time">
									<div class="weui-cell__hd">
										<div class="part"><img src="{{ asset('images/17.png') }}" alt="">上课时间:  {!! $attach->weekday !!} {!! $attach->start_time !!}-{!! $attach->end_time !!}</div>
										<div class="part"><img src="{{ asset('images/18.png') }}" alt="">上课教室:  {!! $attach->classroom_name !!}</div>
									</div>
								</div>
								@endforeach
							@endif

						@else
							<div class="weui-cell time">
								<div class="weui-cell__hd">
									<div class="part"><img src="{{ asset('images/17.png') }}" alt="">开展时间: {!! $course->activity_time !!}</div>
									<div class="part"><img src="{{ asset('images/17.png') }}" alt="">报名时间:{!! $course->sign_time.'--'.$course->sign_time_end  !!}</div>
								</div>
							</div>
						@endif

						<div class="weui-cell code">
							<div>编码：W10{!! $course->id !!}</div>
						</div>
				<div class="weui-cell apply_btn">
					<div class="weui-cell__bd"><span>¥</span>{!! app('zcjy')->CourseRepo()->coursePrice($user,$course) !!}</div>
				</div>
				<div class="weui-cell order_price">
					<div class="weui-cell__bd">
						<div class="cancel_collect" data-id="{!! $course->id !!}"><img src="{{ asset('images/25.png') }}" alt="">取消收藏</div>
						<a class="link_detail" href="{!! $course->Url !!}"><img src="{{ asset('images/26.png') }}" alt="">查看详情</a>
					</div>
				</div>
			</div>
		@endforeach
	@else
		<div class="no_content">这里空空如也~</div>
	@endif
</div>

@endsection


@section('js')
<script type="text/javascript">
	$('.cancel_collect').click(function(){
			var id = $(this).data('id');
			var parent = $(this).parent().parent().parent();
			$.zcjyRequest('/ajax/action_attention_course/'+id,function(res){
				if(res){
					$.alert(res);
					parent.remove();
				}
			});
	});
</script>
@endsection
