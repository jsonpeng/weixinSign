@extends('front.layout.base')

@section('css')
	<style>
		.weui-cell{
			padding:5px 15px;
		}
		.collected{
			display: none;
		}
	</style>
@endsection
@section('content')
		
	<div class="course">
		<a class="second_course_item" href="javascropt:;">
			<div class="weui-cell  info">
				<div class="weui-cell__bd">{!! $course->name !!}</div>
				<div class="weui-cell__ft"></div>
			</div>
			<div class="weui-cell intr" >
				<div class="weui-cell__bd">{!! $course->brief !!}</div>
			</div>
			<div class="weui-cell num">
				<div class="weui-cell__hd"><img src="{{ asset('images/16.png') }}" alt="">招生人数: {!! $course->max_num !!}人</div>
			</div>

			@if(count($course_attachs))
				@foreach($course_attachs as $attach)
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
				<div>编码：{!! $course->code !!}</div>
			</div>
			
			<div class="weui-cell apply_btn">
				<div class="weui-cell__bd"><span>¥</span>{!! app('zcjy')->CourseRepo()->coursePrice($user,$course) !!}</div>
			</div>
		</a>
		<div class="weui-cell">
			<div class="weui-cell__bd detail_text">
				<div class="g-head"><img src="{{ asset('images/19.png') }}" alt="">班级介绍</div>
		{{-- 		<div class="teacher">老师： 韩梅梅</div> --}}
				<div class="content">
					<div>{!! $course->content !!}</div>
				</div>
			</div>
		</div>
		<div class="bottom weui-cell">
			<div class="weui-cell__bd">
				<div class="collection" >

					<div>@if($attention_status)<img src="{{ asset('images/21.png') }}" alt=""> @else<img src="{{ asset('images/20.png') }}" class="" alt="">@endif</div>

					@if($attention_status)
						<p>已收藏</p>
					@else
						<p class="">收藏</p>
					@endif

				</div>
				<div class="check_btn">立即报名</div>
			</div>
		</div>


		{{-- 报名弹窗 --}}
		<div class="wrappe">
			<div class="cover"></div>
			<div class="box">
				<div class="close"><img src="{{ asset('images/23.png') }}" alt=""></div>
				<div class="img"><img src="{{ asset('images/24.png') }}" alt=""></div>
				<div class="text">
					您还需要<br>报名其他课程吗？
				</div>
				<div class="btn">
					<a class="addMore" href="/cat">添加</a>
					<a class="checkNow" href="/enter_sign">结算</a>	
				</div>
			</div>
		</div>
	</div>


@endsection


@section('js')
	<script>

		@if(isset($input['join']))
			$(function(){
				$('.check_btn').click();
			});
		@endif

		$('.collection').click(function(event) {
				if(varifyUser())
				{
					return;
				}
				var that = this;
				$.zcjyRequest('/ajax/action_attention_course/{!! $course->id !!}',function(res){
					if(res){
						$.alert(res);
						if(res == '收藏成功'){
							$(that).find('p').text('已收藏');
							$(that).find('img').attr('src',"{{ asset('images/21.png') }}");
						}
						else{
							$(that).find('p').text('收藏');
							$(that).find('img').attr('src',"{{ asset('images/20.png') }}");
						}
					}
				});
				

		});
		$('.check_btn').click(function(event) {
			$.zcjyRequest('/ajax/add_courses/{!! $course->id !!}',function(res){
					if(res){
						$('.wrappe').show();
					}
			});
			/* Act on the event */
		});
		$('.close').click(function(event) {
			/* Act on the event */
			$('.wrappe').hide();
		});
	</script>
@endsection
