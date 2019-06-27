@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            订单详情
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('admin.orders.show_fields')
                    <a href="{!! route('orders.index') !!}" class="btn btn-default">返回</a>
                </div>

                <?php $joins = $order->joins;?>

    

            </div>

                <table class="table table-responsive" id="orders-table">
                    <thead>
                        <tr>
                        <th>购买课程</th>
                        <th>课程金额</th>
                        <th>课程描述</th>
                        <th>类型</th>
                       
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($joins as $join)
                        <tr>
                            <td>{!! $join->course_name !!}</td>
                            <td>{!! $join->price !!}</td>
                            <td>{!! $join->course_des !!}</td>
                            <td>{!! $join->type !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection
