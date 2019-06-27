<?php

namespace App\Repositories;

use App\Models\CourseJoin;
use InfyOm\Generator\Common\BaseRepository;
use Carbon\Carbon;
use Mail;

/**
 * Class CourseJoinRepository
 * @package App\Repositories
 * @version November 30, 2018, 4:45 pm CST
 *
 * @method CourseJoin findWithoutFail($id, $columns = ['*'])
 * @method CourseJoin find($id, $columns = ['*'])
 * @method CourseJoin first($columns = ['*'])
*/
class CourseJoinRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'course_id',
        'price',
        'course_name',
        'course_des',
        'order_id',
        'type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CourseJoin::class;
    }

    /**
     * [后台显示]
     * @return [type] [description]
     */
    public function adminShow()
    {
        return CourseJoin::orderBy('created_at','desc')
            ->paginate(15);
    }

    public function userJoins($user)
    {
        return CourseJoin::where('user_id',$user->id)
              ->orderBy('created_at','desc')
              ->paginate(15);
    }

    //成功参与人数
    public function successJoinNum($course_id)
    {
        $joinYear = getSettingValueByKey('sign_year') ? getSettingValueByKey('sign_year') : '2019';
        $joinQuat = getSettingValueByKey('sign_quarter') ? getSettingValueByKey('sign_quarter') : '1';

        $joins = CourseJoin::whereNotNull('order_id')
                ->where('course_id',$course_id)
                ->where('join_year',$joinYear)
                ->where('join_quarter',$joinQuat)
                ->with(['order'=>function($query){
                     $query->where('pay_status','已支付');
                }])
                ->get();
        
        $joins = $joins->filter(function($item){
            return !empty($item->order);
        });

        return count($joins);
    }

    //昨天所有报名成功的记录
    public function successLog($time=null)
    {
        if(empty($time))
        {
            $time = Carbon::now()->subDay();
        }

        $start_time = time_parse($time)->startOfDay();
        //$end_time = time_parse($time)->addDay();
        $end_time = time_parse($time)->endOfDay();

        #已支付的参与
        $joins = CourseJoin::whereNotNull('order_id')
                ->whereBetween('created_at',[$start_time,$end_time])
                ->with('user')
                ->with(['order'=>function($query){
                     $query->where('pay_status','已支付');
                }])
                ->orderBy('created_at','asc')
                ->get();

        $joins = $joins->filter(function($item){
            return !empty($item->order);
        });

        // #超额的参与
        // $chao_joins =  CourseJoin::whereBetween('created_at',[$start_time,$end_time])
        //         ->where('join_status','超额参与')
        //         ->with('user')
        //         ->orderBy('created_at','asc')
        //         ->get();

        // if(count($chao_joins))
        // {
        //     $joins = $joins->concat($chao_joins);
        // }

        foreach ($joins as $key => $val) {
                $val['nickname'] = optional($val->user)->nickname;
                $val['name'] = optional($val->user)->name;
                $val['ret_unit'] = optional($val->user)->ret_unit;
                $val['time'] = $val->created_at;
        }
        return $joins;
    }


    //给指定人的邮箱发送邮件及附件
    public function sendEmailAttach()
    {
        $email = getSettingValueByKey('receive_data_email');
        $name = empty(getSettingValueByKey('name')) ? '长江老年大学' : getSettingValueByKey('name');

        $joins = $this->successLog();

        if(count($joins) == 0)
        {
            return zcjy_callback_data('昨日无报名记录',1);
        }

        if(!empty($email))
        {
            app('zcjy')->exportExcelTable(
                [
                    'name'          =>'报名人姓名',
                    'nickname'      =>'报名人微信昵称',
                    'course_name'   => '报名课程',
                    'course_des'    => '课程描述',
                    'type'          => '类型',
                    'price'         => '价格',
                    'join_status'   => '参与状态',
                    'time'          => '报名时间'
                ],
                $joins,
                'excel/log.xls');

            Mail::send('emails.index',['name'=>$name,'joins'=>$joins],function($message) use ($email,$name){
                $to = $email;
                $message->to($to)->subject('['.$name.']每日报名记录提醒');

                 $attachment = public_path('excel/log.xls');
                 //在邮件中上传附件
                 $message->attach($attachment,['as'=>'log.xls']);
            });

            $beiyong_email = getSettingValueByKey('beiyong_data_email');
            if(!empty($beiyong_email))
            {
                Mail::send('emails.index',['name'=>$name,'joins'=>$joins],function($message) use ($beiyong_email,$name){
                    $to = $beiyong_email;
                    $message->to($to)->subject('['.$name.']每日报名记录提醒');

                     $attachment = public_path('excel/log.xls');
                     //在邮件中上传附件
                     $message->attach($attachment,['as'=>'log.xls']);
                });
            }
            return zcjy_callback_data('发送邮件成功');
        }
        else{
            return zcjy_callback_data('请填写接收人邮箱',1);
        }

    }

}
