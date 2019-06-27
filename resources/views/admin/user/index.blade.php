@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">用户列表</h1>
        @if(Request::get('type') == '单位内部用户')
          <a style="margin-top: 5px;display: inline;margin-left: 15px;" href="/excel/demo.xls">导入信息模板</a>
           <a style="margin-top: 5px;display: inline;margin-left: 15px;" class="btn btn-success import_topic" href="javascript:;">导入内部员工信息</a>
        @else
        <a style="margin-top: 5px;display: inline;margin-left: 15px;" class="btn btn-danger" href="javascript:$('.reportForm').submit();">全部导出</a>
        {!! Form::open(['route' => ['users.reports'],'class'=>'reportForm']) !!}
        {!! Form::close() !!}
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>

         <!--查询搜索框-->
         <div class="box box-default box-solid mb10-xs @if(!$tools) collapsed-box @endif">
            <div class="box-header with-border">
              <h3 class="box-title">查询</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-{!! !$tools?'plus':'minus' !!}"></i></button>
              </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <form id="projects_search">

                      <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                          <label for="order_delivery">姓名</label>
                         <input type="text" class="form-control" name="name" placeholder="姓名" @if (array_key_exists('name', $input))value="{{$input['name']}}"@endif>
                      </div>

                   @if(Request::get('type') != '单位内部用户')
                      <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                          <label for="order_delivery">微信昵称</label>
                         <input type="text" class="form-control" name="nickname" placeholder="微信昵称" @if (array_key_exists('nickname', $input))value="{{$input['nickname']}}"@endif>
                      </div>
                    @else
                    <input type="hidden" name="type" value="{!! Request::get('type') !!}" />
                    @endif

                    <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <label for="order_delivery">手机号</label>
                       <input type="text" class="form-control" name="mobile" placeholder="手机号" @if (array_key_exists('mobile', $input))value="{{$input['mobile']}}"@endif>
                    </div>

                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <label for="snumber">查看专家权限</label>
                            <select name="can_read_zj" class="form-control">
                                    <option value="0" @if(!array_key_exists('can_read_zj',$input)) selected="selected" @endif>全部</option>
                                    <option value="1" @if(array_key_exists('can_read_zj',$input) && $input['can_read_zj']=='1') selected="selected" @endif>可查看</option>
                                    <option value="2" @if(array_key_exists('can_read_zj',$input) && $input['can_read_zj']=='2') selected="selected" @endif>不可查看</option>
                            </select>
                    </div> 
        
                    <div class="form-group col-lg-1 col-md-1 hidden-xs hidden-sm" style="padding-top: 25px;">
                        <button type="submit" class="btn btn-primary pull-right " onclick="search()">查询</button>
                    </div>
                    <div class="form-group col-xs-6 visible-xs visible-sm" >
                        <button type="submit" class="btn btn-primary pull-left " onclick="search()">查询</button>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        <!--/查询搜索框-->

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.user.table')
            </div>
        </div>
         <div class="text-center">
             <div class="tc"><?php echo $users->appends($input)->render(); ?></div>
         </div>
    </div>

<div id="import_box" style="display: none;">
    <div style='width:350px; padding: 0 15px;height: 100%;'>
        <form id="import_form" class="import_class">
            <div style='width:320px;padding: 0px 0px 0px 0px;' class='form-group has-feedback attach' style="">
                 <label>上传Excel文件:</label>
                 <div class="input-append type_files" style="">
                      <a href="javascript:;"  class="btn upload_file" type="button" >请点击上传Excel文件或者拖动上传</a>
                      {{-- <a href="">打开excel预览</a> --}}
                 </div>
            </div>
            <input type="hidden" name="excel_path" value="">


            <button style='margin-top:5%;width:80%;margin:0 auto;margin-bottom:5%;display: none;' type='button' class='btn btn-block btn-primary' onclick='startImport()'>开始导入</button>
        </form>

    

    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('vendor/dropzone/dropzone.js') }}"></script>
<script type="text/javascript">
      $('.import_topic').click(function(){
        layer.open({
            type: 1,
            closeBtn: false,
            shift: 7,
            shadeClose: true,
            title:'请把要导入的Excel文件拖动到这',
            content: $('#import_box').html()
        });
        click_dom = $('.type_files:eq(1)');
    });

    //开始导入
    function startImport(){
          layer.msg('系统正在整理信息...请耐心等待', {
              icon: 16,shade: 0.01,time:1000000
          });
          $.zcjyRequest('/ajax/autogenerate_user',function(res){
                if(res){
                        layer.closeAll();
                        layer.msg(res, {
                        icon: 1,
                        skin: 'layer-ext-moon' 
                        });
                       //
                       setTimeout(function(){
                        location.reload();
                       },1000);
                    
                }
                else{
                    layer.closeAll();
                    click_dom.find('a').text('上传失败╳,请重新上传 ');
                }
          },$('#import_form').serialize());
    }

    //图片文件上传
    var myDropzone = $(document.body).dropzone({
        url:'/ajax/upload_file',
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        addRemoveLinks:false,
        maxFiles:100,
        autoQueue: true, 
        previewsContainer: ".attach", 
        clickable: ".type_files",
        headers: {
         'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        addedfile:function(file){
            console.log(file);
        },
        totaluploadprogress:function(progress){
            progress=Math.round(progress);
            click_dom.find('a').text(progress+"%");
        },
        queuecomplete:function(progress){
          //console.log(progress);
          click_dom.find('a').text('上传完毕√');
        },
        success:function(file,data){
          if(data.code == 0){
              console.log('上传成功:'+data.message.src);
              if(data.message.type == 'image'){
                click_dom.find('img').attr('src',data.message.src);
              }
              else if(data.message.type == 'sound'){
                click_dom.find('audio').show().attr('src',data.message.src);
              }
                else if(data.message.type == 'excel'){
                    console.log($('#import_form').find('input[name=excel_path]'));
                    $('#import_form').find('input[name=excel_path]').val(data.message.current_src);
                    $('.import_class').find('button').show();
                    return;
                }
                if(click_dom.data('type') == 'question'){
                    $('input[name=attach_sound_url]').val(data.message.src);
                }
                else if(click_dom.data('type') == 'selection'){
                    $('input[name=selection_sound_url]').val(data.message.src);
                }
                else{
                    $('input[name=attach_url]').val(data.message.src);
                }
          
          }
          else{
            click_dom.find('a').text('上传失败╳ ');
            alert('文件格式不支持!');
          }
      },
      error:function(){
        console.log('失败');
      }
    });

    var click_dom = $('.type_files');
    $(document).on('click','.type_files',function(){
        click_dom = $(this);
        // console.log('aa');
        $('input[type=file]').trigger('click');
    });

    //发送微信通知
    $('.informMessage').click(function(){
        var user_id = $(this).data('id');
        $.zcjyRequest('/ajax/user_weixin_inform/'+user_id,function(res){
          if(res){
             $.alert(res);
          }
        });
    });
</script>
@endsection

