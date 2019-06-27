<table class="table table-responsive" id="users-table">
    <thead>
        <tr>
        @if(Request::get('type') != '单位内部用户')
        <th>头像</th>
        @endif
        <th>姓名</th>
        @if(Request::get('type') != '单位内部用户')
        <th>微信昵称</th>
        @endif
        <th>出生日期</th>
        <th>身份证号</th>
        <th>用户类型</th>
        <th>手机号</th>
        <th>退休单位</th>
        <th>查看专家权限</th>
        <th>注册导入状态</th>
        <th>创建时间</th>
        
        <th colspan="3">操作</th>
    
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)

        <tr>
            @if(Request::get('type') != '单位内部用户')
            <td><img src="{!! $user->head_image !!}"  style="max-width: 100%;height: 80px;"/></td>
            @endif
            <td>{!! $user->name !!}</td>
            @if(Request::get('type') != '单位内部用户')
            <td>{!! $user->nickname !!}</td>
            @endif
            <td>{!! $user->birthday !!}</td>
            <td>{!! $user->idcard_num !!}</td>
            <td>{!! $user->type !!}</td>
            <td>{!! $user->mobile !!}</td>
            <td>{!! $user->ret_unit !!}</td>
            <td>{!! $user->can_read_zj ? '可查看' : '不可查看' !!}</td>
            <td>{!! $user->import_status == '未导入' ? '未绑定' : '已绑定' !!}</td>
            <td>{!! $user->created_at !!}</td>
            <td>
            @if(Request::get('type') != '单位内部用户')
                <div class='btn-group'>
             
                     <a href="{!! route('users.show', [$user->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>

                    <form action="/zcjy/user/{!! $user->id !!}/update" method="post">
                         <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                         @if($user->type == '单位内部用户')
                            <input type="hidden" name="type" value="普通用户" />
                            <input type="hidden" name="import_status" value="未导入" />
                            <a href="javascript:" onclick="$(this).parent().submit();" class="btn btn-default btn-xs">取消单位内部用户</a>
                         @else
                            <input type="hidden" name="type" value="单位内部用户" />
                            <input type="hidden" name="import_status" value="已导入" />
                            <a href="javascript:" onclick="$(this).parent().submit();" class="btn btn-default btn-xs">设置为单位内部用户</a>
                     @endif
                    </form>

                    <form action="/zcjy/user/{!! $user->id !!}/update_zj" method="post">
                         <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                         <a href="javascript:" onclick="$(this).parent().submit();" class="btn btn-default btn-xs">
                            @if($user->can_read_zj)
                            设置为不可查看专家
                            @else
                            设置为可查看专家
                            @endif
                        </a>
                    </form>

                    <form action="/zcjy/user/{!! $user->id !!}/update" method="post">
                         <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                         <input type="hidden" name="_reset" value="1" />
                         <input type="hidden" name="type" value="普通用户" />
                         <a href="javascript:" onclick="$(this).parent().submit();" class="btn btn-default btn-xs">重置注册信息</a>
                    </form>

                     <a href="javascript:;" class="btn btn-default btn-xs informMessage" data-id="{!! $user->id !!}">推送微信通知</a>

                </div>
            @else
                 <div class='btn-group'>
                       
                        <form action="/zcjy/user/{!! $user->id !!}/delete" method="post">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}" />
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('确定要删除吗?')"]) !!}
                        </form>

                 </div>
            @endif

            </td>
        </tr>
    @endforeach
    </tbody>
</table>