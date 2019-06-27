@extends('front.layout.base')

@section('css')
	<style>
		body{
			padding-bottom:55px;
		}
		.weui-cell{
			padding:5px 15px;
		}
		.collected{
			display: none;
		}
		.bottom .weui-cell__bd>div:nth-child(1){
			color:#7a7a7a;
		}
		.bottom .price{
			display: inline-block;
			color:#ff5959;
			font-size: 23px;
		}
		.bottom .price span{
			font-size: 16px;
		}
		.course .apply_btn .weui-cell__ft{
			display: flex;
			align-items: center;
			color:#8c8c8c;
			border-color:#8c8c8c;
		}
		.course .apply_btn .weui-cell__ft img{
			width:21px;
			display: inline-block;
		}
		.second_course_item{
			border-bottom:1px solid #f5f5f5;
		}
	</style>
@endsection
@section('content')
	@if(count($courses))
		<div class="course">
			@foreach($courses as $course)
			<?php $IsExcess = $course->course->IsExcess;  ?>
				<a class="second_course_item @if($IsExcess) excess @endif" href="javascript:;">
					<div class="weui-cell  info">
						<div class="weui-cell__bd">{!! $course->course_name !!}@if($IsExcess) {!! tag('[报名人数已满]') !!} @endif</div>
						<div class="weui-cell__ft"></div>
					</div>
					<div class="weui-cell intr" >
						<div class="weui-cell__bd">{!! $course->course_des !!}</div>
					</div>
					<div class="weui-cell num">
						<div class="weui-cell__hd"><img src="{{ asset('images/16.png') }}" alt="">招生人数:  {!! $course->course->max_num !!}人</div>
					</div>

					@if($course->course->Type == '课程班')

					<?php $attachs = $course->course->attachs; ?>

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
								<div class="part"><img src="{{ asset('images/17.png') }}" alt="">开展时间: {!! $course->course->activity_time !!}</div>
								<div class="part"><img src="{{ asset('images/17.png') }}" alt="">报名时间:{!! $course->course->sign_time.'--'.$course->course->sign_time_end  !!}</div>
							</div>
						</div>
					@endif

					<div class="weui-cell code">
						<div>编码：{!! $course->code !!}</div>
					</div>
					<div class="weui-cell apply_btn">
						<div class="weui-cell__bd"><span>¥</span>{!! $course->price !!}</div>
						<div class="weui-cell__ft deleteCourse" data-id="{!! $course->id !!}"><img src="{{ asset('images/27.png') }}" alt="">删除</div>
					</div>
				</a>
			@endforeach

			<div class="bottom weui-cell">
				<div class="weui-cell__bd">
					<div>支付金额：<div class="price"><span>¥</span>{!! $price !!}</div></div>
					<div class="check_btn">确认</div>
				</div>
			</div>

		</div>
	@endif

@endsection


@section('js')
	<script>

		$('.check_btn').click(function(event) {
			var excess = false;
			$('.second_course_item').each(function(){
				if($(this).hasClass('excess'))
				{
					excess = true;
				}
			});
			if(!excess){
				$.zcjyFrameOpen('/sign_guide?show=1','学员须知');
			}
			else{
				$.alert('请删除报名人数已满的课程后结算!','error');
			}
			/* Act on the event */
		});

		function call_back_action(){
			window.location.href='/choose_pay';
		}
		
		$('.deleteCourse').click(function(){
			event.stopPropagation();
			var id = $(this).data('id');
			$.zcjyRequest('/ajax/del_course/'+id,function(res){
				if(res){
					$.alert(res);
					location.reload();
				}
			});
		});
		
	</script>
@endsection
