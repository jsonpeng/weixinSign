<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', function () {
    return redirect('/zcjy');
});


Route::get('report','Admin\OrderController@allJoinLog');

Route::any('/wechat', 'Admin\Wechat\WechatController@serve');

//刷新缓存
Route::post('/clearCache','Controller@clearCache');

//测试路由
Route::get('/test',function(){
	dd(app('zcjy')->staticData());
	// dd(wechatSearchOneOrder('1552531403_1150'));
	dd(alipaySearchOneOrder('1552523665_1128'));
	dd(alipaySearchOrder('2019-02'));
	dd(getIDCardInfo('420982199604130010'));
	dd(app('zcjy')->maxWeekCoursesNum());
	
});

Route::get('limit',function(){
	return view('front.limit');
});

//支付
Route::group(['namespace'=>'Front'],function(){
		#微信支付通知
		Route::any('weixin_notify_pay','MainController@payWechatNotify');
	 	### 支付宝支付通知
 		Route::any('alipay_notify','MainController@alipayWebNotify');
	 	### 支付宝支付同步
 		Route::any('alipay_return','MainController@alipayWebReturn');
});

//微信跳转回调
Route::get('/weixin/auth_callback','Front\UserController@weixinAuthCallback');

//前端路由
Route::group(['namespace'=>'Front','middleware'=>['auth.user']],function(){

	#专家资料列表
	Route::get('experts','MainController@experts');

	#专家资料详情
	Route::get('expert/{id}','MainController@expertDetail');

	#文案分类
	Route::get('post_cat/{cat_name?}','MainController@postCat');

	#文案详情
	Route::get('post/{id}','MainController@postDetail');

	#前端首页 教学活动
	Route::get('/','MainController@index');

	#所有开设课程列表 开设课程
	Route::get('/cat','MainController@courseCats');

	#对应分类下的课程 查看课程
	Route::get('/cat/{id}','MainController@courseCatShow');

	#课程班列表
	Route::get('/courses/{id}','MainController@courses');

	#课程班详情
	Route::get('/course/{id}','MainController@courseDetail');

	Route::group(['middleware'=>'web_auth'],function(){

		#选择付款方式
		Route::get('choose_pay','MainController@choosePay');

		#确认报名
		Route::get('enter_sign','MainController@enterSign');

	});

	#报名须知
	Route::get('sign_guide','MainController@signGuide');

	#兴趣小组
	Route::get('like_groups/{type?}','MainController@likeGroups');

	#兴趣小组详情
	Route::get('like_group/{id}','MainController@likeGroupDetail');

	//ajax请求
	Route::group(['prefix'=>'ajax'],function(){

		#注册完善注册信息
		Route::get('prefect_reg','AjaxController@prefectRegUser');

		#发送手机验证码
		Route::get('send_code','AjaxController@sendMobileCode');

		#发起收藏操作
		Route::get('action_attention_course/{id}','AjaxController@actionAttentionCourse');

		#查找退休单位
		Route::get('find_nuit','AjaxController@findRetUnit');

		Route::group(['middleware'=>'web_auth'],function(){
			##修改手机号
			Route::get('update_mobile','AjaxController@updateMobile');

			##添加课程
			Route::get('add_courses/{course_id}','AjaxController@addCourseAction');

			##删除课程
			Route::get('del_course/{course_join_id}','AjaxController@delCourseAction');

			##结算课程
			Route::get('settle_check','AjaxController@settleCheck');

			##马上支付未支付的订单
			Route::get('settle_now','AjaxController@settleNow');

		});

	
	});

	//个人
	Route::group(['prefix'=>'user'],function(){

		#注册
		Route::get('reg','UserController@reg');	

		#登录
		Route::get('login','UserController@login');	

			#我的个人中心
			Route::get('index','UserController@index');

			#我的收藏
			Route::get('collect','UserController@collect');

			Route::group(['middleware'=>'web_auth'],function(){

				#我的订单
				Route::get('orders','UserController@orders');

				#我的课程表
				Route::get('course_biao','UserController@courseBiao');

				#修改手机号
				Route::get('edit_mobile','UserController@editMobile');

			});

	});

});


//开启认证
Auth::routes();
//后台ajax操作
Route::group([ 'middleware' => ['auth.admin:admin'], 'prefix' => 'ajax'], function () {
	//上传文件
	Route::post('upload_file','AppBaseController@uploadFile');
	//自动从excel中读取信息并且生成用户信息
	Route::get('autogenerate_user','AppBaseController@autoGenerateUser');
	//系统所有课程安排
	Route::get('courses/{year?}','Admin\SettingController@allCoursesPlan');
	//发送系统邮件
	Route::get('send_email','AppBaseController@sendEmail');
	//发送微信通知
	Route::get('send_weixininform','AppBaseController@sendWeixinInform');
	//指定用户的课表
	Route::get('user_kebiaos/{id}','AppBaseController@userKeBiaos');
	//给指定用户推送通知
	Route::get('user_weixin_inform/{id}','Admin\UserController@sendWeiXinText');
	//查账单
	Route::post('check_order','AppBaseController@checkOrder');
	//操作纠错
	Route::post('action_order/{id}','AppBaseController@actionOrder');
});
/**
 * 认证路由
 */
Route::group([ 'prefix' => 'zcjy', 'namespace' => 'Admin\Auth'], function () {
	Route::get('login', 'AdminAuthController@showLoginForm');
	Route::post('login', 'AdminAuthController@login');
	Route::get('logout', 'AdminAuthController@logout');
});



Route::group([ 'middleware' => ['auth.admin:admin'], 'prefix' => 'zcjy', 'namespace' => 'Admin'], function () {
	//首页
	Route::get('/', 'SettingController@setting');
	/**
	 * 网站设置
	 */
	Route::get('settings/setting', 'SettingController@setting')->name('settings.setting');
	Route::post('settings/setting', 'SettingController@update')->name('settings.setting.update');
	//修改密码
	Route::get('setting/edit_pwd','SettingController@edit_pwd')->name('settings.edit_pwd');
    Route::post('setting/edit_pwd/{id}','SettingController@edit_pwd_api')->name('settings.pwd_update');
    //批量导出用户
    Route::post('users/reportMany','UserController@reportMany')->name('users.reports');
    //用户管理
    Route::get('users','UserController@index')->name('users.index');
    //用户管理
    Route::get('users/edit/{id}','UserController@edit')->name('users.edit');
    //用户信息查看
    Route::get('users/show/{id}','UserController@show')->name('users.show');
	//更新用户
	Route::post('user/{id}/update','UserController@update');
	//删除用户
	Route::post('user/{id}/delete','UserController@deleteAction')->name('users.destory');
	//更新用户查看权限
	Route::post('user/{id}/update_zj','UserController@updateZjAction')->name('users.updatezj');

	/**
	 * 内容管理
	 */
	//横幅
	Route::resource('banners', 'BannerController');
	Route::resource('{banner_id}/bannerItems', 'BannerItemController');
	//文案分类管理
	Route::resource('cats', 'CatController');
	//文章
	Route::resource('posts', 'PostController');
	//通知消息
	Route::resource('messages', 'MessagesController');
    /**
     * 微信公众号功能
     */
    Route::group([ 'prefix' => 'wechat'], function () {
    	Route::group([ 'prefix' => 'menu'], function () {
			Route::get('menu', 'Wechat\MenuController@getIndex')->name('wechat.menu');
			Route::get('lists', 'Wechat\MenuController@getLists');
			Route::get('create', 'Wechat\MenuController@getCreate');
			Route::get('delete/{id}', 'Wechat\MenuController@getDelete');
			Route::get('update/{id}', 'Wechat\MenuController@getUpdate');
			Route::get('single/{id}', 'Wechat\MenuController@getSingle');
			Route::post('store', 'Wechat\MenuController@postStore');
			Route::get('update-menu-event', 'Wechat\MenuController@getUpdateMenuEvent');
		});

		Route::group([ 'prefix' => 'reply'], function () {
			Route::get('/', 'Wechat\ReplyController@getIndex');
			Route::get('index', 'Wechat\ReplyController@getIndex')->name('wechat.reply');
			Route::get('rpl-follow', 'Wechat\ReplyController@getRplFollow');
			Route::get('rpl-no-match', 'Wechat\ReplyController@getRplNoMatch');
			Route::get('follow-reply', 'Wechat\ReplyController@getFollowReply');
			Route::get('no-match-reply', 'Wechat\ReplyController@getNoMatchReply');
			Route::get('lists', 'Wechat\ReplyController@getLists');
			Route::get('save-event-reply', 'Wechat\ReplyController@getSaveEventReply');
			Route::post('store', 'Wechat\ReplyController@postStore');
			Route::get('edit/{id}', 'Wechat\ReplyController@getEdit');
			Route::post('update/{id}', 'Wechat\ReplyController@postUpdate');
			Route::get('delete/{id}', 'Wechat\ReplyController@getDelete');
			Route::get('single/{id}', 'Wechat\ReplyController@getSingle');
			Route::get('delete-event/{type}', 'Wechat\ReplyController@getDeleteEvent');
		});

		Route::group([ 'prefix' => 'material'], function () {
			Route::get('by-event-key/{key}', 'Wechat\MaterialController@getByEventKey');
		});
	});
    
	//专家资料管理
	Route::resource('experts', 'ExpertController');
    //教室管理
    Route::resource('classrooms', 'ClassroomController');
    //课程分类管理
    Route::resource('{type}/courseCats', 'CourseCatController');

    //课程设置显示不显示
    Route::post('courses_action/{cat_id}/{id}','CourseController@updateAction');

    //课程设置开放状态
    Route::post('courses_open/{cat_id}/{id}','CourseController@updateOpenStatus');
    
    //课程管理
	Route::resource('{cat_id}/courses', 'CourseController');
	//课程安排管理
	Route::resource('attachCourses', 'AttachCourseController');

	//课程参与记录导出
	Route::post('courseJoins/reportMany', 'CourseJoinController@reportMany')->name('courseJoins.reports');

	//课程参与记录
	Route::resource('courseJoins', 'CourseJoinController');

	//订单导出
	Route::post('orders/reportMany','OrderController@reportMany')->name('orders.reports');
	//订单管理
	Route::resource('orders', 'OrderController');
	
   }
);




