<table class="table table-responsive" id="cats-table">
    <thead>
        <tr>
        <th>文案分类名称</th>
        <th>排序权重</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($cats as $cat)
        <tr>
            <td>{!! $cat->name !!}</td>
            <td>{!! $cat->sort !!}</td>
            <td>
                {!! Form::open(['route' => ['cats.destroy', $cat->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
             {{--        <a href="{!! route('cats.show', [$cat->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                    <a href="{!! route('cats.edit', [$cat->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?删除后对应分类文案文章都会删除')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>