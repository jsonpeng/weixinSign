@extends('front.layout.base')

@section('css')
<style>
</style>
@endsection

@section('content')

<div class="interest_list">
	<a class="item" href="javascript:;">
		<div class="weui-cell title">
			<div class="weui-cell__bd">{!! $course->name !!}<div class="price"><span>¥</span>{!! app('zcjy')->CourseRepo()->coursePrice(auth('web')->user(),$course) !!}</div></div>
			<div class="weui-cell__ft"></div>
		</div>
		<div class="weui-cell content">
			<div class="weui-cell__bd">
				<div><img src="{{ asset('images/28.png') }}" alt="">活动人数：{!! $course->max_num !!}人</div>
				<div><img src="{{ asset('images/29.png') }}" alt="">开展时间：{!! $course->activity_time !!}</div>
				<div><img src="{{ asset('images/29.png') }}" alt="">报名时间：{!! $course->sign_time !!}-{!! $course->sign_time_end !!}</div>
			</div>
		</div>
	</a>
</div>
<div class="course">
	<div class="weui-cell">
		<div class="weui-cell__bd detail_text">
			<div class="g-head"><img src="{{ asset('images/19.png') }}" alt="">介绍</div>
			{{-- <div class="teacher">老师： 韩梅梅</div> --}}
			<div class="content">
				<div>{!! $course->content !!}</div>
			</div>
		</div>
	</div>

	<div class="bottom weui-cell">
		<div class="weui-cell__bd">
			<div class="collection">

				<div>@if($attention_status)<img src="{{ asset('images/21.png') }}" alt=""> @else<img src="{{ asset('images/20.png') }}" class="" alt="">@endif</div>

				@if($attention_status)
					<p>已收藏</p>
				@else
					<p class="">收藏</p>
				@endif

			</div>
			<div class="check_btn" @if(!$insideUserVarify) style="background-color: #ddd;" @endif>@if(!$insideUserVarify) 该板块仅对内部员工开放 @else 立即报名 @endif</div>
		</div>
	</div>


	{{-- 报名弹窗 --}}
	<div class="wrappe">
		<div class="cover"></div>
		<div class="box">
			<div class="close"><img src="{{ asset('images/23.png') }}" alt=""></div>
			<div class="img"><img src="{{ asset('images/24.png') }}" alt=""></div>
			<div class="text">
				您还需要<br>报名其他兴趣小组吗？
			</div>
			<div class="btn">
				<a class="addMore" href="/like_groups">添加</a>
				<a class="checkNow" href="/enter_sign">结算</a>	
			</div>
		</div>
	</div>
</div>

@endsection


@section('js')
<script type="text/javascript">

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

		@if($insideUserVarify)
		$('.check_btn').click(function(event) {
			$.zcjyRequest('/ajax/add_courses/{!! $course->id !!}',function(res){
					if(res){
						if(res == '报名成功')
						{
							$.alert(res);
							return;
						}
						$('.wrappe').show();
					}
			});
			/* Act on the event */
		});
		@endif

		$('.close').click(function(event) {
			/* Act on the event */
			$('.wrappe').hide();
		});
		
</script>
@endsection
