<table class="table table-responsive" id="orders-table">
    <thead>
        <tr>
        {{-- <th>订单编号</th> --}}
        <th>订单金额</th>
        <th>下单购买人姓名</th>
        <th>下单购买人微信昵称</th>
        <th>支付平台</th>
        <th>支付状态</th>
        <th>备注说明</th>
        <th>购买时间</th>
            <th colspan="3">操作</th>
        </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            {{-- <td>{!! $order->number !!}</td> --}}
            <td>{!! $order->price !!}</td>
            <td>{!! optional($order->user)->name !!}</td>
            <td>{!! optional($order->user)->nickname !!}</td>
            <td>{!! $order->pay_platform !!}</td>
            <td>{!! $order->pay_status !!}</td>
            <td>{!! $order->remark !!}</td>
            <td>{!! $order->created_at !!}</td>
            <td>
              
                <div class='btn-group'>
                    {!! Form::open(['route' => ['orders.destroy', $order->id], 'method' => 'delete']) !!}
                        <a href="{!! route('orders.show', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    {!! Form::close() !!}
                    @if($order->pay_status == '已支付')
                         {!! Form::model($order, ['route' => ['orders.update', $order->id], 'method' => 'patch']) !!}
                            <input type="hidden" name="pay_status" value="已取消" />
                            <button type="submit" onclick="return confirm('确定取消吗?取消后将无法恢复');" >取消订单</button>
                             {!! Form::close() !!}
                        </div>

                    @endif
           {{--          <a href="{!! route('orders.edit', [$order->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a> --}}
                 {{--    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!} --}}
                </div>
                
            </td>
        </tr>
    @endforeach
    </tbody>
</table>