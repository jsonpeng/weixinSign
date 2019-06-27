<!-- Name Field -->
<div class="form-group col-sm-12">
    {!! Form::label('name', '专家名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Image Field -->
<div class="form-group col-sm-12">
    {!! Form::label('image', '图像:') !!}
    <div class="input-append">
            {!! Form::text('image', null, ['class' => 'form-control', 'id' => 'image']) !!}
            <a data-toggle="modal" href="javascript:;" data-target="#myModal" class="btn" type="button" onclick="changeImageId('image')">选择图片</a>
            <img src="@if(isset($expert)) {{$expert->image}} @endif" style="max-width: 100%; max-height: 150px; display: block;">
    </div>
</div>

<!-- Tel Field -->
<div class="form-group col-sm-12">
    {!! Form::label('tel', '电话:') !!}
    {!! Form::text('tel', null, ['class' => 'form-control']) !!}
</div>

<!-- Jiguan Field -->
<div class="form-group col-sm-12">
    {!! Form::label('jiguan', '籍贯:') !!}
    {!! Form::text('jiguan', null, ['class' => 'form-control']) !!}
</div>

<!-- Re Unit Field -->
<div class="form-group col-sm-12">
    {!! Form::label('re_unit', '退休单位:') !!}
    {!! Form::text('re_unit', null, ['class' => 'form-control']) !!}
</div>

<!-- Work Exp Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('work_exp', '工作履历:') !!}
    {!! Form::textarea('work_exp', null, ['class' => 'form-control']) !!}
</div>

<!-- Res Result Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('res_result', '研究成果:') !!}
    {!! Form::textarea('res_result', null, ['class' => 'form-control intro']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('experts.index') !!}" class="btn btn-default">返回</a>
</div>
