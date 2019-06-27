<table class="table table-responsive" id="courses-table">
    <thead>
        <tr>
        <th>编码</th>
        <th>课程名称</th>
        {{-- <th>前端展示状态</th> --}}
        <th>课程开放状态</th>
        <th>内部员工价格</th>
        <th>普通价格</th>
        <th>@if(app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'兴趣小组') || app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'活动')) 活动 @else 招生 @endif人数</th>
        <th>当前参与</th>
        @if(!app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'兴趣小组') && !app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'活动'))
        <th>课程截止时间</th>
        @else
        <th>活动开展时间</th>
        @endif
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($courses as $course)
        <tr>
            <td>{!! $course->code !!}</td>
            <td>{!! $course->name !!}</td>
            {{-- <td>{!! $course->Shows !!}</td> --}}
            <td>{!! $course->Open  !!}</td>
            <td>{!! $course->inside_price !!}</td>
            <td>{!! $course->price !!}</td>
            <td>{!! $course->max_num !!}</td>
            <td>{!! $course->NowJoin >= $course->max_num ? tag($course->NowJoin,'red',false) : $course->NowJoin !!}</td>
            <td>  @if(!app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'兴趣小组') && !app('zcjy')->CourseCatRepo()->varifyCatIdType($cat->id,'活动')) {!! $course->course_end_time !!} @else {!! $course->activity_time !!} @endif</td>
            <td>
                
                <div class='btn-group'>

                   
                    <a href="{!! route('courses.edit', [$cat->id,$course->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>

                    <form method="post" action="/zcjy/courses_open/{!! $cat->id !!}/{!! $course->id !!}">

                        <input name="_token" type="hidden" value="{{ csrf_token() }}">

                        @if($course->open_status == '开放')
                            {!! Form::button('关闭', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定关闭课程开放状态吗?关闭后课程将无法继续报名并且在前端也无法显示')"]) !!}
                        @else
                            {!! Form::button('开放', ['type' => 'submit', 'class' => 'btn btn-success btn-xs', 'onclick' => "return confirm('确定打开课程开放状态吗?')"]) !!}
                        @endif

                    </form>
                 
                    <form method="post" action="/zcjy/courses_action/{!! $cat->id !!}/{!! $course->id !!}">

                        <input name="_token" type="hidden" value="{{ csrf_token() }}">

                        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'button', 'class' => 'btn btn-danger btn-xs', 'onclick' => "deleteAction(this)"]) !!}

                    </form>
                  
                </div>
              
            </td>
        </tr>
    @endforeach
    </tbody>
</table>