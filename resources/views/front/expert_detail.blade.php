@extends('front.layout.base')

@section('css')
 <style>
 	body{
 		background-color:#fff;
 	}
 	.extendName{
 		display: flex;
 		flex-direction: column;
 		align-items: center;
 		padding:22.5px 0;
 		background-image: url('../images/expert.jpg');
 		background-repeat: no-repeat;
 		background-size: cover;
 	}
 	.extendName .pic{
 		width:75px;
 		height: 75px;
 		border-radius: 50%;
 		background-color:#eee;
 		overflow:hidden;
 	}
 	.extendName .pic img{
 		display: block;
 		width:100%;
 	}
 	.extendName .name{
 		font-size: 20px;color:#333;
 	}
 	.partTitle{
 		font-size: 17px;
 		color: #212121;
 		display:flex;
 		align-items: center;
 		padding:10px;
 	}
 	.partTitle .idIcon{
		width: 25px;
		margin-right: 5px;
 	}
 	.partTitle .idIcon img{
 		display: block;
 		width:100%;
 	}
 	.extendInfo .partTitle:first-child{
 		border-bottom: 1px solid #e6e6e6;
 	}
 	.Basic-Info{
 		font-size:17px;
 		color:#212121;
 		padding-left:10px;
 		line-height:2em;
 	}
 	.Basic-Info div{
 		padding-right: 10px;
 		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
 	}
 	.Basic-Info span{
 		color:#a8a8a8;
 	}
	.spliceLine{
		padding: 10px 0 10px 10px;
	}
	.spliceLine div{
		border-bottom: 1px solid #e6e6e6;
	}
	.extendInfo .content{
		padding:0 10px;
		color:#7a7a7a;
		line-height:1.7em;
	}
	.extendInfo .content img{
		display: block;
		width:100%;
		margin:10px 0;
	}
 </style>
@endsection

@section('content')
	<div class="extendDetail">
		<div class="extendName">
			<div class="pic"></div>

			<div class="name">{!! $expert->name !!}</div>
		</div>
		<div class="extendInfo">
			<div class="">
				<div class="partTitle"><div class="idIcon"><img src="{{ $expert->image }}" alt=""></div><div>基本信息</div></div>
				<div class="Basic-Info">
					<div>电话：<span>{!! $expert->tel !!}</span></div>
					<div>籍贯：<span>{!! $expert->jiguan !!}</span></div>
					<div>退休单位：<span>{!! $expert->re_unit !!}</span></div>

				</div>

				<div class="spliceLine">
					<div></div>
				</div>
				<div class="partTitle">工作履历</div>

				<div class="content">{!! $expert->work_exp !!}</div>

				<div class="spliceLine">
					<div></div>
				</div>
				<div class="partTitle">研究成果</div>

				<div class="content"> 
					{!! $expert->res_result !!}

				</div>
			</div>
	  	</div>
	</div>
@endsection


@section('js')

@endsection
