@extends('front.layout.base')

@section('css')
<style>
	.news_tab{
		/*display: flex;*/
		background-color: #fff;
		overflow: hidden;
		overflow-x: scroll;
	}
	.news_tab::-webkit-scrollbar {
	    display: none;
	}
	.news_tab .warp{
		white-space: nowrap;
		font-size:0;
	}
	.tab_item{
		display: inline-block;
		width:33.333%;
		text-align: center;
		line-height: 1em;
		font-size:18px;
		height: 50px;
		margin:0;
		padding:0;
	}
	.news_tab .tab_item p{
		flex: 1;
		margin-top: 15px;
	}
	.news_tab .tab_item p{
		border-right: 1px solid #f0f0f0;
	}
	.tab_item.active{
		color:#449bff;
		font-weight: bold;
		border-bottom: 2px solid #449bff;
	}
	.news_item{
		display: block;
		/*padding:10px;*/
		background-color:#fff;
		box-shadow: 0px 4px 9.2px 0.8px rgba(0, 0, 0, 0.1);
		border-radius: 5px;
		margin-bottom: 10px;
		overflow: hidden;
	}
	.news_item .news_title{
		font-size:20px;
		color:#333;
		/*position: absolute;*/
		bottom:10px;
		left: 0;
		width:100%;
		box-sizing: border-box;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
		color:#fff;
		padding:0 20px;
	}
	.news_item .news_content{
		font-size:18px;
		color:#a6a6a6;
		overflow: hidden;
	  	text-overflow: ellipsis;
	   	display: box;
	   	display: -webkit-box;
	   	line-clamp: 2;
	   	-webkit-line-clamp: 2;
	   	-webkit-box-orient: vertical;
	   	padding:10px;
	}
	.news_item .news_date{
		margin-top:10px;
		font-size:17px;
		color:#737373;
	}
	.news_item .news_img{
		position: relative;
		height:150px;
		overflow: hidden;
	}
	.news_item .cover{
		background: rgba(0, 0, 0, 0.4);
		position: absolute;
		top: 0;
		left: 0;
		bottom: 0;
		right: 0;
	}
	.news_item .news_img img{
		display:block;
		width:100%;
	}
</style>
@endsection

@section('content')

	@if(count($cats))
	<div class="news_tab">
		<div class="warp">

			@foreach($cats as $cat)
				@if($cat->name == $cat_name) 
					<div class="tab_item @if($cat->name == $cat_name) active @endif" data-link="/post_cat/{!! $cat->name !!}"><p>{!! $cat->name !!}</p></div>
				@endif
			@endforeach

			<?php $user = auth('web')->user();?>
			@if(isset($user->can_read_zj))
				<div class="tab_item @if(Request::is('experts')) active @endif" data-link="/experts"><p>专家资料</p></div>
			@endif

		</div>
	</div>
	@endif

	@if(count($posts))
		@foreach($posts as $post)
			<div class="news_list weui-cell">
				<div class="weui-cell__bd">
					<a class="news_item" href="/post/{!! $post->id !!}">
						<div class="news_img">
							<div class="cover"></div>
							<img src="{!! $post->image !!}" onerror="javascript:this.src='{{ asset('images/bg.jpg') }}';" alt="">
						</div>
						<div class="news_title" style="color: black;
    padding-top: 14px;font-size: 16px;">{!! $post->name !!}</div>
						<div class="news_content" style="padding: 10px 20px;font-size: 14px;">{!! $post->brief !!}</div>
						{{-- <div class="news_date">发布时间:{!! time_parse($post->created_at)->format('Y-m-d') !!}</div> --}}
					</a>

					
				</div>
			</div>
		@endforeach
	@else
		<div class="no_content">这里空空如也~</div>
	@endif

@endsection


@section('js')
<script type="text/javascript">
	$('.tab_item').click(function(event) {
			$.location($(this).data('link'),false);
	});
	$(document).ready(function() {
		var index=$('.tab_item.active').index()+1;
		if(index>=4){
			var num=index%3;
			var step=$('.tab_item').width();
			$('.news_tab').scrollLeft(step*num);
		}
		console.log(num);
		console.log(index);
		console.log(step*num);
	});
</script>
@endsection
