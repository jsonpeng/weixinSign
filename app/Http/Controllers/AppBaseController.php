<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Illuminate\Support\Facades\Artisan;
use Log;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Models\CourseJoin;

class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

     /**
     * 后台显示获取分页数目
     * @return [int] [分页数目]
     */
    public function defaultPage(){
        return empty(getSettingValueByKey('records_per_page')) ? 15 : getSettingValueByKey('records_per_page');
    }

    /**
     * 验证是否展开
     * @return [int] [是否展开tools 0不展开 1展开]
     */
    public function varifyTools($input,$order=false){
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
     * 倒序显示带分页
     */
    public function descAndPaginateToShow($obj,$attr="created_at",$sort="desc"){
       if(!empty($obj)){
      		return $obj->orderBy($attr,$sort)->paginate($this->defaultPage());
	    }else{
	        return [];
	    }
    }

    /**
     * 查询索引初始化状态
     */
    public function defaultSearchState($obj){
         if(!empty($obj)){
            return $obj::where('id','>',0);
         }else{
            return [];
         }
    }

    /**
     * [上传图片/文件]
     * @return [type] [description]
     */
    public function uploadFile(){
        // $oss = new \AliyunOssUpload();
        // return $oss->ossUpload();
        $file =  Input::file('file');
        return app('zcjy')->uploadFiles($file);
    }

    /**
     * [读取Excel信息并且生成题目]
     * @return [type] [description]
     */
    public function autoGenerateTopic(Request $request){
        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,['subject_id','sec','excel_path']);
        if($varify){
            return zcjy_callback_data($varify,1);
        }
        return app('zcjy')->readExcelsToGenerate($input['excel_path'],$input);
    }

    /**
     * [读取Excel信息并且生成用户信息]
     * @return [type] [description]
     */
    public function autoGenerateUser(Request $request){
        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,['excel_path']);
        if($varify){
            return zcjy_callback_data($varify,1);
        }
        return app('zcjy')->readExcelsToGenerateUser($input['excel_path']);
    }

    /**
     * [发送邮件]
     * @return [type] [description]
     */
    public function sendEmail()
    {
        return app('zcjy')->CourseJoinRepo()->sendEmailAttach();
    }

    /**
     * [发送微信通知]
     * @return [type] [description]
     */
    public function sendWeixinInform()
    {
        return app('zcjy')->willStartCourseInform();
    }

    /**
     * [用户的课程表]
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function userKeBiaos(Request $request,$id)
    {
        $user = app('zcjy')->UserRepo()->findWithoutFail($id);
        if(empty($user)){
            return zcjy_callback_data('没有找到该用户',1);
        }
        $attachs = app('zcjy')->OrderRepo()->userJoinAttachs($user);
        return zcjy_callback_data(app('zcjy')->allCourses($attachs));
    }

    //对账单
    public function checkOrder(Request $request)
    {
        $platform = $request->get('platform');
        $out_trade_no = $request->get('out_trade_no');
        if($platform == '支付宝')
        {
             return alipaySearchOneOrder($out_trade_no);
        }
        else{

            return wechatSearchOneOrder($out_trade_no);
        }
    }

    //操作订单
    public function actionOrder(Request $request,$id)
    {
        CourseJoin::where('id',$id)->update(['order_id'=>$request->get('order_id')]);
        return zcjy_callback_data('自动处理成功');
    }



}
