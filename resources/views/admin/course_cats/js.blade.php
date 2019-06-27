@section('scripts')
<script type="text/javascript">
	$('select[name=pid]').change(function(){
		if(parseInt($(this).val()) == 0)
		{
			$('#course_cat_image').show();
		}
		else{
			$('#course_cat_image').hide();
		}
	});
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

	function deleteAction(obj,type = 'parent')
	{
		var text = type == 'parent' ? '确定删除吗,删除后子分类也会一并删除,请注意删除后将无法恢复!' : '确定删除吗,请注意删除后将无法恢复!';
		if(confirm(text))
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