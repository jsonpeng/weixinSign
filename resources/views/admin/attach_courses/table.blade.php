<table class="table table-responsive" id="attachCourses-table">
    <thead>
        <tr>
            <th>Weekday</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Classroom Name</th>
        <th>Teacher Name</th>
        <th>Course Id</th>
            <th colspan="3">Action</th>
        </tr>
    </thead>
    <tbody>
    @foreach($attachCourses as $attachCourse)
        <tr>
            <td>{!! $attachCourse->weekday !!}</td>
            <td>{!! $attachCourse->start_time !!}</td>
            <td>{!! $attachCourse->end_time !!}</td>
            <td>{!! $attachCourse->classroom_name !!}</td>
            <td>{!! $attachCourse->teacher_name !!}</td>
            <td>{!! $attachCourse->course_id !!}</td>
            <td>
                {!! Form::open(['route' => ['attachCourses.destroy', $attachCourse->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('attachCourses.show', [$attachCourse->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('attachCourses.edit', [$attachCourse->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>