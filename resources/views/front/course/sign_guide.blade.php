@extends('front.layout.base')

@section('css')
	<style>
		
	</style>
@endsection

@section('content')

	<div class="guide_notice weui-cell">
		<div class="weui-cell__bd">请选择微信支付缴费请谨慎报名，缴费后不能办理转班、更名和退款学校将以短信形式发送通知，请填写真实的手机号码请仔细阅读以下报名须知。</div>
	</div>
	<div class="weui-cell guide_text">
		<div class="weui-cell__bd">
			<div class="title">一、招生对象</div>
			<div>年满50周岁，具备完全行为能力，身心健康，能够坚持学习的离退休干部及其他老年人。其中，年龄超过75周岁报名体育系和舞蹈系的，或年龄超过80周岁的，必须提供本人和家属签字的《超龄学院入学申请表》，提交本年度体检报告方可报名。建议80周岁以上或体弱多病的老年人在家登录教育学习网免费学习。</div>
		</div>
	</div>

	<?php $show = Request::get('show');?>

	@if($show)	
	<div class="agreen_btn">我已仔细阅读并同意接受</div>
	@endif
	
@endsection


@section('js')
<script type="text/javascript">
	$('.agreen_btn').click(function(){
		javascript:window.parent.call_back_action();
	});
</script>
@endsection
