<?php 
$cat_show = $type == '课程班';
?>
<table class="table table-responsive" id="courseCats-table">
    <thead>
        <tr>
        <th>分类名称</th>
         @if($cat_show)
            {{-- <th>前端展示状态</th> --}}
            <th>分类图</th>
         @endif
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($courseCats as $courseCat)
        <?php $child_cats = $courseCat->child_cats;?>

       
            <tr>
                <td>{!! tag('[大类班]').$courseCat->name !!}</td>
                @if($cat_show)
                {{-- <td>{!! $courseCat->ShowS !!}</td> --}}
                <td><img src="{!! $courseCat->image !!}" style="max-width: 80px;height: auto;" /></td>
                @endif
                <td>
               
                    <div class='btn-group'>
                   {{--      <a href="{!! route('courseCats.show', [$courseCat->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a> --}}
                         @if($type != '课程班')
                            @if(!count($child_cats))<a href="{!! route('courses.index',$courseCat->id) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-plus"></i>管理{!! $type !!}</a>@endif
                         @endif

                        @if($cat_show)
                            <a href="{!! route('courseCats.edit', [$courseCat->id,$type]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                               {!! Form::model($courseCat, ['route' => ['courseCats.update', $courseCat->id,$type], 'method' => 'patch']) !!}
                                    @if($courseCat->show)
                                        <a class="btn btn-danger btn-xs" href="javascript:;" onclick="deleteAction(this,'parent')">
                                        删除</a>
                                        <input type="hidden" name="show" value="0" />
                                        <input type="hidden" name="name" value="{!! $courseCat->name !!}" />
                                    @endif
                                {!! Form::close() !!}
                        @endif
                 {{--        {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?删除后对应课程记录都将删除')"]) !!} --}}
                    </div>
                   
                </td>
            </tr>
        

        @if(count($child_cats))
            @foreach($child_cats as $cat)
             @if($cat->show)
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;{!! tag('[小类班]').$cat->name !!}</td>
                    {{-- <td>{!! $cat->ShowS !!}</td> --}}
                    {{-- <td><img src="{!! $cat->image !!}" style="max-width: 80px;height: auto;" /></td> --}}
                    <td> </td>
                    <td>
               
                        <div class='btn-group'>
                            <a href="{!! route('courses.index',$cat->id) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-plus"></i>管理{!! $type !!}</a>
                            <a href="{!! route('courseCats.edit', [$cat->id,$type]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                        {!! Form::model($cat, ['route' => ['courseCats.update', $cat->id,$type], 'method' => 'patch']) !!}
                            @if($cat->show)
                                <a class="btn btn-danger btn-xs" href="javascript:;" onclick="deleteAction(this,'child')">
                                删除</a>
                                <input type="hidden" name="show" value="0" />
                                <input type="hidden" name="name" value="{!! $cat->name !!}" />
                            @endif
                        {!! Form::close() !!}
             {{--                {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定删除吗?删除后对应课程记录都将删除')"]) !!} --}}
                        </div>
                   
                    </td>
                </tr>
             @endif
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>