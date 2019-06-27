@extends('front.layout.base')

@section('css')
	<style>
		.time_select{
			position: relative;
		}
		.time_list{
			background-color:#e0e0e0;
			position: absolute;
			top:40px;
			left: 0;
			width:100%;
			z-index: 100;
			box-shadow: 0px 4px 9.2px 0.8px rgba(44, 143, 239, 0.5);
			display: none;
		}
		.time_list div{
			color:#666;
			padding-left: 1em;
			line-height:2em;
			font-size:22px;
			border-bottom:1px solid #eee;
		}
	</style>
@endsection
@section('content')
	<div class="timetable">
		<div class="box">
			<div class="time_select weui-cell">
				<div class="weui-cell__bd">@if(isset($input['week'])) {!! $input['week'] !!} @else 请选择上课时间 @endif</div>
				<div class="weui-cell__ft"><img src="{{ asset('images/22.png') }}" alt=""></div>
				<div class="time_list">
					<div>全部</div>
					<div>星期一</div>
					<div>星期二</div>
					<div>星期三</div>
					<div>星期四</div>
					<div>星期五</div>
					<div>星期六</div>
					<div>星期天</div>
				</div>
			</div>
		</div>

		@if(count($courses_biao))
			@foreach($courses_biao as $key => $biao)
				@if(count($biao))

					<div class="table_list" 	@if(isset($input['week']) && $input['week'] == $key || !isset($input['week'])) style="display: block;" @else style="display: none;" @endif>
						<div class="table_item">
							<div class="weui-cell week">
								<div class="weui-cell__bd">{!! $key !!}</div>
							</div>
							<div class="t_head weui-cell">
								<div class="weui-cell__hd">课程</div>
								<div class="weui-cell__bd">时间</div>
								<div>教室</div>
							</div>

							
								@foreach($biao as $item)
								<div class="t_body weui-cell">
									<div class="weui-cell__hd">{!! $item['name'] !!}</div>
									<div class="weui-cell__bd">{!! $item['start_time'] !!}-{!! $item['end_time'] !!}</div>
									<div class="weui-cell__ft">{!! $item['classroom_name'] !!}</div>
								</div>
								@endforeach
						
					
						</div>
					</div>

				@endif
			@endforeach
		@else
			<div class="no_content">这里空空如也~</div>
		@endif

	</div>
@endsection


@section('js')
	<script type="text/javascript">
		$('.time_select').click(function(event) {
			/* Act on the event */
			$('.time_list').slideToggle();
		});
		$('.time_list > div').click(function(){
			var week = $(this).text();
			if(week=='全部')
			{
				week = '';
			}
			else{
				week = '?week='+week;
			}
			location.href = '/user/course_biao'+week;
		});
	</script>
@endsection
