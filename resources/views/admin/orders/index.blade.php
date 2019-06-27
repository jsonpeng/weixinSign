@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">订单列表</h1>
     {{--    <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('orders.create') !!}">Add New</a>
        </h1> --}}
          <a style="margin-top: 5px;display: inline;margin-left: 15px;" class="btn btn-danger" href="javascript:$('.reportForm').submit();">导出订单及参与记录</a>
        {!! Form::open(['route' => ['orders.reports'],'class'=>'reportForm']) !!}
        {!! Form::close() !!}
    </section>
    <div class="content">
        <div class="clearfix"></div>

        <div class="box box-primary">
            <form >
                <div class="box-body">
                    
                        <div class="form-group col-md-2">
                            <label>下单购买人姓名</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="" @if (array_key_exists('name', $input)) value="{{$input['name']}}"@endif >
                        </div> 

                        <div class="form-group col-md-2">
                            <label>下单统一订单号</label>
                            <input type="text" class="form-control" name="number" id="number" placeholder="" @if (array_key_exists('number', $input)) value="{{$input['number']}}"@endif >
                        </div>

                        <div class="form-group col-md-1">
                            <label>支付平台</label>
                            <select class="form-control" name="pay_platform">
                                <option value="" @if (!array_key_exists('pay_platform', $input) || isset($input['pay_platform']) && $input['pay_platform'] == '') selected="selected" @endif>全部</option>
                                <option value="微信" @if (array_key_exists('pay_platform', $input) && $input['pay_platform'] == '微信') selected="selected" @endif>微信</option>
                                <option value="支付宝" @if (array_key_exists('pay_platform', $input) && $input['pay_platform'] == '支付宝') selected="selected" @endif>支付宝</option>
                            </select>
                        </div>

                        <div class="form-group col-md-1">
                            <label>支付状态</label>
                            <select class="form-control" name="pay_status">
                                <option value="" @if (!array_key_exists('pay_status', $input) || isset($input['pay_status']) && $input['pay_status'] == '') selected="selected" @endif>全部</option>
                                <option value="已支付" @if (array_key_exists('pay_status', $input) && $input['pay_status'] == '已支付') selected="selected" @endif>已支付</option>
                                <option value="未支付" @if (array_key_exists('pay_status', $input) && $input['pay_status'] == '未支付') selected="selected" @endif>未支付</option>
                            </select>
                        </div>

                        <div class="form-group col-md-1">
                            <label>操作</label>
                            <button type="submit" class="btn btn-primary pull-right form-control">查询</button>

                        </div>

                
              
                </div>
            </form>
        </div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.orders.table')
            </div>
        </div>
        <div class="text-center">
            {!! $orders->appends($input)->links() !!}
        </div>
    </div>
@endsection

