<table class="table table-responsive" id="courseJoins-table">
    <thead>
        <tr>
        <th>报名人姓名</th>
        <th>报名人微信昵称</th>
        <th>参与年</th>
        <th>参与季度</th>
        <th>课程价格</th>
        <th>课程所属分类</th>
        <th>课程名称</th>
        {{-- <th>课程描述</th> --}}
        <th>类型</th>
        <th>参与状态</th>
        <th>订单状态</th>
        <th>支付状态</th>
        <th>加入时间</th>
            {{-- <th colspan="3">Action</th> --}}
        </tr>
    </thead>
    <tbody>
    @foreach($courseJoins as $courseJoin)
        <tr>
            <td>{!! a_link(optional($courseJoin->user)->name) !!}</td>
            <td>{!! a_link(optional($courseJoin->user)->nickname) !!}</td>
            <td>{!! $courseJoin->join_year !!}</td>
            <td>{!! $courseJoin->join_quarter == '1' ? '春季' : '秋季' !!}</td>
            {{-- <td>{!! $courseJoin->course_id !!}</td> --}}
            <td>{!! $courseJoin->price !!}</td>
            <td>{!! optional($courseJoin->course)->cat_name !!}</td>
            <td>{!! $courseJoin->course_name !!}</td>
            {{-- <td>{!! $courseJoin->course_des !!}</td> --}}
            {{-- <td>{!! $courseJoin->order_id !!}</td> --}}
            <td>{!! $courseJoin->type !!}</td>
            <td>{!! $courseJoin->join_status != '超额参与' ? $courseJoin->join_status : tag($courseJoin->join_status,'red',false) !!}</td>
            <td>{!! $courseJoin->order_id ? '已加入订单' : '未加入订单' !!}</td>
            <td>@if(isset($courseJoin->order)) {!! $courseJoin->order->pay_status !!}  @else -- @endif</td>
            <td>{!! $courseJoin->created_at !!}</td>
   {{--          <td>
                {!! Form::open(['route' => ['courseJoins.destroy', $courseJoin->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('courseJoins.show', [$courseJoin->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('courseJoins.edit', [$courseJoin->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td> --}}
        </tr>
        @if(!isset($courseJoin->order) && $courseJoin->order_id)
            <?php $orders = app('zcjy')->OrderRepo()->userSuccessPayOrders($courseJoin->user_id); ?>
            @if(count($orders))
                <td>&nbsp;&nbsp;&nbsp;&nbsp;用户问题订单:</td>
                @foreach ($orders as $order)
                    <td>{!! a_link($order->number.'('.$order->pay_platform.')',route('orders.show', [$order->id])) !!}</td>
                    <td><button type="button" class="btn btn-xs btn-default seachOrder" data-id="{!! $order->number !!}" data-platform="{!! $order->pay_platform !!}" data-time="{!! $order->updated_at !!}">核对{!! $order->pay_platform !!}账单</button></td>
                    @if(count($orders) == 1)
                        <td><button type="button" class="btn btn-xs btn-default actionOrder" data-id="{!! $courseJoin->id !!}" data-orderid="{!! $order->id !!}">自动纠错处理</button></td>
                    @endif
                @endforeach
            @endif
        @endif
    @endforeach
    </tbody>
</table>