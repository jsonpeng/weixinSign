@extends('front.layout.base')

@section('css')
<style>
	body{
		background-color:#fff;
	}
	.news_detail .weui-cell__bd{
		font-size:20px;
		color:#333;
	}
	.news_detail .weui-cell__bd img{
		width:100%;
		display: block;
		margin:10px 0;
	}
	.news_title{
		text-align: center;
		font-weight:bold;
	}
	.news_content div,.news_content p{
		text-indent: 2em;
	}
</style>
@endsection

@section('content')
	<div class="news_detail weui-cell">
		<div class="weui-cell__bd">
			<div class="news_title">{!! $post->name !!}</div>
			<div class="news_content">{!! $post->content !!}</div>

		</div>
	</div>
@endsection


@section('js')
<script type="text/javascript">
</script>
@endsection
