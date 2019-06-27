<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\Setting;
use Config;

use App\User;
use App\Models\Admin;
use App\Models\AttentionCourse;

use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class UserController extends AppBaseController
{

    //过滤处理特殊字节
     private  function filter($str) {      
        if($str){ 
            $name = $str; 
            $name = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $name); 
            $name = preg_replace('/xE0[x80-x9F][x80-xBF]‘.‘|xED[xA0-xBF][x80-xBF]/S','?', $name); 
            $return = json_decode(preg_replace("#(\\\ud[0-9a-f]{3})#ie","",json_encode($name))); 
            if(!$return){ 
                return $this->jsonName($return); 
            } 
        }else{ 
            $return = ''; 
        }     
        return $return; 
  
    }

    private  function emoji_encode($nickname){
          $strEncode = '';
          $length = mb_strlen($nickname,'utf-8');
          for ($i=0; $i < $length; $i++) {
              $_tmpStr = mb_substr($nickname,$i,1,'utf-8');
              if(strlen($_tmpStr) >= 4){
                  $strEncode .= '[[EMOJI:'.rawurlencode($_tmpStr).']]';
              }else{
                  $strEncode .= $_tmpStr;
             }
         }
        return $strEncode;
     }


    //批量导出
    public function reportMany(Request $request){
        if(User::orderBy('created_at','desc')->count() == 0){
            Flash::error('当前没有数据可以导出');
            return redirect(route('users.index'));
        }
        $time = Carbon::now()->format('Y-m-d H:i:s');
        $lists = User::orderBy('created_at','desc')->get();
        $con = $this;
        Excel::create('截止到'.$time.'用户记录', function($excel) use($lists,$con) {
            //第二列sheet
            $excel->sheet('用户记录列表', function ($sheet) use ($lists,$con) {
            $sheet->setWidth(array(
                'A' => 80,
                'B' => 14,
                'C' => 12,
                'D' => 60,
                'E' => 18
            ));
            $sheet->appendRow(array('微信昵称','手机号','注册时间'));
                //$lists = $lists->chunk(100, function($lists) use(&$sheet) {
                   // Log::info($lists);
                    //$item = $item->items()->get();
                        foreach ($lists as $key => $item) 
                        {
                            $sheet->appendRow(array(
                                $con->emoji_encode($item->nickname),
                                $item->mobile,
                                $item->created_at
                            ));
                        }
                  
                
            //});
        });
        })->download('xls');
    }

    public function index(Request $request){

        session(['userUrl'=>$request->fullUrl()]);

        $users = $this->defaultSearchState(app('zcjy')->UserRepo()->model()); 
        $input = $request->all();
        $input = array_filter($input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );

        $tools=1;

        $type = '普通用户';

        if(array_key_exists('type',$input))
        {
          
            $type = $input['type'];

            if($type == '单位内部用户')
            {
                $users = $users->where('import_status','未导入');
            }
            $users = $users->where('type',$type);
        }

        
        if(!array_key_exists('type',$input))
        {
           $users = $users->whereNotNull('openid');
        }

        if(array_key_exists('name', $input)){
            $users=$users->where('name','like','%'.$input['name'].'%');
        }

        if(array_key_exists('nickname', $input)){
            $users=$users->where('nickname','like','%'.$input['nickname'].'%');
        }

        if(array_key_exists('mobile', $input)){
            $users=$users->where('mobile','like','%'.$input['mobile'].'%');
        }

        if(array_key_exists('can_read_zj', $input)){
            if((int)$input['can_read_zj'] === 1){
                $users = $users->where('can_read_zj','1');
            }
            elseif((int)$input['can_read_zj'] === 2)
            {
                $users = $users->whereNull('can_read_zj');
            }
        }

        $users = $this->descAndPaginateToShow($users);
        return view('admin.user.index')
               ->with('users', $users)
               ->with('tools',$tools)
               ->with('input',$input);
    }

    public function edit($id){
        $users = app('zcjy')->UserRepo()->findWithoutFail($id);
        if(empty($users)){
            Flash::error('没有找到该用户');
            return redirect(route('users.index'));
        }
        return view('admin.user.edit')
        ->with('users',$users);
    }

    public function show($id)
    {
        $user = app('zcjy')->UserRepo()->findWithoutFail($id);
        if(empty($user)){
            Flash::error('没有找到该用户');
            return redirect(route('users.index'));
        }
        #报名列表
        $joins = app('zcjy')->CourseJoinRepo()->userJoins($user);
        #收藏列表
        $courses = app('zcjy')->CourseRepo()->userAttionedCourses($id);
        #课表信息
        return view('admin.user.show',compact('user','joins','courses'));
    }

    //指定用户发送微信通知文本
    public function sendWeiXinText($id,Request $request)
    {
        $user = app('zcjy')->UserRepo()->findWithoutFail($id);

        if(empty($user))
        {
           return zcjy_callback_data('没有找到该用户',1);
        }

        return app('zcjy')->sendUserInform($user);
    }

    public function update($id,Request $request)
    {
        $users = app('zcjy')->UserRepo()->findWithoutFail($id);

        if(empty($users)){
            Flash::error('没有找到该用户');
        }

        $users->update(['type'=>$request->get('type')]);

        ##更新已经加入到购物车的数据
        $all_attr = app('zcjy')->CourseRepo()->userAddedCourses($users);

        $courses = $all_attr['courses'];

        if(count($courses))
        {
            foreach ($courses as $key => $courseJoin) 
            {
               $course = $courseJoin->course()->first();
               $price =app('zcjy')->CourseRepo()->coursePrice($users,$course);
               $courseJoin->update(['price'=>$price]);
            }
        }

        if($request->has('_reset'))
        {
            $users->update([
                'mobile'       => '',
                'name'         => '',
                'birthday'     => '',
                'ret_unit'     => '',
                'idcard_num'   => '',
                'import_status'=> '未导入'
            ]);
            Flash::success('重置用户注册信息成功');
        }
        else{
            Flash::success('更新用户成功');
        }

        return redirect(session('userUrl'));
    }

    //更新查看专家权限动作
    public function updateZjAction($id)
    {
        $user = app('zcjy')->UserRepo()->findWithoutFail($id);

        if(empty($user))
        {
            Flash::error('没有找到该用户');
        }

        $canAction = $user->can_read_zj ? null : 1;

        $user->update(['can_read_zj'=>$canAction]);

        Flash::success('更新用户查看权限成功');

        return redirect(session('userUrl'));
    }

    //删除动作
    public function deleteAction($id,Request $request)
    {
        $user = app('zcjy')->UserRepo()->findWithoutFail($id);

        if(empty($user))
        {
            Flash::error('没有找到该用户');
        }

        //删除附带信息
        AttentionCourse::where('user_id',$id)->delete();

        $user->delete();

        Flash::success('删除用户成功');

        return redirect(session('userUrl'));
    }
}
