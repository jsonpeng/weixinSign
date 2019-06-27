<!-- Name Field -->
<div class="form-group col-sm-8">
    {!! Form::label('name', '课程名称:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Cat Name Field -->
<div class="form-group col-sm-8">
    {!! Form::label('cat_name', '课程分类名称:') !!}
    {!! Form::text('cat_name', $cat->name, ['class' => 'form-control','readonly'=>'readonly']) !!}
</div>


{!! Form::hidden('cat_id', $cat->id, ['class' => 'form-control']) !!}

@if(app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'课程班'))
    <div class="form-group col-sm-8">
        {!! Form::label('brief', '简介:') !!}

        {!! Form::textarea('brief', null, ['class' => 'form-control']) !!}

    </div>
@endif

<!-- Content Field -->
<div class="form-group col-sm-8">
    {!! Form::label('content', '内容描述:') !!}
    @if(app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'兴趣小组') || app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'活动'))
        {!! Form::textarea('content', null, ['class' => 'form-control intro']) !!}
    @else
       {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
    @endif
</div>

<!-- Brief Field -->
{{-- <div class="form-group col-sm-8">
    {!! Form::label('brief', '简介:') !!}
    {!! Form::textarea('brief', null, ['class' => 'form-control']) !!}
</div> --}}

<div class="form-group col-sm-8">
    {!! Form::label('code', '编码:') !!}
    {!! Form::text('code', null, ['class' => 'form-control']) !!}
</div>

<!-- Inside Price Field -->
<div class="form-group col-sm-8">
    {!! Form::label('inside_price', '内部员工价格:') !!}
    {!! Form::text('inside_price', null, ['class' => 'form-control']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-8">
    {!! Form::label('price', '普通价格:') !!}
    {!! Form::text('price', null, ['class' => 'form-control']) !!}
</div>

<!-- Max Num Field -->
<div class="form-group col-sm-8">
    @if(app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'兴趣小组') || app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'活动')) 
     {!! Form::label('max_num', '活动人数:') !!} 
    @else
     {!! Form::label('max_num', '招生人数:') !!}
    @endif
     {!! Form::text('max_num', null, ['class' => 'form-control']) !!}
</div>

<!--如果是兴趣小组或者活动-->
@if(app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'兴趣小组') || app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'活动'))

    <div class="form-group col-sm-8">
        {!! Form::label('activity_time', '开展时间:') !!}
        {!! Form::text('activity_time', null, ['class' => 'form-control activity_time','autoComplete'=>'off']) !!}
    </div>

    <div class="form-group col-sm-8">
        {!! Form::label('sign_time', '报名时间(起):') !!}
        {!! Form::text('sign_time', null, ['class' => 'form-control activity_time','autoComplete'=>'off']) !!}
    </div>

    <div class="form-group col-sm-8">
        {!! Form::label('sign_time_end', '报名时间(止):') !!}
        {!! Form::text('sign_time_end', null, ['class' => 'form-control activity_time','autoComplete'=>'off']) !!}
    </div>

@else

<div class="form-group col-sm-8">
        {!! Form::label('course_end_time', '课程截止时间(截止时间过后用户报名记录失效并且课程开放状态会自动关闭):') !!}
        @if(isset($course))
        {!! Form::text('course_end_time', null, ['class' => 'form-control activity_time','autoComplete'=>'off']) !!}
        @else
        {!! Form::text('course_end_time', endYear(), ['class' => 'form-control activity_time','autoComplete'=>'off']) !!}
        @endif

</div>

<div class="form-group col-sm-12">

    <a href="javascript:;" class="add_plan">添加安排</a>
    <?php $weekdays = WeekDays(); ?>
    <table class="table table-responsive" id="plans-table">
        <thead>
            <th>教室</th>
            <th>课程星期安排</th>
            <th>老师</th>
            <th>开始时间</th>
            <th>结束时间</th>
            <th>操作</th>
        </thead>

        <tbody id="plans_tbody">
            
            @if(!isset($attachs))
                <tr  class="first_tr_plan zcjy_hidden" >
                    <td>
                        <select name="classroom_name[]" class="form-control">
                            @foreach($allRooms as $item) 
                            <option value="{!! $item->name !!}">{!! $item->name !!}</option> 
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="weekday[]" class="form-control">
                            @foreach($weekdays as $item) 
                            <option value="{!! $item !!}">{!! $item !!}</option> 
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" name="teacher_name[]" class="form-control" />
                    </td>
                    <td>
                        <input type="text" name="start_time[]" class="form-control time_start" />
                    </td>
                    <td>
                        <input type="text" name="end_time[]" class="form-control time_end" />
                    </td>
                    <td>
                        <button class="btn btn-danger btn-xs" type="button" onclick="deleteAttach(this)"><i class="glyphicon glyphicon-trash"></i>删除</button>
                    </td>
                </tr>
            @else
                @foreach($attachs as $attach)
                    <tr  class="first_tr_plan " >
                        <td>
                            <select name="classroom_name[]" class="form-control">
                                @foreach($allRooms as $item) 
                                <option value="{!! $item->name !!}" @if($attach->classroom_name == $item->name) selected="selected" @endif>{!! $item->name !!}</option> 
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="weekday[]" class="form-control">
                                @foreach($weekdays as $item) 
                                <option value="{!! $item !!}" @if($attach->weekday == $item) selected="selected" @endif>{!! $item !!}</option> 
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="teacher_name[]" value="{!! $attach->teacher_name !!}" class="form-control" />
                        </td>
                        <td>
                            <input type="text" name="start_time[]" value="{!! $attach->start_time !!}" class="form-control time_start" />
                        </td>
                        <td>
                            <input type="text" name="end_time[]" value="{!! $attach->end_time !!}" class="form-control time_end" />
                        </td>
                        <td>
                            <button class="btn btn-danger btn-xs" type="button" onclick="deleteAttach(this)"><i class="glyphicon glyphicon-trash"></i>删除</button>
                        </td>
                    </tr>
                @endforeach
            @endif

        </tbody>
    </table>

</div>
@endif





<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('保存', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('courses.index',$cat->id) !!}" class="btn btn-default">返回</a>
</div>
