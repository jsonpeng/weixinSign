@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('fullcalendar/dist/fullcalendar.min.css') }}">
<style type="text/css">
  .fc-day-grid-event .fc-content {
      text-align: center;
  }
</style>
@endsection

@section('content')
    <div class="container-fluid" style="padding: 30px 15px;">
        <!-- Content Header (Page header) -->
        <section class="content-header" style="padding-top: 0;">
          <h1>
            用户信息
          </h1>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" style="height: 100px;" src="{{ $user->head_image }}">

                  <h3 class="profile-username text-center">{{ $user->nickname }}</h3>


                  <p class="text-muted text-center">
                
                    
                </p>

                  <ul class="list-group list-group-unbordered">

            
                 
                  </ul>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->

              <!-- About Me Box -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">其他信息</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                          <b>姓名</b> <span class="pull-right">{{ $user->name }}</span>
                        </li>
                        <li class="list-group-item">
                          <b>注册时间</b> <span class="pull-right">{{ $user->created_at->format('Y-m-d') }}</span>
                        </li>
                        <li class="list-group-item">
                          <b>电话</b> <span class="pull-right">{{ $user->mobile }}</span>
                        </li>
                        <li class="list-group-item">
                          <b>最后活跃时间</b> <span class="pull-right">{{ $user->last_login }}</span>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">

                  <li class="active"><a href="#order_list" data-toggle="tab">报名记录</a></li>
                  <li ><a href="#shoucang_list" data-toggle="tab">收藏记录</a></li>
                  <li ><a href="#kecheng_list" data-toggle="tab">课程安排</a></li>

                </ul>
                <div class="tab-content">

                  <div class="active tab-pane" id="order_list">

                    <table class="table table-responsive" id="courseJoins-table">
                        <thead>
                            <tr>
                            <th>课程价格</th>
                            <th>课程所属分类</th>
                            <th>课程名称</th>
                     {{--        <th>课程描述</th> --}}
                            <th>类型</th>
                            <th>订单状态</th>
                            <th>支付状态</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($joins as $courseJoin)
                            <tr>
                                <td>{!! $courseJoin->price !!}</td>
                                <td>{!! optional($courseJoin->course)->cat_name !!}</td>
                                <td>{!! $courseJoin->course_name !!}</td>
                                {{-- <td>{!! $courseJoin->course_des !!}</td> --}}
                                {{-- <td>{!! $courseJoin->order_id !!}</td> --}}
                                <td>{!! $courseJoin->type !!}</td>
                                <td>{!! $courseJoin->order_id ? '已加入订单' : '未加入订单' !!}</td>
                                <td>@if(isset($courseJoin->order)) {!! $courseJoin->order->pay_status !!}  @else -- @endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="text-center">
                      {!! $joins->links() !!}
                    </div>

                  </div>

               
                      <div class="tab-pane" id="shoucang_list">
                                 <table class="table table-responsive" id="courses-table">
                          <thead>
                              <tr>
                              <th>编码</th>
                              <th>课程名称</th>
                              <th>前端展示状态</th>
                              <th>分类名称</th>
                              <th>内部员工价格</th>
                              <th>普通价格</th>
                              <th>招生人数</th>
                                  {{-- <th colspan="3">操作</th> --}}
                              </tr>
                          </thead>
                          <tbody>
                          @foreach($courses as $course)
                              <tr>
                                  <td>{!! $course->code !!}</td>
                                  <td>{!! $course->name !!}</td>
                                  <td>{!! $course->Shows !!}</td>
                                  <td>{!! $course->cat_name !!}</td>
                                  <td>{!! $course->inside_price !!}</td>
                                  <td>{!! $course->price !!}</td>
                                  <td>{!! $course->max_num !!}</td>
                      
                              </tr>
                          @endforeach
                          </tbody>
                      </table>       

                                       

                      </div>


            

                      <div class="tab-pane" id="kecheng_list">
                          <div class="mt30" id="calendar"></div>
                      </div>
   
                  <!-- /.tab-pane -->

             
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
              <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

        </section>
    </div>
@endsection

@section('scripts')
  <script src="{{ asset('moment/moment.js') }}"></script>
  <script src="{{ asset('fullcalendar/dist/fullcalendar.min.js') }}"></script>
  <script>
      var date=new Date;
      var year=date.getFullYear(); 
      // console.log(year);
      // var year = 2018;
      $(function () {
        var date = new Date()
        var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear()
        $('#calendar').fullCalendar({
          header    : {
            left  : 'prev,next today',
            center: 'title',
            right:'下个月'
            // right : 'month,agendaWeek,agendaDay'
          },
          buttonText: {
            today: '返回今天',
            month: '月',
            week : '周',
            day  : '日'
          },
          monthNames:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
          monthNamesShort:['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
          dayNamesShort:['周日','周一','周二','周三','周四','周五','周六'],
          //Random default events
          events: [],
          eventRender: function (event, element) {
            element.html(event.title);           
          },
          editable  : false,
          droppable : false, // this allows things to be dropped onto the calendar !!!
          dayClick: function(date, allDay, jsEvent, view) {//当单击日历中的某一天时，触发callback
      
          },
          eventClick:function(event, jsEvent, view){//当点击日历中的某一日程（事件）时，触发此操作
              
          }
        });
        initEvents();
      })

      //初始化事件
      function initEvents(){
        layer.closeAll();
        var events =     [

        ];
          
        $.zcjyRequest('/ajax/user_kebiaos/{!! $user->id !!}',function(res){
          if(res){
            if(res.length){
              for (var i = res.length -1; i >= 0; i--) {
                var event = res[i]['event'];
                if(!$.empty(event)){
                    for (var e = 0; e < event.length; e++) {
                          events.push({
                            e_id           : i+'_'+e,
                            time           : res[i]['time'],
                            title          : event[e],
                            //月份要减去1
                            start          : new Date(res[i]['y'], res[i]['m']-1, res[i]['d']),
                            allDay         : true,
                            // backgroundColor: res[i]['color'],
                            // borderColor    : res[i]['color']
                          });
                  }
                }
              }
            $('#calendar').fullCalendar('removeEvents');
            $('#calendar').fullCalendar('addEventSource', events);
            $('#calendar').fullCalendar('refetchEvents');
            }
          }
        });
      }
      $('#calendar').find('.fc-prev-button,.fc-next-button').click(function(){
        alert($(this).hasClass('fc-prev-button')?'prev':'next');
      });
  </script>
@endsection
{{-- @include('admin.user.js') --}}
