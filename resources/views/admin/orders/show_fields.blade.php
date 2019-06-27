<!-- Id Field -->
{{-- <div class="form-group">
    {!! Form::label('id', '订单Id:') !!}
    <p>{!! $order->id !!}</p>
</div> --}}
<div class="form-group">
    {!! Form::label('number', '订单号:') !!}
    <p>{!! $order->number !!}</p>
</div>

<!-- Price Field -->
<div class="form-group">
    {!! Form::label('price', '订单金额:') !!}
    <p>{!! $order->price !!}</p>
</div>

<!-- Number Field -->


<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', '购买人:') !!}
    <p>{!! optional($order->user)->nickname !!}</p>
</div>

<!-- Pay Platform Field -->
<div class="form-group">
    {!! Form::label('pay_platform', '支付平台:') !!}
    <p>{!! $order->pay_platform !!}</p>
</div>

<!-- Pay Status Field -->
<div class="form-group">
    {!! Form::label('pay_status', '支付状态:') !!}
    <p>{!! $order->pay_status !!}</p>
</div>

<!-- Remark Field -->
<div class="form-group">
    {!! Form::label('remark', '备注:') !!}
    <p>{!! $order->remark !!}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', '创建时间:') !!}
    <p>{!! $order->created_at !!}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', '更新时间:') !!}
    <p>{!! $order->updated_at !!}</p>
</div>

