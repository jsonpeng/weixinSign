<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\CourseJoin;
use App\Models\AttachCourse;

use InfyOm\Generator\Common\BaseRepository;

use Cache;
use Config;

/**
 * Class OrderRepository
 * @package App\Repositories
 * @version November 30, 2018, 4:56 pm CST
 *
 * @method Order findWithoutFail($id, $columns = ['*'])
 * @method Order find($id, $columns = ['*'])
 * @method Order first($columns = ['*'])
*/
class OrderRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'price',
        'number',
        'user_id',
        'pay_platform',
        'pay_status',
        'remark'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Order::class;
    }

    public function joinsLog($orders)
    {
        $orders_id = [];
        foreach ($orders as $key => $order) 
        {
           $orders_id[] = $order->id;
        }
        return CourseJoin::whereIn('order_id',$orders_id)->get();
    }

    /**
     * [删除没有支付的订单并且连报名记录也一起]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    public function deleteUnPayOrder($user_id)
    {
       #先删除之前未支付的订单
       $orders =  Order::where('user_id',$user_id)->where('pay_status','未支付')->get();

       if(count($orders))
       {
         foreach ($orders as $key => $order) 
         {
             #删除报名记录
             CourseJoin::where('order_id',$order->id)->delete();
             #删除订单
             $order->delete();
         }
       }

    }


    //生成订单
    public function generateOrder($user,$price,$pay_platform)
    {   
        #先删除之前未支付的订单
        //Order::where('user_id',$user->id)->where('pay_status','未支付')->delete();

        #创建订单
        $order = Order::create([
            'price'         => $price,
            'user_id'       => $user->id,
            'pay_platform'  => $pay_platform,
            'pay_status'    => '未支付',
            'remark'        => '支付课程费用'.$price.'元'
        ]);

        #更新订单号
        $order->update(['number'=>time().'_'.$order->id]);

        #更新用户已经添加的
        CourseJoin::where('user_id',$user->id)->whereNull('order_id')->where('join_status','正常参与')->update(['order_id'=>$order->id]);
        
        return $order;
    }

    //生成免费报名单
    public function generateZeroOrder($user,$course_join)
    {
        #创建订单
        $order = Order::create([
            'price'         => 0,
            'user_id'       => $user->id,
            'pay_platform'  => '无',
            'pay_status'    => '已支付',
            'remark'        => '免费活动报名'
        ]);
        #更新订单号
        $order->update(['number'=>time().'_'.$order->id]);
        $course_join->update(['order_id'=>$order->id]);
        #发送微信通知
        app('zcjy')->successPayOrderInform($order);
        return $order;
    }

    //处理支付成功后的订单
    public function dealSuccessOrder($number)
    {
        $order = Order::where('number',$number)->first();

        if(empty($order))
        {
            return;
        }

        if($order->pay_status == '已支付')
        {
            return;
        }

        $order->update(['pay_status'=>'已支付']);
        #发送微信通知
        app('zcjy')->successPayOrderInform($order);
    }

    //用户的历史订单
    public function userOrders($user)
    {
         //return Cache::remember('zcjy_orders_'.$user->id,Config::get('web.shrottimecache'),function() use($user){
                return ['pay'=>
                         Order::where('user_id',$user->id)
                        ->where('pay_status','已支付')
                        ->with('joins.course.attachs')
                        ->orderBy('created_at','desc')
                        ->get(),
                        'nopay'=>
                        Order::where('user_id',$user->id)
                        ->where('pay_status','未支付')
                        ->with('joins.course.attachs')
                        ->orderBy('created_at','desc')
                        ->get(),
                        'nopay_all_price'=>
                        Order::where('user_id',$user->id)
                        ->where('pay_status','未支付')
                        ->sum('price'),
                    ];
            //});
    }

    //用户未支付的订单
    public function userNoPayOrder($user)
    {
        return  Order::where('user_id',$user->id)
                        ->where('pay_status','未支付')
                        ->first();
    }


    public function userJoinAttachs($user)
    {
        $user_orders = Order::where('user_id',$user->id)
                        ->where('pay_status','已支付')
                        ->with('joins.course.attachs')
                        ->orderBy('created_at','desc')
                        ->get();
        $attach_id = [];
        foreach ($user_orders as $key => $order) 
        {
                $joins = $order->joins;
                if(count($joins))
                {
                    foreach ($joins as $key => $join) 
                    {
                        $course = $join->course;
                        if(!empty($course))
                        {
                             $attachs = $course->attachs;
                            if(count($attachs))
                            {
                                foreach ($attachs as $key => $attach) 
                                {
                                    $attach_id[] = $attach->id;
                                }
                            }
                        }
                    }
                }
        }
        return AttachCourse::whereIn('id',$attach_id)->get();
    }

    //用户的课程表 前端显示
    public function userCourseBiao($user)
    {
        #已经支付的订单
        $user_orders = Order::where('user_id',$user->id)
                        ->where('pay_status','已支付')
                        ->with('joins.course.attachs')
                        ->orderBy('created_at','desc')
                        ->get();

        if(count($user_orders) == 0)
        {
            return [];
        }

        $week_plan = [
          '星期一'=>[],
          '星期二'=>[],
          '星期三'=>[],
          '星期四'=>[],
          '星期五'=>[],
          '星期六'=>[],
          '星期天'=>[]
        ];

        foreach ($user_orders as $key => $order) 
        {
            $joins = $order->joins;
            if(count($joins))
            {
                foreach ($joins as $key => $join) 
                {
                    $course = $join->course;
                    if(!empty($course) && $course->open_status == '开放')
                    {
                        $attachs = $course->attachs;
                        if(count($attachs))
                        {
                            foreach ($attachs as $key => $attach) 
                            {
                                if(isset($week_plan[$attach->weekday]))
                                {
                                    $week_plan[$attach->weekday][] = [
                                        'name'          => $course->name,
                                        'start_time'    => $attach->start_time,
                                        'end_time'      => $attach->end_time,
                                        'classroom_name'=> $attach->classroom_name
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        return $week_plan;
    }

    public function userSuccessPayOrders($user_id)
    {
        $orders =  Order::where('user_id',$user_id)->where('pay_status','已支付')->get();

        foreach ($orders as $key => $order) 
        {
           if(CourseJoin::where('order_id',$order->id)->count() > 0)
            {
                unset($orders[$key]);
            }
        }
        return $orders;
    }

}
