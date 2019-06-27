<table class="table table-responsive" id="classrooms-table">
    <thead>
        <tr>
        <th>教室名称</th>
{{--         <th>Location</th> --}}
        <th>教室地址</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($classrooms as $classroom)
        <tr>
            <td>{!! $classroom->name !!}</td>
        {{--     <td>{!! $classroom->location !!}</td> --}}
            <td>{!! $classroom->address !!}</td>
            <td>
                {!! Form::open(['route' => ['classrooms.destroy', $classroom->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
   {{--                  <a href="{!! route('classrooms.show', [$classroom->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('classrooms.edit', [$classroom->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>