
<li class="header">系统</li>
  <li class="treeview @if(Request::is('zcjy/settings/setting*') || Request::is('zcjy')  || Request::is('zcjy/wechat/menu*') || Request::is('zcjy/wechat/reply*') || Request::is('zcjy/users*') || Request::is('zcjy/cities*')) active @endif " >
    <a href="#">
      <i class="fa fa-cog"></i>
      <span>系统管理</span>
      <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">

        <li class="{{ Request::is('zcjy/settings/setting*') || Request::is('zcjy') ? 'active' : '' }}">
            <a href="{!! route('settings.setting') !!}"><i class="fa fa-cog"></i><span>系统设置</span></a>
        </li>
        
     {{--    <li class="{{ Request::is('zcjy/wechat/menu*') Request::is('zcjy/wechat/reply*') ? 'active' : '' }}">
            <a href="{!! route('wechat.menu') !!}"><i class="fa fa-commenting"></i><span>微信设置</span></a>
        <li> --}}

        <li class="{{ Request::is('zcjy/users*') && Request::get('type') == '单位内部用户' ? 'active' : '' }}">
            <a href="{!! route('users.index') !!}?type=单位内部用户"><i class="fa fa-user"></i><span>内部用户管理</span></a>
        </li>

        <li class="{{ Request::is('zcjy/users*') && Request::get('type') != '单位内部用户' ? 'active' : '' }}">
            <a href="{!! route('users.index') !!}"><i class="fa fa-user"></i><span>微信用户管理</span></a>
        </li>

    </ul>
</li>
<li class="header">教室/课程管理</li>
<li class="{{ Request::is('zcjy/classrooms*') ? 'active' : '' }}">
    <a href="{!! route('classrooms.index') !!}"><i class="fa fa-edit"></i><span>教室管理</span></a>
</li>

<li class="{{ Request::is('zcjy/课程班/courseCats*') || Request::is('zcjy/*/courseCats/课程班*') || Request::is('zcjy/*/courses*') && app('zcjy')->CourseCatRepo()->varifyCatIdType(request('cat_id'),'课程班')  ? 'active' : '' }}">
    <a href="{!! route('courseCats.index','课程班') !!}"><i class="fa fa-edit"></i><span>课程班</span></a>
</li>

<li class="{{ Request::is('zcjy/兴趣小组/courseCats*') || Request::is('zcjy/*/courseCats/兴趣小组*') || Request::is('zcjy/*/courses*') && app('zcjy')->CourseCatRepo()->varifyCatIdType(request('cat_id'),'兴趣小组') ? 'active' : '' }}">
    <a href="{!! route('courseCats.index','兴趣小组') !!}"><i class="fa fa-edit"></i><span>兴趣小组</span></a>
</li>

<li class="{{ Request::is('zcjy/活动/courseCats*')  || Request::is('zcjy/*/courseCats/活动*') || Request::is('zcjy/*/courses*') && app('zcjy')->CourseCatRepo()->varifyCatIdType(request('cat_id'),'活动') ? 'active' : '' }}">
    <a href="{!! route('courseCats.index','活动') !!}"><i class="fa fa-edit"></i><span>活动</span></a>
</li>

{{-- <li class="{{ Request::is('zcjy/courses*') ? 'active' : '' }}">
    <a href="{!! route('courses.index') !!}"><i class="fa fa-edit"></i><span>课程管理</span></a>
</li> --}}

{{-- <li class="{{ Request::is('zcjy/attachCourses*') ? 'active' : '' }}">
    <a href="{!! route('attachCourses.index') !!}"><i class="fa fa-edit"></i><span>课程安排</span></a>
</li>

<li class="{{ Request::is('zcjy/courseJoins*') ? 'active' : '' }}">
    <a href="{!! route('courseJoins.index') !!}"><i class="fa fa-edit"></i><span>课程参与记录</span></a>
</li> --}}

<li class="header">报名/订单管理</li>
<li class="{{ Request::is('zcjy/courseJoins*') ? 'active' : '' }}">
    <a href="{!! route('courseJoins.index') !!}"><i class="fa fa-edit"></i><span>课程参与记录</span></a>
</li>
<li class="{{ Request::is('zcjy/orders*') ? 'active' : '' }}">
    <a href="{!! route('orders.index') !!}"><i class="fa fa-edit"></i><span>订单管理</span></a>
</li>
<li class="header">内容管理</li>
<li class="treeview @if(Request::is('zcjy/categories*') || Request::is('zcjy/posts*') || Request::is('zcjy/customPostTypes') || Request::is('zcjy/*/customPostTypeItems*') || Request::is('zcjy/banners*') || Request::is('zcjy/*/bannerItems') || Request::is('zcjy/messages*') || Request::is('zcjy/cats*')) active @endif " >
  <a href="#">
    <i class="fa fa-pie-chart"></i>
    <span>内容管理</span>
    <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
 {{--    <li class="{{ Request::is('zcjy/banners*') || Request::is('zcjy/*/bannerItems') ? 'active' : '' }}">
        <a href="{!! route('banners.index') !!}"><i class="fa fa-object-group"></i><span>横幅管理</span></a>
    </li>  --}}
    <li class="{{ Request::is('zcjy/cats*') ? 'active' : '' }}">
        <a href="{!! route('cats.index') !!}"><i class="fa fa-edit"></i><span>文章分类</span></a>
    </li>
    <li class="{{ Request::is('zcjy/posts*') ? 'active' : '' }}">
        <a href="{!! route('posts.index') !!}"><i class="fa fa-newspaper-o"></i><span>文章</span></a>
    </li>
 {{--    <li class="{{ Request::is('zcjy/messages*') ? 'active' : '' }}">
        <a href="{!! route('messages.index') !!}"><i class="fa fa-commenting"></i><span>通知消息</span></a>
    </li> --}}
  </ul>
</li>

<li class="{{ Request::is('zcjy/experts*') ? 'active' : '' }}">
    <a href="{!! route('experts.index') !!}"><i class="fa fa-edit"></i><span>专家资料管理</span></a>
</li>

<li class="">
    <a href="javascript:;" id="refresh"><i class="fa fa-refresh"></i><span>清理缓存</span></a>
</li>




{{-- <li class="{{ Request::is('attentionCourses*') ? 'active' : '' }}">
    <a href="{!! route('attentionCourses.index') !!}"><i class="fa fa-edit"></i><span>Attention Courses</span></a>
</li> --}}





