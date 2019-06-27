<table class="table table-responsive" id="experts-table">
    <thead>
        <tr>
        <th>专家名称</th>
        <th>图像</th>
        <th>电话</th>
        <th>籍贯</th>
        <th>退休单位</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($experts as $expert)
        <tr>
            <td>{!! $expert->name !!}</td>
            <td><img src="{!! $expert->image !!}" style="max-width: 100px;height: auto;" /></td>
            <td>{!! $expert->tel !!}</td>
            <td>{!! $expert->jiguan !!}</td>
            <td>{!! $expert->re_unit !!}</td>
         {{--    <td>{!! $expert->work_exp !!}</td>
            <td>{!! $expert->res_result !!}</td> --}}
            <td>
                {!! Form::open(['route' => ['experts.destroy', $expert->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    {{-- <a href="{!! route('experts.show', [$expert->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('experts.edit', [$expert->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>