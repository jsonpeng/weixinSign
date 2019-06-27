@extends('front.layout.base')

@section('css')
	<style>
		.course{
			padding:0 15px 70px 15px;
		}
		.second_course_item{
			border-radius:10px;
			margin-bottom: 10px;
			box-shadow: 0px 4px 9.2px 0.8px rgba(0, 0, 0, 0.1);
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
		.needPay .second_course_item{
			padding:0;
			border-bottom:0;
			border-radius: 0;
			margin-bottom:0;
			box-shadow:0;
		}
		.needPay .order_time{
			background-color:#e0e0e0;
			color:#666;
			font-size:18px;
			border-top-left-radius:10px;
			border-top-right-radius: 10px;
		}
		.needPay .order_price{
			background-color:#f5f5f5;
			color:#666;
			font-size:18px;
			border-bottom-left-radius:10px;
			border-bottom-right-radius: 10px;
			margin-bottom: 10px;
			box-shadow: 0px 4px 9.2px 0.8px rgba(0, 0, 0, 0.1);
		}
		.needPay .order_price .weui-cell__bd{
			font-size:18px;
			color:#737373;
		}
		.needPay .order_price .weui-cell__ft{
			font-size:22px;
			color:#ff5959;
		}
		.needPay .order_price .weui-cell__ft span{
			font-size:16px;
		}
		.needPay .order_price:after{
			content: " ";
			    position: absolute;
			    left: 0;
			    top: 0;
			    right: 0;
			    height: 1px;
			    border-top: 1px solid #e5e5e5;
		}
		.course .check_btn{
			position: fixed;
			bottom:0;
			left:0;
			width:100%;
			box-sizing: border-box;
			background:#fff;
			height:60px;
			align-items: center;
		}
		.course .check_btn .weui-cell__bd{
			color:#7a7a7a;
			font-size:18px;
		}
		.course .check_btn p{
			display: inline-block;
			font-size:22px;
			color:#ff5959;
		}
		.course .check_btn p span{ 
			display: inline-block;
			font-size:16px;
			color:#ff5959;
		}
		.course .check_btn .weui-cell__ft{
			padding:5px 20px;
			background-color:#449bff;
			color:#fff;
			border-radius: 20px;
			font-size:20px;
		}
	</style>
@endsection

@section('content')
<div class="order_list">
	<div class="navTab weui-cell">
		<div class="weui-cell__bd">
			<div class="item @if(!$check) active @endif">已报名</div>
			{{-- <div class="item @if($check) active @endif">待付款</div> --}}
		</div>
	</div>
	{{-- 已报名 --}}
	<div class="course needPay" @if($check) style="display: none;" @endif>
		@if(count($pay_orders))
			@foreach($pay_orders as $order)
				<div class="orderitem">
					<div class="order_time weui-cell">
						<div class="weui-cell__bd">{!! $order->created_at !!}</div>
					</div>
					<?php $joins = $order->joins; ?>
					@if(count($joins))
						@foreach($joins as $course)
								<a class="second_course_item" href="javascript:;">
									<div class="weui-cell weui-cell_access info">
										<div class="weui-cell__bd">{!! $course->course_name !!}</div>
										{{-- <div class="weui-cell__ft"></div> --}}
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
									</div>
								</a>
						@endforeach
					@endif
					<div class="weui-cell order_price">
						<div class="weui-cell__bd">实付金额：</div>
						<div class="weui-cell__ft">{!! $order->price !!} <span>元</span></div>
					</div>
				</div>
			@endforeach
		@else
			<div class="no_content">这里空空如也~</div>
		@endif
	</div>

	{{-- 已报名和待支付样式有区别 不要共用一套 --}}
	{{-- 已报名和待支付样式有区别 不要共用一套 --}}
	{{-- 已报名和待支付样式有区别 不要共用一套 --}}
	{{-- 待支付 --}}
	<div class="course" @if(!$check) style="display: none;" @endif>
		@if(count($nopay_orders))
			@foreach($nopay_orders as $order)
				<?php $joins = $order->joins; ?>
				@if(count($joins))
					@foreach($joins as $course)
					<?php $IsExcess = $course->course->IsExcess;  ?>
						<a class="second_course_item @if($IsExcess) excess @endif" href="javascript:;">
							<div class="weui-cell  info">
								<div class="weui-cell__bd">{!! $course->course_name !!}@if($IsExcess) {!! tag('[报名人数已满]') !!} @endif</div>
								{{-- <div class="weui-cell__ft"></div> --}}
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
				@endif
			@endforeach
			<div class="check_btn weui-cell">
				<div class="weui-cell__bd">支付总额：<p><span>¥</span>{!! $nopay_all_price !!}</p></div>
				<div class="weui-cell__ft" onclick="payNow()">立即付款</div>
			</div>
		@else
			<div class="no_content">这里空空如也~</div>	
	
		@endif
	</div>
</div>

@endsection


@section('js')
	<script>
		$('.item').click(function(event) {
			/* Act on the event */
			$(this).addClass('active').siblings().removeClass('active');
			$('.course').eq($(this).index()).show().siblings('.course').hide();
			console.log($(this).index());
		});
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
		function payNow()
		{
			var excess = false;
			$('.second_course_item').each(function(){
				if($(this).hasClass('excess'))
				{
					excess = true;
				}
			});
			if(!excess){
				$.location('/choose_pay/?paynow=true');
			}
			else{
				$.alert('请删除报名人数已满的课程后结算!','error');
			}
		}
	</script>
@endsection
