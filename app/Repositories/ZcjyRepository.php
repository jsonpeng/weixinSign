<?php

namespace App\Repositories;

use App\Models\Setting;

use App\Repositories\UserRepository;
use App\Repositories\BannerRepository;
use App\Repositories\PostRepository;
use App\Repositories\MessagesRepository;
use App\Repositories\CourseCatRepository;
use App\Repositories\CourseRepository;
use App\Repositories\ClassroomRepository;
use App\Repositories\AttachCourseRepository;
use App\Repositories\CourseJoinRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CatRepository;
use App\Repositories\ExpertRepository;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Log;
use App\User;
use App\Models\AttachCourse;
use App\Models\Order;
use Image;
use EasyWeChat\Factory;
use Carbon\Carbon;
use Excel;
use Hash;
use Request;

class ZcjyRepository 
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    private $UserRepository;
    private $BannerRepository;
    private $PostRepository;
    private $MessagesRepository;
    private $CourseCatRepository;
    private $CourseRepository;
    private $ClassroomRepository;
    private $AttachCourseRepository;
    private $CourseJoinRepository;
    private $OrderRepository;
    private $CatRepository;
    private $ExpertRepository;
    public function __construct(
      UserRepository $UserRepo,
      BannerRepository $BannerRepo,
      PostRepository $PostRepo,
      MessagesRepository $MessagesRepo,
      CourseCatRepository $CourseCatRepo,
      CourseRepository $CourseRepo,
      ClassroomRepository $ClassroomRepo,
      AttachCourseRepository $AttachCourseRepo,
      CourseJoinRepository $CourseJoinRepo,
      OrderRepository $OrderRepo,
      CatRepository $CatRepo,
      ExpertRepository $ExpertRepo
    )
    {
      $this->UserRepository = $UserRepo;
      $this->BannerRepository = $BannerRepo;
      $this->PostRepository = $PostRepo;
      $this->MessagesRepository = $MessagesRepo;
      $this->CourseCatRepository = $CourseCatRepo;
      $this->CourseRepository = $CourseRepo;
      $this->ClassroomRepository = $ClassroomRepo;
      $this->AttachCourseRepository = $AttachCourseRepo;
      $this->CourseJoinRepository = $CourseJoinRepo;
      $this->OrderRepository = $OrderRepo;
      $this->CatRepository = $CatRepo;
      $this->ExpertRepository = $ExpertRepo;
    }

    public function ExpertRepo()
    {
      return $this->ExpertRepository;
    }

    public function CatRepo()
    {
      return $this->CatRepository;
    }

    public function OrderRepo()
    {
      return $this->OrderRepository;
    }

    public function CourseJoinRepo()
    {
      return $this->CourseJoinRepository;
    }

    public function AttachCourseRepo(){
      return $this->AttachCourseRepository;
    }

    public function ClassroomRepo(){
      return $this->ClassroomRepository;
    }

    public function CourseRepo(){
      return $this->CourseRepository;
    }

    public function CourseCatRepo(){
      return $this->CourseCatRepository;
    }

    public function MessagesRepo(){
      return $this->MessagesRepository;
    }

    public function PostRepo(){
      return $this->PostRepository;
    }

    public function BannerRepo(){
      return $this->BannerRepository;
    }

    public function UserRepo(){
        return $this->UserRepository;
    }

    public function staticData(){
      ##所有支付成功订单
      $allOrders = Order::where('pay_status','已支付')->where('pay_platform','<>','无')->get();
      $allOrderCount = count($allOrders);
      $allOrderCountPrice = Order::where('pay_status','已支付')->where('pay_platform','<>','无')->sum('price');
      ##所有支付宝订单
      $alipayOrderCount = 0;
      $alipayOrderCountPrice = 0;
      foreach ($allOrders as $key => $order) 
      {
        if($order->pay_platform == '支付宝')
        {
          $alipayOrderCount++;
          $alipayOrderCountPrice += $order->price;
        }
      }
      ##所有微信订单
      $wechatOrderCount = 0;
      $wechatOrderCountPrice = 0;
      foreach ($allOrders as $key => $order) 
      {
        if($order->pay_platform == '微信')
        {
          $wechatOrderCount++;
          $wechatOrderCountPrice += $order->price;
        }
      }
      //截止目前为止
      return (object)[
         #所有订单数量
        'allOrderCount' => $allOrderCount,
         #所有订单金额
        'allOrderCountPrice' => $allOrderCountPrice,
         #支付宝订单数量
        'alipayOrderCount' => $alipayOrderCount,
         #支付宝订单金额
        'alipayOrderCountPrice' => $alipayOrderCountPrice,
         #微信订单数量
        'wechatOrderCount' => $wechatOrderCount,
         #微信订单金额
        'wechatOrderCountPrice' => $wechatOrderCountPrice,
      ];

    }

    /**
     * [单位内部员工判断]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function insideUserVarify($user)
    {
        if(empty($user))
        {
          return false;
        }

        if($user->type == '单位内部用户')
        {
          return true;
        }
        else{
          return false;
        }
    }

    //清空缓存
    public function clearCache(){
        Artisan::call('cache:clear');
    }
    

     public function exportExcelTable($filter,$data,$file_path=null,$title='导出记录',$browser_open=false)
    {
        if(!empty($filter) && is_array($filter))

        #输入开始
        $html="<tr>";

        foreach ($filter as $key => $value) {
            #循环列 表头
            $html .= "<td>".$value."</td>"; 
        }

        #结束
        $html .= "</tr>";

        foreach ($data as $key => $value) {
            $html .="<tr>";
                #写入单行文本
                foreach ($filter as $key2 => $val2) {
                     if(isset($value[$key2])){
                      $html .= "<td>".$value[$key2]."</td>";
                    }
                    else{
                       $html .= "<td>--</td>";
                    }
                }
            $html .="</tr>";       
        }
        $html = "<table border=1>".$html."</table>";
        if($browser_open){
          header("Content-type:application/vnd.ms-excel;charset=UTF-8");
          $title = iconv("utf-8", "GB2312", $title);
          header("Content-Disposition:attachment;filename={$title}.xls;charset=utf-8");
          echo  $html;
          exit;
        }
        if(!empty($file_path))
        {
            $file_path = public_path('/'.$file_path);
            if(!file_exists($file_path)){
                return zcjy_callback_data('请先创建指定文件',1);
            }
            $myfile = fopen($file_path, "w") or die("Unable to open file!");
            fwrite($myfile, $html);
            fclose($myfile);
        }
        return $html;
    }

    /**
     * [web请求验证]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function webAuthVarify($request,$next)
    {
        $user = auth('web')->user();

        if($request->ajax()){

          if(empty($user))
          {
            return zcjy_callback_data('请先完成登录',1);
          }

          if(empty($user->mobile))
          {
             return zcjy_callback_data('请先完善填写个人信息后使用',1);
          }

        }else{

          if(empty($user->mobile))
          {
            return redirect('/user/reg');
          }

        }

        return $next;
    }

    /**
     * [微信授权跳转]
     * @param  [type] $targer_url [description]
     * @return [type]             [description]
     */
    public function weixinAuthRedirect($target_url='')
    {
        #默认配置项
        $options = Config::get('wechat.official_account.default');

        $options['oauth'] = [
          'scopes'   => ['snsapi_userinfo'],
          'callback' => $options['target_callback_path'].'?target_url='.$target_url,//存储客户端要跳转的链接
        ];
        $app = Factory::officialAccount($options);

        $response = $app
        ->oauth
        ->scopes(['snsapi_userinfo'])
        ->redirect();

        return $response;
    }

    /**
     * 获取微信信息
     * @return [type] [description]
     */
    public function getWeiXinInfo()
    {
        $options = Config::get('wechat.official_account.default');
        //Log::info($options);
        $app =Factory::officialAccount($options);
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $userinfo = $oauth->user();
        $user = User::where('openid', $userinfo->getId())->first();
        if (is_null($user)) 
        {
            // 新建用户
            $user = User::create([
                'openid' => $userinfo->getId(),
                'nickname' => $userinfo->getNickname(),
                'head_image' => $userinfo->getAvatar(),
                ]);
        }
        else{
            $user->update([
                'nickname' => $userinfo->getNickname(),
                'head_image' => $userinfo->getAvatar()
            ]);
        }
        return $user;
    }

    /**
     * [微信callback跳转]
     * @param  [type] $target_url [description]
     * @return [type]             [description]
     */
    public function weixinAuthCallback($target_url)
    {
        $user = $this->getWeiXinInfo();
        $target_url = empty($target_url) ? Request::root() : $target_url;
        #发起登录
        $user->last_ip = Request::ip();
        $user->last_login = \Carbon\Carbon::now();
        $user->save();
        auth('web')->login($user);
        return redirect($target_url);
    }

    /**
     * [根据ip获取微信用户]
     * @param  [type] $ip [description]
     * @return [type]     [description]
     */
    public function getCacheWeixinUser($ip)
    {
      return cache('weixin_user_'.$ip);
    }

    /**
     * [本地开发微信登录]
     * @param  string $openid [description]
     * @return [type]         [description]
     */
    public function localWeixinUser($openid = 'odh7zsgI75iT8FRh0fGlSojc9PWM')
    {
         $user= User::where('openid', $openid)->first();
         return $user;
    }

    /**
     * [把输入中的一些带,的键值转为数组]
     * @param  [type] $input [description]
     * @param  [type] $keys  [description]
     * @return [type]        [description]
     */
    public function dealInputStringToArr($input,$keys)
    {
        if(!is_array($keys)){
            $keys = explode(',',$keys);
        }
        foreach ($keys as $key => $val) {
                if(isset($input[$val])){
                    if(!is_array($input[$val])){
                        $input[$val] = explode(',',$input[$val]);
                    }
                }
        }
        return $input;
    }

    /**
     * [过滤空的输入]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function filterNullInput($input){
        foreach ($input as $key => $value) {
            if(is_null($value) || $value == '' || empty($value) && $value != 0){
               unset($input[$key]);
            }
        }
        return $input;
    }

    /**
     * [默认直接通过数组的值 否则通过数组的键]
     * @param  [type] $input      [description]
     * @param  array  $attr       [description]
     * @param  string $valueOrKey [description]
     * @return [type]             [description]
     */
    public function varifyInputParam($input,$attr=[],$valueOrKey='value'){
      
        #过滤空字符串
        $input = $this->filterNullInput($input);

        $status = false;
        if(!is_array($attr)){
                $attr = explode(',',$attr);
        }
     
        #第一种带键值但值为空的情况
        foreach ($input as $key => $val) {
            if(array_key_exists($key,$input)){
                if(empty($input[$key]) && $input[$key]!=0){
                    $status = '参数不完整';
                }
            }
        }
        #第二种是针对提交的指定键值
        if(count($attr)){
            foreach ($attr as $key => $val) {
                if($valueOrKey == 'value'){
                    if(!array_key_exists($val,$input) || array_key_exists($val,$input) && empty($input[$val]) && $input[$val] != 0){
                        $status = '参数不完整';
                    }
                }
                else{
                     if(!array_key_exists($key,$input) || array_key_exists($key,$input) && empty($input[$key]) && $input[$key] != 0){
                        $status = '参数不完整';
                    }
                }
            }
        }

        return $status;
    }

    /**
     * [通过用户信息生成密钥]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function generateApiKey($user){
       return  Hash::make($user->id.','.getSettingValueByKey('token_key')).'_'.Hash::make(strtotime($user->created_at)).'_'.Hash::make($user->openid);
    }

    /**
     * [接口请求用户验证]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function zcjyApiUserVarify($input){
         $status = false;
         if(isset($input['token']) && !empty($input['token'])){
        
            $token = optional(explode('__', zcjy_base64_de($input['token'])));
            //Log::info($token);
            $user = User::find($token[0]);
            if(empty($user)){
                $status = 'token信息验证失败,参数错误';
                return $status;
            }
            if(!isset($token[0]) || !isset($token[1]) || !isset($token[2]) || !isset($token[3])){
                $status = 'token信息验证失败,参数错误';
                return $status;
            }
            #开始验证token的详细细节
            if($user->id == $token[0]  && strtotime($user->created_at) == $token[1] && $user->openid == $token[2] ){
                $token_time =  empty(getSettingValueByKey('token_time')) ? 1 : getSettingValueByKey('token_time');
                #验证token时间
                if(time_diff($token[3],time())['hour'] >= $token_time){
                    $status = 'token信息验证失败,时间过期';
                }
                #验证密钥有效性
            }
            else{
                $status = 'token信息验证失败,参数错误';
            }

        }
        else{
            $status = 'token信息验证失败,参数错误';
        }
        return $status;
    }

    /**
     * [接口密钥校检]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function zcjyApiKeyVarify($input){
          $user = zcjy_api_user($input);
          if(empty($user)){
               return zcjy_callback_data('token信息验证失败',401);
          }
          if(isset($input['key']) && !empty($input['key'])){
              $key_arr = explode('__',$input['key']);
              if(!isset($key_arr[0]) || !isset($key_arr[1]) || !isset($key_arr[2])){
                  return zcjy_callback_data('密钥key校检失败',10002);
              }
              if(!Hash::check($user->id.','.getSettingValueByKey('token_key'),$key_arr[0]) || !Hash::check(strtotime($user->created_at),$key_arr[1]) || !Hash::check($user->openid,$key_arr[2])){
                  return zcjy_callback_data('密钥key校检失败',10002);
              }
              return false;
          }
          else{
             return zcjy_callback_data('密钥key校检失败',10002);
          }
    }

    /**
     * [图片/文件 上传]
     * @param  [type] $file     [description]
     * @param  string $api_type [description]
     * @return [type]           [description]
     */
    public function uploadFiles($file , $api_type = 'web' , $user = null){
        if(empty($file)){
            return zcjy_callback_data('文件不能为空',1,$api_type);
        }
        #文件类型
        $file_type = 'file';
        #文件实际后缀
        $file_suffix = $file->getClientOriginalExtension();
        if(!empty($file)) {
              $img_extensions = ["png", "jpg", "gif","jpeg"];
              $sound_extensions = ["PCM","WAVE","MP3","OGG","MPC","mp3PRo","WMA","wma","RA","rm","APE","AAC","VQF","LPCM","M4A","cda","wav","mid","flac","au","aiff","ape","mod","mp3"];
              $excel_extensions = ["xls","xlsx","xlsm"];
              if ($file_suffix && !in_array($file_suffix , $img_extensions) && !in_array($file_suffix , $sound_extensions) && !in_array($file_suffix,$excel_extensions)) {
                  return zcjy_callback_data('上传文件格式不正确',1,$api_type);
              }
              if(in_array($file_suffix, $img_extensions)){
                  $file_type = 'image';
              }
              if(in_array($file_suffix, $sound_extensions)){
                $file_type = 'sound';
              }
              if(in_array($file_suffix,$excel_extensions)){
                $file_type = 'excel';
              }
          }

        #文件夹
        $destinationPath = empty($user) ? "uploads/admin/" : "uploads/user/".$user->id.'/';
        #加上类型
        $destinationPath = $destinationPath.$file_type.'/';

        if (!file_exists($destinationPath)){
            mkdir($destinationPath,0777,true);
        }
       
        $extension = $file_suffix;
        $fileName = str_random(10).'.'.$extension;
        $file->move($destinationPath, $fileName);

        #对于图片文件处理
        if($file_type == 'image'){
          $image_path=public_path().'/'.$destinationPath.$fileName;
          $img = Image::make($image_path);
          $img->resize(640, 640);
          $img->save($image_path,70);
        }

        $host='http://'.$_SERVER["HTTP_HOST"];

        if(env('online_version') == 'https'){
             $host='https://'.$_SERVER["HTTP_HOST"];
        }

        #路径
        $path=$host.'/'.$destinationPath.$fileName;

        return zcjy_callback_data([
                'src'=>$path,
                'current_time' => Carbon::now(),
                'type' => $file_type,
                'current_src' => public_path().'/'.$destinationPath.$fileName
            ],0,$api_type);
    }

    //读取excel文件
    public function loadExcels($files){
       if (!file_exists($files)){
          //return zcjy_callback_data('没有找到该文件',1);
          return false;
       }
       $res = [];
       Excel::load($files, function($reader) use( &$res ) {
            $reader = $reader->getSheet(0);
            $res = $reader->toArray();
       }); 
       return $res;
    }

    //检查一下类型
    private function  varifyType($type){
        if (stripos($type,'文本') !== false){
            $type = '文本';
        }
        if (stripos($type,'图片') !== false){
            $type = '图片';
        }
        if (stripos($type,'音频') !== false){
            $type = '音频';
        }
        if($type != '文本' && $type != '图片' && $type != '音频'){
            $type = '文本';
        }
        return $type;
    }

    /**
     * [上传文件并且自动生成用户信息]
     * @param  [type] $files [description]
     * @return [type]        [description]
     */
    public function readExcelsToGenerateUser($files)
    {
       $res= $this->loadExcels($files);
       if(count($res) > 1){

            $add_users_num = 0;
            $false_users_num = 0;
            for ($i=1; $i < count($res); $i++) { 

                if(!isset($res[$i][0]))
                {
                  return zcjy_callback_data('上传文件中第'.$i.'列未填写姓名,请确认后上传',1,'web');
                }

                if(!isset($res[$i][1]))
                {
                  return zcjy_callback_data('上传文件中第'.$i.'列未填写出生年月日,请确认后上传',1,'web');
                }

                $ret_unit = null;
                if(isset($res[$i][2])){
                  $ret_unit = $res[$i][2];
                }

                $birthday = null;
                if(isset($res[$i][1]))
                {
                  $birthday = substr($res[$i][1],0,6);
                }
                // $line = '以下内部用户未添加成功:';
                // if(User::where('name',$res[$i][0])->where('birthday',$birthday)->where('type','单位内部用户')->where('ret_unit',$ret_unit)->count() == 0)
                //   {
                //     $line .= '第'.$i.'列:'.$res[$i][0].';';
                //   }
              
                if(User::where('name',$res[$i][0])->where('birthday',$birthday)->where('type','单位内部用户')->count() == 0)
                  {
                      $add_users_num++;
                      User::create([
                        'name'       => $res[$i][0],
                        'birthday'   => $birthday,
                        'ret_unit'   => $ret_unit,
                        'type'       => '单位内部用户',
                        'import_type'=> '导入用户'
                      ]);
                      #如果之前存在该手机号的普通用户 则自动处理状态为单位内部用户
                      $exist_user = User::where('name',$res[$i][0])->where('birthday',$res[$i][1])->where('type','普通用户')->first();
                      if(!empty($exist_user))
                      {
                        $exist_user->update(['type'=>'单位内部用户','ret_unit'=>$ret_unit]);
                      }
                 }
                 else{
                  $false_users_num++;
                 }
            }
            #新题目上传后自动清除缓存
            $this->clearCache();
            return zcjy_callback_data('上传成功,此次新增'.$add_users_num.'个新用户,'.$false_users_num.'个用户上传失败',0,'web');
       }
       else{
        return zcjy_callback_data('excel中无内容',1,'web');
       }
    }

    //检查一下文本内容
    private function varifyContent($content){
        return empty($content) ? '未知内容' : $content;
    }

    public function varifySort($sort){
      return preg_match("/^\d*$/",$sort) ? (int)$sort : select_sort_num($sort); 
    }

    /**
     * 检查时间过期
     * @param  [type] $end_time [description]
     * @param  string $overdue  [description]
     * @return [type]           [description]
     */
    public function varifyOverdue($end_time,$overdue = 'lt'){
      return $overdue == 'lt' ? Carbon::parse($end_time)->lt(Carbon::now()) : Carbon::parse($end_time)->gt(Carbon::now());
    }


    /**
     * [这一年有多少天]
     * @param  [type] $year [description]
     * @return [type]       [description]
     */
    public function yearDays($year)
    {
      if(!empty($year))
      {
        $year = (int)$year;
        return $year%400 == 0 || $year%4 == 0 && $year%100 != 0 ? 366 : 365;  
      }
      else{
        return null;
      }
    }

    /**
     * [批量生成一年的日期带上星期]
     * @param  string $year [description]
     * @return [type]       [description]
     */
    public function generateDates($year='2018'){

        $year = 'first day of January'.$year;

        #处理年
        $carbon_year = time_parse($year);

        #第一年的第一天
        $start_day_of_year = time_parse($year)->startOfYear();

        #第一年的最后一天
        $end_day_of_year = time_parse($year)->endOfYear();

        #星期数组
        $weekdays_arr = WeekDays();

        #这一年所有的天
        $year_all_days_arr = [
          [
            'day'=>$start_day_of_year,
            'week'=>WeekDays($start_day_of_year->dayOfWeek),
            'y'=>$start_day_of_year->format('Y'),
            'm'=>$start_day_of_year->format('m'),
            'd'=>$start_day_of_year->format('d'),
          ]
        ];

        #判断这一年总共有多少天
        $year_all_days = (int)$this->yearDays($year);

        for ($i = 1; $i < $year_all_days-1; $i++) { 

           $next_day = time_parse($year)->startOfYear()->addDays($i);
         
           $year_all_days_arr[] = [
            'day'=>$next_day,
            'week'=>WeekDays($next_day->dayOfWeek),
            'y'=>$next_day->format('Y'),
            'm'=>$next_day->format('m'),
            'd'=>$next_day->format('d'),
          ];

        }
        
        return $year_all_days_arr;
    }

    /**
     * [判断两个时间在不在同一周]
     * @param  [type] $pretime   [description]
     * @param  [type] $aftertime [description]
     * @return [type]            [description]
     */
    public function getSameWeek($pretime,$aftertime)
    {
      $pretime = strtotime($pretime);
      $aftertime = strtotime($aftertime);
      $flag = false;//默认不是同一周
      $afweek = date('w',$aftertime);//当前是星期几
      $mintime = $aftertime - $afweek * 3600*24;//一周开始时间
      $maxtime = $aftertime + (7-$afweek)*3600*24;//一周结束时间
      if ( $pretime >= $mintime && $pretime <= $maxtime){//同一周
        $flag = true;
      }
      return $flag;
    }


    /**
     * 单周的最大课程数量
     * @return [type] [description]
     */
    public function maxWeekCoursesNum()
    {
        #所有的课程安排
        $allCourses = AttachCourse::all();

        $sum_arr = titleCountTimes($allCourses,'weekday');

        return empty($sum_arr->max('count')) ? 1 : $sum_arr->max('count');
    }


    /**
     * [所有课程安排]
     * @param  string $year [description]
     * @return [type]       [description]
     */
    public function allCourses($all_courses = null,$year = null)
    {

      #所有的课程安排
      $allCourses = AttachCourse::with('course')->get();

      if(!empty($all_courses))
      {
        $allCourses = $all_courses;
      }

      if(empty($year)){
         $year = Carbon::now()->format('Y');
      }
     
      #指定年所有的课程安排
      $yearAllDates = $this->generateDates($year);

      #今日日期
      $now_date =  Carbon::now();

      #今日在本年中第几天
      $now_year = Carbon::now()->dayOfYear;


      #时间组合并
      foreach ($yearAllDates as $yearKey => $date) {
            
            foreach ($allCourses as $key => $course) {

                if($date['week'] == $course->weekday && optional($course->course)->open_status == '开放'){
                     $dateTitle = '';

                     #判断是不是今天
                    if($now_year == time_parse($date['day'])->dayOfYear)
                    {
                       $dateTitle .= tag('[今日课程]<br />','red',false);
                    }

                    #判断是不是这周
                    if($this->getSameWeek($now_date,$date['day']))
                    {
                      $dateTitle .= tag('[本周课程]<br />','orange',false);
                    }

                    #展示文本
                    $dateTitle .= '课程名称:'.optional($course->course)->name.'<br />教室:'.$course->classroom_name.'<br />老师:'.$course->teacher_name.'<br />开始时间:'.$course->start_time.'<br />结束时间:'.$course->end_time;

                    $yearAllDates[$yearKey]['event'][] = $dateTitle;

                }
             
            }
            
      }

      return $yearAllDates;
    }

    //发送用户微信通知
    public function sendUserInform($user,$now_add1_week=null)
    {
        if(empty($now_add1_week))
        {
          $now_add1_week = WeekDays(Carbon::now()->addDay()->dayOfWeek);
        }

        #用户的课表
        $user_kebiaos = app('zcjy')->OrderRepo()->userCourseBiao($user);

        if(count($user_kebiaos) && isset($user_kebiaos[$now_add1_week]) && count($user_kebiaos[$now_add1_week]))
        {
          $user_kebiaos = $user_kebiaos[$now_add1_week];
            foreach ($user_kebiaos as $key => $kebiao) 
            {
                         $this->weixinText(
                              $user->openid,
                              '上课通知',
                              [
                              'first'=> '有课程即将开始',
                              'keyword1' => $kebiao['name'],//课程
                              'keyword2' => $kebiao['start_time'].'-'.$kebiao['end_time'],//时间
                              'keyword3' => $kebiao['classroom_name'],//地点
                              'remark' => '您的课程['.$kebiao['name'].']明天即将开始,请提前做好上课准备'
                              ],
                              '/user/course_biao'
                        );
            }
            return zcjy_callback_data('发送课程通知成功');
        }
        else{
            return zcjy_callback_data('没有课程需要通知',1);
        }
    }


    /**
     * [用户上课提醒通知]
     * @return [type] [description]
     */
    public function willStartCourseInform(){
        #前一天的星期
        $now_add1_week = WeekDays(Carbon::now()->addDay()->dayOfWeek);

        #所有用户的通知
        $users = User::whereNotNull('openid')
        ->whereNotNull('idcard_num')
        ->where('import_type','微信用户')
        ->get();

        foreach ($users as $key => $user) 
        {
            $this->sendUserInform($user,$now_add1_week);
        }

        return zcjy_callback_data('发送微信通知成功');

    }

    /**
     * [成功支付订单后通知]
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function successPayOrderInform($order=null)
    {   
        #订单支付成功
        if(!empty($order))
        {
          #订单用户
          $user = $order->user;
          #订单中的报名记录
          $joins = $order->joins;
          if(count($joins))
          {
            foreach ($joins as $key => $join) 
            {
                #正常报名单
                if($order->price){
                    $this->weixinText(
                      $user->openid,
                      '支付通知',
                      [
                      'first'=> '您的课程订单已经支付成功',
                      'keyword1' => Carbon::now(),
                      'keyword2' => '长江老年大学',
                      'keyword3' => $join->course_name,
                      'remark' => '您的课程订单已经成功提交给长江老年大学'
                      ]
                    );
                }#免费报名单
                else{
                        $this->weixinText(
                        $user->openid,
                        '支付通知',
                        [
                        'first'=> '您的活动已经报名成功',
                        'keyword1' => Carbon::now(),
                        'keyword2' => '长江老年大学',
                        'keyword3' => $join->course_name,
                        'remark' => '您的活动已经报名成功,信息已成功提交给长江老年大学'
                        ]
                      );
                }
            }
          }
        }
    }

    /**
     * 推送微信模板消息
     * @param  [type] $openId      [description]
     * @param  [type] $template_id [description]
     * @param  [type] $data        [description]
     * @return [type]              [description]
     */
    public function weixinText($openId,$template_id=null,$data=null,$link=null)
    {
        #自定义内容
        // $result = app('wechat.official_account')->customer_service->message($message)->to($openId)->send();
        
        ##模板消息模块
        #上课提醒
        #46agKZJM11jLKeIYbCr6jPKSeb51IDV16eTdoY1WUOE 
        #详细内容
        // {{first.DATA}}
        // 课程：{{keyword1.DATA}}
        // 时间：{{keyword2.DATA}}
        // 地点：{{keyword3.DATA}}
        // {{remark.DATA}}
        
        // 有课程即将开始
        // 课程：线性代数
        // 时间：[1,2节] 08:30-10:00
        // 地点：N304
        // 请开发者为用户提供定制提醒的选项，以免打扰。
        
        #报名成功提醒
        #Jjy6Fq_rLmHS5ukaJrD8Iqu1emHkIpGlCq9Cd63raww
        // {{first.DATA}}
        // 受理时间：{{keyword1.DATA}}
        // 申请学院：{{keyword2.DATA}}
        // 申请专业：{{keyword3.DATA}}
        // {{remark.DATA}}
        
        if(empty($template_id))
        {
          $template_id = 'Jjy6Fq_rLmHS5ukaJrD8Iqu1emHkIpGlCq9Cd63raww';
        }

        if($template_id == '支付通知')
        {
          $template_id = 'Jjy6Fq_rLmHS5ukaJrD8Iqu1emHkIpGlCq9Cd63raww';
        }
        elseif($template_id == '上课通知')
        {
          $template_id = '46agKZJM11jLKeIYbCr6jPKSeb51IDV16eTdoY1WUOE';
        }

        if(empty($data))
        {
          $data = [
                'first'=> '您的课程订单已经提交成功',
                'keyword1' => Carbon::now(),
                'keyword2' => '长江老年大学',
                'keyword3' => '老年课程',
                'remark' => '您的课程订单已经提交成功'
            ];
        }

        if(empty($link))
        {
          $link = '/user/orders';
        }

        app('wechat.official_account')->template_message->send([
            'touser' => $openId,
            'template_id' => $template_id,
            'url' => 'https://www.cjlndx.top'.$link,
            'data' => $data
        ]);

        return zcjy_callback_data('发送通知成功');
    }


}
