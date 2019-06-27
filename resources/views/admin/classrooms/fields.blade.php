<!-- Name Field -->
<div class="form-group col-sm-8">
    {!! Form::label('name', '教室名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Location Field -->
{{-- <div class="form-group col-sm-8">
    {!! Form::label('location', 'Location:') !!}
    {!! Form::text('location', null, ['class' => 'form-control']) !!}
</div> --}}

<!-- Address Field -->
<div class="form-group col-sm-8">
    {!! Form::label('address', '教室地址:') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('classrooms.index') !!}" class="btn btn-default">返回</a>
</div>
