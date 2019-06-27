<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use App\Models\Cities;
use App\Models\Setting;
use App\Models\Project;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Carbon\Carbon;
//微信
use EasyWeChat\Factory;
include_once "wxBizDataCrypt.php";
include_once "AliyunOssUpload.php";


  function alipaySearchOrder($date = null)
  {    
      $request = new \AlipayDataDataserviceBillDownloadurlQueryRequest();
      $request->setBizContent("{" .
      "\"bill_type\":\"trade\"," .
      "\"bill_date\":\"".$date."\"" .
      "  }");

      $config = Config::get('alipay');
      $payResponse = new \AlipayTradeService($config);

      $result = $payResponse->aopclientRequestExecute($request); 

      $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
      $resultCode = $result->$responseNode->code;
      if(!empty($resultCode)&&$resultCode == 10000){
     // echo "成功";
      
      } else {
      echo "失败";
      }
      dd($result);
  }

  /**
   * 支付宝查单个账单
   * @param  [type] $out_trade_no [description]
   * @return [type]               [description]
   */
  function alipaySearchOneOrder($out_trade_no = null)
  {    
      $request = new AlipayTradeQueryRequest ();
      $request->setBizContent("{" .
          "\"out_trade_no\":\"".$out_trade_no."\"," .
          "\"trade_no\":\"\"," .
          "\"org_pid\":\"\"" .
          "  }");

      $config = Config::get('alipay');
      $payResponse = new \AlipayTradeService($config);

      $result = $payResponse->aopclientRequestExecute($request); 

      $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
      $resultCode = $result->$responseNode->code;
      if(!empty($resultCode)&&$resultCode == 10000){
        return zcjy_callback_data($result->alipay_trade_query_response);
      } else {
        return zcjy_callback_data('查询失败,订单不存在或未支付',1);
      }
      // dd($result);
  }

  /**
   * 微信查单个账单
   * @param  [type] $out_trade_no [description]
   * @return [type]               [description]
   */
  function wechatSearchOneOrder($out_trade_no = null)
  {
     $app = Factory::payment(Config::get('wechat.payment.default'));
     return zcjy_callback_data($app->order->queryByOutTradeNumber($out_trade_no));
  }


  //用php从身份证中提取生日,包括位和位身份证
  function getIDCardInfo($idcard)
  {
    return strlen($idcard)==15 ? ('19' . substr($idcard, 6, 6)) : substr($idcard, 6, 6);
  }

  function endYear()
  {
    return Carbon::now()->endOfYear();
  }

  /**
   * 下几年
   * @param  integer $years [description]
   * @return [type]         [description]
   */
  function nextYear($years=1)
  {
    return Carbon::now()->addYears($years);
  }

  
  /**
   * [多维数组手动去重并且统计重复次数]
   * @param  [array] $arr  [需要去重的数组]
   * @param  string $keys  [需要去重的字段]
   * @param  string $count [得到的统计重复次数]
   * @return [type]        [返回原数组并且带上重复次数]
   */
  function titleCountTimes($arr,$keys='title',$count='count'){
      $b=array();
      foreach($arr as $v){
          $b[]=$v[$keys];
      }
      $c=array_unique($b);
      foreach($c as $key => $v){
          $n=0;
          foreach($arr as $t){
              if($v==$t[$keys])
                  $n++;
          }
          $arr[$key][$count] = $n;
      }
      foreach ($arr as $key => $v) {
        if(!isset($v[$count])){
          unset($arr[$key]);
        }
      }
      return $arr;
  }

/**
 * [前端显示]
 * @param  [type] $cats [description]
 * @return [type]       [description]
 */
function frontShow($attr,$column = 'show')
{
  return $attr->where($column,1);
}

//时间处理为Carbon类
function time_parse($time){
  return Carbon::parse($time);
}

/**
 * 星期
 * @return [type] [description]
 */
function WeekDays($key=null){
     $weekdays = [
        '0'=>'星期天',
        '1'=>'星期一',
        '2'=>'星期二',
        '3'=>'星期三',
        '4'=>'星期四',
        '5'=>'星期五',
        '6'=>'星期六'
      ];
      if(is_numeric($key))
      {
        return isset($weekdays[$key]) ? $weekdays[$key] : null;
      }
      else{
        return $weekdays;
      }

}

//替换字符空格
function dealStrSpace($str)
{
  //return preg_replace('/S(\d+):/','S:'.'\r\n',$str);
  return $str;
}

function weixinDecodeTel($sessionKey,$encryptedData,$iv){
    $appid = Config::get('wechat.mini_program.default.app_id');
    $pc = new WXBizDataCrypt($appid, $sessionKey);
    $errCode = $pc->decryptData($encryptedData, $iv, $data);
    if ($errCode == 0) {
        return $data;
    } 
    else {
        return $errCode;
    }
}


//功能：计算两个时间戳之间相差的日时分秒
//$begin_time  开始时间戳
//$end_time 结束时间戳
function time_diff($begin_time,$end_time)
{
      if($begin_time < $end_time){
         $starttime = $begin_time;
         $endtime = $end_time;
      }else{
         $starttime = $end_time;
         $endtime = $begin_time;
      }

      //计算天数
      $timediff = $endtime-$starttime;
      $days = intval($timediff/86400);
      //计算小时数
      $remain = $timediff%86400;
      $hours = intval($remain/3600);
      //计算分钟数
      $remain = $remain%3600;
      $mins = intval($remain/60);
      //计算秒数
      $secs = $remain%60;
      $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
      return $res;
}


function user_by_id($id){
    //return Cache::remember('zcjy_user_by_id_'.$id, Config::get('web.shrottimecache'), function() use ($id) {
        try {
           return User::find($id);
        } catch (Exception $e) {
            return null;
        }
     
    //});
}

//所有选项
function select_all($array=true){
    $select =  ['0','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    return $array ? $select : implode(',',$select);
}

//选项序号取出字符
function select_sort($num){
    $select = select_all();
    return isset($select[$num]) ? $select[$num] : '';
}


function select_sort_num($num){
    $select = ['A'=>1,'B'=>2,'C'=>3,'D'=>4];
    return isset($select[$num]) ? $select[$num] : '';
}


//当前api用户
function zcjy_api_user($input){
     $token = explode('_', zcjy_base64_de($input['token']));
     return empty(session('zcjy_api_user_'.$token[0])) ? user_by_id($token[0]) : session('zcjy_api_user_'.$token[0]);
}

//加密
function zcjy_base64_en($str){
    $str = str_replace('/','@',str_replace('+','-',base64_encode($str)));
    return $str;
}

//解密
function zcjy_base64_de($str){
    $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
    $str = base64_decode(str_replace('@','/',str_replace('-','+',$str)));
    $encoded = mb_detect_encoding($str, $encode_arr);
    $str = iconv($encoded,"utf-8",$str);
    return $str;
}

/**
 * [接口请求回转数据格式]
 * @param  type    $data     [成功/失败提示]
 * @param  integer $code     [0成功 1失败]
 * @param  string  $api      [默认不传是api格式 传web是web格式]
 * @return [type]            [description]
 */
function zcjy_callback_data($data=null,$code=0,$api='web'){
    return $api === 'api'
        ? api_result_data_tem($data,$code)
        : web_result_data_tem($data,$code);
}

/**
 * [把文字加粗并且变色]
 * @param  [type] $string [文字]
 * @param  string $color  [颜色 默认红色]
 * @return [type]         [description]
 */
function tag($string,$color='red',$nbsp=true){
    return $nbsp ? '&nbsp;&nbsp;<strong style=color:'.$color.'>'.$string.'</strong>&nbsp;&nbsp;' :'<strong style=color:'.$color.'>'.$string.'</strong>';
}



/**
 * [把文字变成链接 并且带上颜色]
 * @param  [type]  $string [文字]
 * @param  [type]  $link   [链接]
 * @param  string  $color  [颜色 默认橙色]
 * @param  boolean $nbsp   [是否加左右间隔]
 * @return [type]          [description]
 */
function a_link($string,$link='javascript:;',$color='orange',$nbsp=true,$target='_blank'){
      $color == 'cancle_target' 
      ?
       $target = false 
       : '';
      $color = 'orange';
     return $nbsp ? '&nbsp;&nbsp;<a href='.$link.' style=color:'.$color.'>'.$string.'</a>&nbsp;&nbsp;' : '<a target=_blank href='.$link.' style=color:'.$color.'>'.$string.'</a>';
}


/**
 * [api接口请求回转数据]
 * @param  [type]  $message  [成功/失败提示]
 * @param  integer $code     [0成功 1失败]
 * @return [type]            [description]
 */
function api_result_data_tem($data=null,$status_code=0){
     return response()->json(['status_code'=>$status_code,'data'=>$data]);
     // return response()->json(['code' => 404, 'msg' => '未找到对应用户'], 404, [header('Access-Control-Allow-Origin:http://www.xyzabc.com.cn')]);
}

/**
 * [web程序请求回转数据]
 * @param  [type]  $message  [成功/失败提示]
 * @param  integer $code     [0成功 1失败]
 * @return [type]            [description]
 */
function web_result_data_tem($message=null,$code=0){
    return response()->json(['code'=>$code,'message'=>$message]);
}

function modelRequiredParam($model,$return_array=false){
    $requireds = $model::$rules;
    $attr = [];
    foreach ($requireds as $key => $value) {
        array_push($attr,$key);
    }
    $attr = !$return_array ? implode(',',$attr) : $attr;
   return $attr;
}


function getSettingValueByKey($key){
     return app('setting')->valueOfKey($key);
}

function getSettingValueByKeyCache($key){
    return Cache::remember('getSettingValueByKey'.$key, Config::get('web.cachetime'), function() use ($key){
        return getSettingValueByKey($key);
    });
}


function funcOpen($func_name)
{
    $config  = Config::get('web.'.$func_name);
    return empty($config) ? false : $config;
}

function funcOpenCache($func_name)
{
    return Cache::remember('funcOpen'.$func_name, Config::get('web.cachetime'), function() use ($func_name){
        return funcOpen($func_name);
    });
}

function arrayToString($re1){
    $str = "";
    $cnt = 0;
    foreach ($re1 as $value)
    {
        if($cnt == 0) {
            $str = $value;
        }
        else{
            $str = $str.','.$value;
        }
        $cnt++;
    }
}

//修改env
function modifyEnv(array $data)
{
    $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

    $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

    $contentArray->transform(function ($item) use ($data){
        foreach ($data as $key => $value){
            if(str_contains($item, $key)){
                return $key . '=' . $value;
            }
        }
        return $item;
    });

    $content = implode($contentArray->toArray(), "\n");

    \File::put($envPath, $content);
}

function array_remove($arr, $key){
    if(!array_key_exists($key, $arr)){
        return $arr;
    }
    $keys = array_keys($arr);
    $index = array_search($key, $keys);
    if($index !== FALSE){
        array_splice($arr, $index, 1);
    }
    return $arr;

}



/**
 * 指定位置插入字符串
 * @param $str  原字符串
 * @param $i    插入位置
 * @param $substr 插入字符串
 * @return string 处理后的字符串
 */
function insertToStr($str, $i, $substr){
    //指定插入位置前的字符串
    $startstr="";
    for($j=0; $j<$i; $j++){
        $startstr .= $str[$j];
    }

    //指定插入位置后的字符串
    $laststr="";
    for ($j=$i; $j<strlen($str); $j++){
        $laststr .= $str[$j];
    }

    //将插入位置前，要插入的，插入位置后三个字符串拼接起来
    $str = $startstr . $substr . $laststr;

    //返回结果
    return $str;
}


function getCitiesNameById($cities_id)
{
    $city=Cities::find($cities_id);
    if(!empty($city)) {
        return $city->name;
    }else{
        return null;
    }
}

/**
 * 验证是否展开
 * @return [int] [是否展开tools 0不展开 1展开]
 */
function varifyTools($input,$order=false){
    $tools=0;
    if(count($input)){
        $tools=1;
        if(array_key_exists('page', $input) && count($input)==1) {
            $tools = 0;
        }
        if($order){
            if(array_key_exists('menu_type', $input) && count($input)==1) {
                $tools = 0;
            }
        }
    }
    return $tools;
}

/**
 * 倒序分页显示
 * @parameter [object]
 * @return [array] [desc]
 */
function descAndPaginateToShow($obj){
    if(!empty($obj)){
      return  $obj->orderBy('created_at','desc')->paginate(defaultPage());
    }else{
        return [];
    }
}

/**
 * 默认分页数量
 * @parameter []
 * @return [int] [每页显示数量]
 */
function defaultPage(){
    return empty(getSettingValueByKey('records_per_page')) ? 15 : getSettingValueByKey('records_per_page');
}


//截取内容
function sub_content($str, $num=120){
        global $Briefing_Length;
        mb_regex_encoding("UTF-8");
        $Foremost = mb_substr($str, 0, $num);
        $re = "<(\/?) 
    (P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|TABLE|TR|TD|TH|INPUT|SELECT|TEXTAREA|OBJECT|A|UL|OL|LI| 
    BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|SPAN)[^>]*(>?)";
        $Single = "/BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|BR/i";

        $Stack = array(); $posStack = array();

        mb_ereg_search_init($Foremost, $re, 'i');

        while($pos = mb_ereg_search_pos()){
            $match = mb_ereg_search_getregs();

            if($match[1]==""){
                $Elem = $match[2];
                if(mb_eregi($Single, $Elem) && $match[3] !=""){
                    continue;
                }
                array_push($Stack, mb_strtoupper($Elem));
                array_push($posStack, $pos[0]);
            }else{
                $StackTop = $Stack[count($Stack)-1];
                $End = mb_strtoupper($match[2]);
                if(strcasecmp($StackTop,$End)==0){
                    array_pop($Stack);
                    array_pop($posStack);
                    if($match[3] ==""){
                        $Foremost = $Foremost.">";
                    }
                }
            }
        }

        $cutpos = array_shift($posStack) - 1;
        $Foremost =  mb_substr($Foremost,0,$cutpos,"UTF-8");
        return strip_tags($Foremost);

}

//截取内容中的图片
function get_content_img($text){   
  
    //取得所有img标签，并储存至二维数组 $match 中   
    preg_match_all('/<img[^>]*>/i', $text, $match);   
      
    return $match;
}



//替换上传图片的url
function replace_img_url($image_attr){

   return str_replace("../../","/",implode('', $image_attr));
}


/**
 * 纠错信息的选项 多少个
 */
function getErrorList(){
      $list= preg_replace("/\n|\r\n/", "_",getSettingValueByKey('error_info_list'));
      $list_arr = explode('_',$list);
      return $list_arr;
}

/**
 * 项目金额的选项 多少个
 */
function projectMoneyList(){
      $list= preg_replace("/\n|\r\n/", "_",getSettingValueByKey('project_money_list'));
      $list_arr = explode('_',$list);
      return $list_arr;
}

function getFrontDefaultPage(){
    return empty(getSettingValueByKey('front_take'))?16:getSettingValueByKeyCache('front_take');
}


function iconv_system($str){   
    global $config; 
    $result = iconv($config['app_charset'], 
    $config['system_charset'], $str); 
      if (strlen($result)==0) {  
           $result = $str; 
       }   
       return $result;
}


function carbon_parse($time){
    return Carbon::parse($time);
}


//截取指定字符串前多少位
function des($str, $num=100){
        global $Briefing_Length;
        mb_regex_encoding("UTF-8");
        $Foremost = mb_substr($str, 0, $num);
        $re = "<(\/?) 
    (P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|TABLE|TR|TD|TH|INPUT|SELECT|TEXTAREA|OBJECT|A|UL|OL|LI| 
    BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|SPAN)[^>]*(>?)";
        $Single = "/BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|BR/i";

        $Stack = array(); $posStack = array();

        mb_ereg_search_init($Foremost, $re, 'i');

        while($pos = mb_ereg_search_pos()){
            $match = mb_ereg_search_getregs();

            if($match[1]==""){
                $Elem = $match[2];
                if(mb_eregi($Single, $Elem) && $match[3] !=""){
                    continue;
                }
                array_push($Stack, mb_strtoupper($Elem));
                array_push($posStack, $pos[0]);
            }else{
                $StackTop = $Stack[count($Stack)-1];
                $End = mb_strtoupper($match[2]);
                if(strcasecmp($StackTop,$End)==0){
                    array_pop($Stack);
                    array_pop($posStack);
                    if($match[3] ==""){
                        $Foremost = $Foremost.">";
                    }
                }
            }
        }

        $cutpos = array_shift($posStack) - 1;
        $Foremost =  mb_substr($Foremost,0,$cutpos,"UTF-8");
        return strip_tags($Foremost);
}