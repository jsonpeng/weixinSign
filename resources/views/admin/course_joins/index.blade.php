@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">课程参与记录</h1>
 {{--        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('courseJoins.create') !!}">添加</a>
        </h1> --}}
    </section>
    <div class="content">
        <div class="clearfix"></div>
        
        <div class="box box-primary">
            <div class="box-body">
                 {!! Form::open(['route' => ['orders.reports'],'class'=>'reportForm']) !!} 

                    <div class="form-group col-md-2">
                        <label>报名人姓名</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="" @if (array_key_exists('name', $input)) value="{{$input['name']}}"@endif >
                    </div>

                    <div class="form-group col-md-2">
                        <label>课程名称</label>
                        <input type="text" class="form-control" name="course_name" id="course_name" placeholder="" @if (array_key_exists('course_name', $input)) value="{{$input['course_name']}}"@endif >
                    </div>

                    <div class="form-group col-md-1">
                        <label>参与年</label>
                        <select class="form-control" name="join_year">
                            <option value="" @if (!array_key_exists('join_year', $input) || isset($input['join_year']) && $input['join_year'] == '') selected="selected" @endif>全部</option>

                            <option value="2019" @if (array_key_exists('join_year', $input) && $input['join_year'] == '2019') selected="selected" @endif>2019</option>
                            <option value="2020" @if (array_key_exists('join_year', $input) && $input['join_year'] == '2020') selected="selected" @endif>2020</option>
                            <option value="2021" @if (array_key_exists('join_year', $input) && $input['join_year'] == '2021') selected="selected" @endif>2021</option>
                            <option value="2022" @if (array_key_exists('join_year', $input) && $input['join_year'] == '2022') selected="selected" @endif>2022</option>
                            <option value="2023" @if (array_key_exists('join_year', $input) && $input['join_year'] == '2023') selected="selected" @endif>2023</option>
                            <option value="2024" @if (array_key_exists('join_year', $input) && $input['join_year'] == '2024') selected="selected" @endif>2024</option>
                            <option value="2025" @if (array_key_exists('join_year', $input) && $input['join_year'] == '2025') selected="selected" @endif>2025</option>
                         
                        </select>
                    </div>

                    <div class="form-group col-md-1">
                        <label>参与季度</label>
                        <select class="form-control" name="join_quarter">
                            <option value="" @if (!array_key_exists('join_quarter', $input) || isset($input['join_quarter']) && $input['join_quarter'] == '') selected="selected" @endif>全部</option>
                            <option value="1" @if (array_key_exists('join_quarter', $input) && $input['join_quarter'] == '1') selected="selected" @endif>春季</option>
                            <option value="2" @if (array_key_exists('join_quarter', $input) && $input['join_quarter'] == '2') selected="selected" @endif>秋季</option>
                        </select>
                    </div>

                    <div class="form-group col-md-1">
                        <label>类型</label>
                        <select class="form-control" name="type">
                            <option value="" @if (!array_key_exists('type', $input) || isset($input['type']) && $input['type'] == '') selected="selected" @endif>全部</option>
                            <option value="内部员工优惠" @if (array_key_exists('type', $input) && $input['type'] == '内部员工优惠') selected="selected" @endif>内部员工优惠</option>
                            <option value="普通" @if (array_key_exists('type', $input) && $input['type'] == '普通') selected="selected" @endif>普通</option>
                        </select>
                    </div>

                    <div class="form-group col-md-1">
                        <label>参与状态</label>
                        <select class="form-control" name="join_status">
                            <option value="" @if (!array_key_exists('join_status', $input) || isset($input['join_status']) && $input['join_status'] == '') selected="selected" @endif>全部</option>
                            <option value="正常参与" @if (array_key_exists('join_status', $input) && $input['join_status'] == '正常参与') selected="selected" @endif>正常参与</option>
                            <option value="超额参与" @if (array_key_exists('join_status', $input) && $input['join_status'] == '超额参与') selected="selected" @endif>超额参与</option>
                        </select>
                    </div>

                    <div class="form-group col-md-1">
                        <label>订单状态</label>
                        <select class="form-control" name="order_status">
                            <option value="" @if (!array_key_exists('order_status', $input) || isset($input['order_status']) && $input['order_status'] == '') selected="selected" @endif>全部</option>
                            <option value="未加入订单" @if (array_key_exists('order_status', $input) && $input['order_status'] == '未加入订单') selected="selected" @endif>未加入订单</option>
                            <option value="已加入订单" @if (array_key_exists('order_status', $input) && $input['order_status'] == '已加入订单') selected="selected" @endif>已加入订单</option>
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
                        <button type="button" class="btn btn-primary pull-right form-control" onclick="formSubmit()">查询</button>

                    </div>

                    <div class="form-group col-md-1">
                         <label>导出</label>
                         <button type="button" class="btn btn-primary pull-right form-control" onclick="formSubmit('POST')">导出</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.course_joins.table')
            </div>
        </div>
        <div class="text-center">
            {!! $courseJoins->appends($input)->links() !!}
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    function formSubmit(method = 'GET')
    {
        var actionUrl = "";
        if(method == 'POST')
        {
            actionUrl = '{!! route('courseJoins.reports') !!}';
        }
        $('form').attr('method',method);
        $('form').attr('action',actionUrl);
        $('form').submit();
    }
    var platform,out_trade_no,date;
    //对账单
    $('.seachOrder').click(function(){
        platform = $(this).data('platform');
        out_trade_no = $(this).data('id');
        date = $(this).data('time');
        $.zcjyRequest('/ajax/check_order',function(res){
            if(res){
                alert('查询核对成功:'+res);
            }
        },{platform:platform,out_trade_no:out_trade_no,date:date},'POST');
    });

    //自动纠错处理
    $('.actionOrder').click(function(){
        var id = $(this).data('id');
        var orderid = $(this).data('orderid');
        $.zcjyRequest('/ajax/action_order/'+id,function(res){
            if(res){
                alert(res);
                // location.reload();
            }
        },{order_id:orderid},'POST');
    });
</script>
@endsection

