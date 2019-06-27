@section('scripts')
<script type="text/javascript">
	$('.activity_time').datetimepicker({
        language: 'zh-CN',
		weekStart: 0,
		todayBtn: true,
		autoclose: true,
		todayHighlight: true,
		startView: 2,
		minView: 1,
		forceParse: 0,
		format:'yyyy-mm-dd hh:ii',
		clearBtn:true,
        minuteStep: 10,
    });

	$('.time_start,.time_end').attr('autoComplete','off');

	function timepicker(obj=null){
		if(obj == null){
			obj = $('.time_start,.time_end');
		}
		obj.datetimepicker({
				//方案1
                // format: 'HH:mm',
                // useCurrent: false,
                // showTodayButton: false,
                // showClear: false,
                // showClose: false,
                // locale: 'zh-cn'
                // 方案2
                language: 'zh-CN',
				weekStart: 0,
				todayBtn: true,
				autoclose: true,
				// todayHighlight: true,
				startView: 1,
				minView: 0,
				// forceParse: 0,
				format:'hh:ii',
				clearBtn:true,
				// startDate: '06:00',
	   //          endDate: '18:00',
	            minuteStep: 10,
				// 方案3
				    // language: 'zh-CN',
	       //          format: 'HH:ii',
	       //          startView: 1,
	       //          minView: 0,
	       //          // viewSelect: 0,
	       //          // pickDate: false,
	       //          startDate: '06:00',
	       //          endDate: '18:00',
	       //          minuteStep: 10,
	       //          autoclose: 1,
	       //          todayBtn: true,
	       //          todayHighlight: true,
	       //          clearBtn:true
     });	
   }

  
   var time_val; 
   $(document).on('mouseover','.time_start,.time_end',function(e){
   			time_val = $(this).val();
   			$(this).val('');
   	 		if(!$(this).hasClass('picker')){
   	 			timepicker($(this));
   	 			// $(this).trigger('click');
   	 			$(this).addClass('picker');
   	 			// $(this).click();
   	 		}
   }).on('mouseleave','.time_start,.time_end',function(e){
   		$(this).val(time_val);
   });
  

	//添加安排
	$('.add_plan').click(function(){
		if($('.first_tr_plan:eq(0)').hasClass('zcjy_hidden')){
			$('.first_tr_plan:eq(0)').removeClass('zcjy_hidden');
		}
		else{
		var dom =	$('#plans_tbody').append($('.first_tr_plan').prop("outerHTML"));
		dom.find('.time_start,.time_end').removeClass('picker');
		}
	});

	function deleteAttach(obj){
		var parent = $(obj).parent().parent();
		if($('.first_tr_plan').length > 1){
			parent.remove();
		}
		else{
			parent.addClass('zcjy_hidden');
			parent.find('input').val('');
		}
	}

	function generateRandStr(n=10)
	{
		var chars =['0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G'];
		var res = '';
		for (var i = 0; i < n; i++) 
		{
			var random_num = Math.ceil(Math.random()*16);
			res += chars[random_num];
		}
		return res;
	}

	function deleteAction(obj)
	{
		if(confirm('确定删除吗,删除后报名用户将不会收到上课消息,课程表内的课程也不会显示'))
		{
			 var str  = generateRandStr(6);
			 var name = prompt("请输入 "+str+" 后确定删除");
			 if(name == str)
			 {
			 	$(obj).parent().submit();
			 }
			 else
			 {
			 	alert('输入错误,请重新操作!');
			 }

		}
	}

</script>
@endsection