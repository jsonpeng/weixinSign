<!-- Name Field -->
<div class="form-group col-sm-8">
    {!! Form::label('name', '分类名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

@if(count($cats) && $type == '课程班')
    <!-- Pid Field -->
    <div class="form-group col-sm-8">
        {!! Form::label('pid', '上级分类:') !!}
        <select name="pid" class="form-control">
                    <option value="0" @if(isset($courseCat) && !$courseCat->pid) selected="selected" @endif>无</option>
            @foreach ($cats as $val)
                <option value="{!! $val->id !!}" @if($val->disabled) disabled="true" @endif  @if($val->selected) selected="selected" @endif>{!! $val->name !!}</option>
            @endforeach
        </select>
    </div>
@endif

@if(!isset($courseCat)  ||  isset($courseCat) && !$courseCat->pid)
    <!-- Image Field -->
    <div class="form-group col-sm-8" id="course_cat_image">
        {!! Form::label('image', '分类图片:') !!}
           <div class="input-append">
                        {!! Form::text('image', null, ['class' => 'form-control', 'id' => 'image']) !!}
                        <a data-toggle="modal" href="javascript:;" data-target="#myModal" class="btn" type="button" onclick="changeImageId('image')">选择图片</a>
                        <img src="@if(isset($courseCat)) {{$courseCat->image}} @endif" style="max-width: 100%; max-height: 150px; display: block;">
          </div>
    </div>
@endif

<!-- Content Field -->
<div class="form-group col-sm-8">
    {!! Form::label('content', '分类描述:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
</div>

<input type="hidden" name="type" value="{!! $type !!}" />

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('courseCats.index',$type) !!}" class="btn btn-default">返回</a>
</div>
