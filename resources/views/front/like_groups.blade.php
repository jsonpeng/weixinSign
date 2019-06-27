@extends('front.layout.base')

@section('css')
	<style>
		body{
			background-color:#fff;
		}
	</style>
@endsection

@section('content')

	@if(count($groups))
		<div class="interest_list">
			@foreach($groups as $group)
				<a class="item" href="/like_group/{!! $group->id !!}">
					<div class="weui-cell weui-cell_access title">
						<div class="weui-cell__bd">{!! $group->name !!}<div class="price"><span>¥</span>{!! app('zcjy')->CourseRepo()->coursePrice(auth('web')->user(),$group) !!}</div></div>
						<div class="weui-cell__ft"></div>
					</div>
					<div class="weui-cell content">
						<div class="weui-cell__bd">
							<div><img src="{{ asset('images/28.png') }}" alt="">活动人数：{!! $group->max_num !!}人</div>
							<div><img src="{{ asset('images/29.png') }}" alt="">开展时间：{!! $group->activity_time !!}</div>
							<div><img src="{{ asset('images/29.png') }}" alt="">报名时间：{!! !empty($group->sign_time) ? time_parse($group->sign_time)->format('Y-m-d') : '' !!}-{!! !empty($group->sign_time_end) ? time_parse($group->sign_time_end)->format('Y-m-d') : '' !!}</div>
						</div>
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
