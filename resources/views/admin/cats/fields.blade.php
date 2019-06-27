<!-- Name Field -->
<div class="form-group col-sm-8">
    {!! Form::label('name', '分类名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Sort Field -->
<div class="form-group col-sm-8">
    {!! Form::label('sort', '排序权重(越高显示越靠前):') !!}
    {!! Form::text('sort', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('cats.index') !!}" class="btn btn-default">返回</a>
</div>
