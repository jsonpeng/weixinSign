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
<section class="content pdall0-xs pt10-xs">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li>
                <a href="javascript:;">
                    <span style="font-weight: bold;">通用设置</span>
                </a>
            </li>
            <li class="active">
                <a href="#tab_1" data-toggle="tab">课程安排</a>
            </li>

       {{--      

            <li>
                <a href="#tab_6" data-toggle="tab">项目金额设置</a>
            </li> --}}

            <li>
                <a href="#tab_2" data-toggle="tab">系统设置</a>
            </li>

            <li>
                <a href="#tab_3" data-toggle="tab">邮箱信息设置</a>
            </li>

   
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="box box-info form">
                <!-- form start -->
                <div class="box-body">
                    <div class="mt30" id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- /.tab-pane -->
  
        <div class="tab-pane" id="tab_2">
                <div class="box box-info form">
                <!-- form start -->
                <div class="box-body">
                    <form class="form-horizontal" id="form1">
                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">系统名称</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sys_name" maxlength="60" placeholder="系统名称" value="{{ getSettingValueByKey('sys_name') }}"></div>
                        </div>

                        <div class="form-group">
                          <label for="names" class="col-sm-3 control-label">网站名称</label>
                          <div class="col-sm-9">
                              <input type="text" class="form-control" name="name" maxlength="60" placeholder="系统名称" value="{{ getSettingValueByKey('name') }}"></div>
                        </div>

           {{--              <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">客服微信号</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="weixin_number" maxlength="60" placeholder="客服微信号" value="{{ getSettingValueByKey('weixin_number') }}"></div>
                        </div> --}}

                        <div class="form-group">
                                                <label for="weixin_erweima" class="col-sm-3 control-label">微信二维码</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="image1" name="weixin_erweima" placeholder="微信客服二维码" value="{{ getSettingValueByKey('weixin_erweima') }}">
                                                    <div class="input-append">
                                                        <a data-toggle="modal" href="javascript:;" data-target="#myModal" class="btn" type="button" onclick="changeImageId('image1')">选择图片</a>
                                                        <img src="@if(getSettingValueByKey('weixin_erweima')) {{ getSettingValueByKey('weixin_erweima') }} @endif" style="max-width: 100%; max-height: 150px; display: block;">
                                                    </div>
                                                </div>
                        </div>
             
                         <div class="form-group">
                          <label for="all_sign_permission" class="col-sm-3 control-label">选择报名权限</label>
                          <div class="col-sm-4">
                              <select name="all_sign_permission" class="form-control">
                                  <option value="0" @if(!getSettingValueByKey('all_sign_permission')) selected="selected" @endif>全部人可报名</option>
                                  <option value="1" @if(getSettingValueByKey('all_sign_permission')) selected="selected" @endif>仅内部可报名</option>
                              </select>
                          </div>
                        </div>

                        <div class="form-group">
                            <label for="sign_open_status" class="col-sm-3 control-label">系统报名总开关</label>
                            <div class="col-sm-4">
                                <select name="sign_open_status" class="form-control">
                                    <option value="关闭" @if(getSettingValueByKey('sign_open_status') == '关闭') selected="selected" @endif>关闭</option>
                                    <option value="开启" @if(getSettingValueByKey('sign_open_status') == '开启') selected="selected" @endif>开启</option>
                                </select>
                            </div>
                          </div>

                          <div class="form-group">
                            <label for="sign_open_status" class="col-sm-3 control-label">当前课程报名时间</label>

                            <div class="col-sm-2">
                                <select name="sign_year" class="form-control">
                                    <option value="2019" @if(getSettingValueByKey('sign_year') == '2019') selected="selected" @endif>2019</option>
                                    <option value="2020" @if(getSettingValueByKey('sign_year') == '2020') selected="selected" @endif>2020</option>
                                    <option value="2021" @if(getSettingValueByKey('sign_year') == '2021') selected="selected" @endif>2021</option>
                                    <option value="2022" @if(getSettingValueByKey('sign_year') == '2022') selected="selected" @endif>2022</option>
                                     <option value="2023" @if(getSettingValueByKey('sign_year') == '2023') selected="selected" @endif>2023</option>
                                     <option value="2024" @if(getSettingValueByKey('sign_year') == '2024') selected="selected" @endif>2024</option>
                                     <option value="2025" @if(getSettingValueByKey('sign_year') == '2025') selected="selected" @endif>2025</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <select name="sign_quarter" class="form-control">
                                    <option value="1" @if(getSettingValueByKey('sign_quarter') == '1') selected="selected" @endif>春季</option>
                                    <option value="2" @if(getSettingValueByKey('sign_quarter') == '2') selected="selected" @endif>秋季</option>
                                </select>
                            </div>

                          </div>

                        
                    </form>
                </div>


                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-left" onclick="saveForm(1)">保存</button>
                </div>
                <!-- /.box-footer -->
             </div>
        </div>

        
        <div class="tab-pane" id="tab_3">
                 <div class="box box-info form">
                <!-- form start -->
                <div class="box-body">
                    <form class="form-horizontal" id="form2">

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">报名数据接收邮箱</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="receive_data_email" maxlength="60" placeholder="报名数据接收邮箱" value="{{ getSettingValueByKey('receive_data_email') }}"></div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">报名数据备用邮箱</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="beiyong_data_email" maxlength="60" placeholder="报名数据备用邮箱" value="{{ getSettingValueByKey('beiyong_data_email') }}"></div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">发送邮件附件时间(每天指定发送前一天的报名数据)</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control time_set" name="send_email_time" maxlength="60" placeholder="发送邮件附件时间" value="{{ getSettingValueByKey('send_email_time') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">发送上课信息通知时间(每天指定推送明天要上课的课程通知)</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control time_set" name="send_course_time" maxlength="60" placeholder="发送上课信息通知时间" value="{{ getSettingValueByKey('send_course_time') }}">
                            </div>
                        </div>

                         <div class="form-group">
                            <div class="row">
                                <div class="col-sm-1">
                                  <button class="btn btn-success emailTest" type="button">发送邮件测试</button>
                                </div>
                                <div class="col-sm-1">
                                  <button class="btn btn-primary weixinTest" type="button">推送微信消息测试</button>
                                </div>
                            </div>
                         </div>
     
             
                    </form>
                </div>


                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-left" onclick="saveForm(2)">保存</button>
                </div>
                <!-- /.box-footer -->
             </div>
        </div>

        <div class="tab-pane" id="tab_8">
           
        </div>

    </div>
    <!-- /.tab-content -->
</div>
</section>
@endsection

@include('admin.partials.imagemodel')

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
          
        $.zcjyRequest('/ajax/courses/'+year,function(res){
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
<script src="{{ asset('js/select.js') }}"> </script>
<script>
      function timepicker(obj){
        obj.datetimepicker({
                language: 'zh-CN',
                weekStart: 0,
                todayBtn: true,
                autoclose: true,
                startView: 1,
                minView: 0,
                format:'hh:ii',
                clearBtn:true,

        });
      }
        var time_val;
         $(document).on('mouseover','.time_set',function(e){
              time_val = $(this).val();
              $(this).val('');
              if(!$(this).hasClass('picker')){
                timepicker($(this));
                // $(this).trigger('click');
                $(this).addClass('picker');
                // $(this).click();
              }
         }).on('mouseleave','.time_set',function(e){
            $(this).val(time_val);
         });  
        function saveForm(index){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/zcjy/settings/setting",
                type:"POST",
                data:$("#form"+index).serialize(),
                success: function(data) {
                  if (data.code == 0) {
                    layer.msg(data.message, {icon: 1});
                  }else{
                    layer.msg(data.message, {icon: 5});
                  }
                },
                error: function(data) {
                  //提示失败消息

                },
            });  
        }
        //纠错信息列表高度自适应
        // $("#error_info_list").height($("#error_info_list")[0].scrollHeight);
        // $("#error_info_list").on("keyup keydown", function(){
        //     console.log(this.scrollHeight);
        //     $(this).height(this.scrollHeight-10);
        // });

        $('#error_info_list,#project_money_list').keypress(function(e) {  
            var rows=parseInt($(this).attr('rows'));
            // 回车键事件  
           if(e.which == 13) {  
                rows +=1;
           }  
           $(this).attr('rows',rows);
       }); 
        $('.emailTest').click(function(){
          $.zcjyRequest('/ajax/send_email',function(res){
            $.alert(res);
          });
        });
        $('.weixinTest').click(function(){
          $.zcjyRequest('/ajax/send_weixininform',function(res){
            $.alert(res);
          });
        });
    </script>
@endsection