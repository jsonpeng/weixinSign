<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\AttentionCourse;
use App\Models\CourseJoin;
use App\Models\CourseCat;
use App\Models\Order;

use InfyOm\Generator\Common\BaseRepository;
use Config;
use Cache;
use Log;

/**
 * Class CourseRepository
 * @package App\Repositories
 * @version November 30, 2018, 4:20 pm CST
 *
 * @method Course findWithoutFail($id, $columns = ['*'])
 * @method Course find($id, $columns = ['*'])
 * @method Course first($columns = ['*'])
*/
class CourseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'cat_name',
        'cat_id',
        'content',
        'brief',
        'inside_price',
        'price',
        'max_num'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Course::class;
    }

    public function catCourses($cat_id,$front_show=true,$open_status = null)
    {
        $courses = Course::where('cat_id',$cat_id);

        if(!empty($open_status))
        {
            $courses = $courses->where('open_status',$open_status);
        }

        if($front_show)
        {
            $courses = frontShow($courses);
        }



        return $courses;
    }

    /**
     * [课程的价格]
     * @param  [type] $user   [description]
     * @param  [type] $course [description]
     * @return [type]         [description]
     */
    public function coursePrice($user,$course)
    {
        if(empty($user))
        {
            return $course->price;
        }
        else{
            if($user->type == '单位内部用户'){
                return $course->inside_price;
            }
            else{
                return $course->price;
            }
        }
    }

    /**
     * [获取缓存的对应分类下的课程]
     * @param  [type] $cat_id [description]
     * @return [type]         [description]
     */
    public function getCachecatCourses($cat_id)
    {
        return Cache::remember('zcjy_cat_courses_'.$cat_id,Config::get('web.shrottimecache'),function() use($cat_id){
                return $this->catCourses($cat_id,true,'开放')
                        ->with('attachs')
                        ->orderBy('created_at','desc')
                        ->get();
        });
    }


    /**
     * [获取course课程详情缓存]
     * @param  [type] $course_id [description]
     * @return [type]            [description]
     */
    public function getCacheCourseDetail($course_id)
    {
        return Cache::remember('zcjy_course_detail_'.$course_id,Config::get('web.shrottimecache'),function() use($course_id){
            return $this->findWithoutFail($course_id);
        });
    }

    //关注课程状态
    public function attionCourseStatus($user_id,$course_id)
    {
      return AttentionCourse::where('user_id',$user_id)->where('course_id',$course_id)->first();
    }

    //发起关注课程
    public function actionAttionCourse($user_id,$course_id)
    {
        $attention_status =  $this->attionCourseStatus($user_id,$course_id);

        #已经关注过
        if($attention_status)
        {
            $attention_status->delete();
            return zcjy_callback_data('取消收藏成功');
        }
        else{
            AttentionCourse::create([
                'user_id'   => $user_id,
                'course_id' => $course_id
            ]);
            return zcjy_callback_data('收藏成功');
        }

    }

    /**
     * [用户关注过的课程列表]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    public function userAttionedCourses($user_id)
    {
        $attention_courses = AttentionCourse::where('user_id',$user_id)->get();
        $courses_arr = [];
        foreach ($attention_courses as $key => $val) {
             $courses_arr[] = $val->course_id;
        }
        $courses = Course::whereIn('id',$courses_arr)->with('attachs')->get();
        return $courses;
    }

    //发起课程删除
    public function actionDelCourse($course_join_id)
    {
        
        $course_join = CourseJoin::find($course_join_id);

        if(empty($course_join))
        {
            return zcjy_callback_data('没有找到该参与的课程',1);
        }

        $order_id = $course_join->order_id;

        #如果参与的已经加入订单
        if(!empty($order_id))
        {

            $order = $course_join->order;

            #删除这次参与
            $course_join->delete();

            if(!empty($order) && $order->pay_status == '未支付'){
                   $sum_price= CourseJoin::where('order_id',$order_id)->sum('price');

                   #更新订单总价
                   $order->update(['price'=>$sum_price,'remark'=>'支付课程费用'.$sum_price.'元']);

                   if($sum_price == 0 && CourseJoin::where('order_id',$order_id)->count() == 0)
                    {
                        $order->delete();
                    }
            }

        }
        else{

         $course_join->delete();

        }

        return zcjy_callback_data('删除成功');
    }

    /**
     * 自动处理开放状态
     * @return [type] [description]
     */
    public function autoCloseOpenStatus()
    {
        $course_cats = CourseCat::where('type','课程班')->get();

        $course_cat_ids = [];

        foreach ($course_cats as $key => $cat) 
        {
           $course_cat_ids[] = $cat->id;
        }

        $courses = Course::whereIn('id',$course_cat_ids)
        ->where('open_status','开放')
        ->get();

        foreach ($courses as $key => $course) 
        {
            if(app('zcjy')->varifyOverdue($course->course_end_time,'lt'))
            {
                //Log::info($course->name.'过期');
                $course->update(['open_status'=>'关闭']);
            }
        }
    }

    //发起课程添加
    public function actionAddCourse($user,$course_id)
    {

        if(getSettingValueByKey('sign_open_status') == '关闭')
        {
            return zcjy_callback_data('当前系统课程报名通道已关闭!无法继续报名',1);
        }

        $course = $this->findWithoutFail($course_id);

        if(empty($course))
        {
            return zcjy_callback_data('没有找到该课程',1);
        }

        if(getSettingValueByKey('all_sign_permission'))
        {

            if($user->type == '普通用户')
            {
                return zcjy_callback_data('目前只有内部员工可报名课程,谢谢合作!',1);
            }

        }

        #检测一下有效期
        
        ## 如果是活动 要在报名时间内才可以报名
        if($course->Type == '活动')
        {
            #检查报名时间
            
            ##检查报名开始时间
            if(app('zcjy')->varifyOverdue($course->sign_time,'gt'))
            {
                return zcjy_callback_data('报名开始时间还未到,请等待开始时间后报名',1);
            }

            ##检查报名结束时间
            if(app('zcjy')->varifyOverdue($course->sign_time_end,'lt'))
            {
                return zcjy_callback_data('报名结束时间已经截止,请在有效时间内报名',1);
            }


        }
        else{
            ## 如果是课程班
            
            ##检查截止时间
            if(app('zcjy')->varifyOverdue($course->course_end_time,'lt'))
            {
                return zcjy_callback_data('报名结束时间已经截止,请在有效时间内报名',1);
            }
        }

        if($course->open_status == '关闭')
        {
            return zcjy_callback_data('当前课程状态已关闭!无法继续报名',1);
        }
        
        //删除之前没有支付的订单和记录
        app('zcjy')->OrderRepo()->deleteUnPayOrder($user->id);

        $unPayJoinNum = CourseJoin::where('user_id',$user->id)->where('course_id',$course_id)->whereNull('order_id')->count();

        #之前已经添加过该课程需要先去结算才能继续付
        if($unPayJoinNum)
        {
            $join = CourseJoin::where('user_id',$user->id)
            ->where('course_id',$course_id)
            ->whereNull('order_id')
            ->first();

            if($join->join_status == '超额参与' && $course->NowJoin >= $course->max_num)
            {
                return zcjy_callback_data('当前课程报名人数已满,您的报名记录已提交给系统后台,有新的课程开设将会第一时间联系您!',1);
            }
            elseif($join->join_status == '正常参与')
            {
                return zcjy_callback_data('该课程已被添加过请先去结算',1);
            }

            $unPayJoinStatus = false;

            #如果参与数量大于1的情况
            if($unPayJoinNum > 1)
            {
                $joins =  CourseJoin::where('user_id',$user->id)
                ->where('course_id',$course_id)
                ->whereNull('order_id')
                ->get();

                 foreach ($joins as $key => $join) 
                 {
                    if($join->join_status == '正常参与')
                    {
                        $unPayJoinStatus = '该课程已被添加过请先去结算';
                    }
                 }

                 if($unPayJoinStatus)
                 {
                    return zcjy_callback_data($unPayJoinStatus,1);
                 }
            }

        }

        #已经报名过该课程的 无法重复报名
        if(CourseJoin::where('user_id',$user->id)->where('course_id',$course_id)->whereNotNull('order_id')->count())
        {
            $order_id = CourseJoin::where('user_id',$user->id)->where('course_id',$course_id)->whereNotNull('order_id')->first()->order_id;

            $order = app('zcjy')->OrderRepo()->findWithoutFail($order_id);

            #并且订单要已经支付过的
            if(isset($order) && $order->pay_status == '已支付')
            {
                return zcjy_callback_data('该课程您已报名过',1);
            }
        }

        #之前有为支付订单的需要先完成支付后才能继续添加
        if(!empty(app('zcjy')->OrderRepo()->userNoPayOrder($user)))
        {
            return zcjy_callback_data('您有未支付的订单,请先完成支付后添加',1);       
        }

        #普通用户不能参加兴趣小组和活动
        if($user->type == '普通用户')
        {
            if($course->cat_name == '兴趣小组' || $course->cat_name == '活动')
            {
                return zcjy_callback_data('你当前不符合报名条件',1);
            }
        }

        $join_status = '正常参与';

        #报名人数已满 记录保留但是不会生成订单
        if($course->NowJoin >= $course->max_num)
        {
            $join_status = '超额参与';
        }   


        #普通用户添加 /内部员工添加
        $price = $course->price;
        $type = '普通';

        #单位内部用户设置
        if($user->type == '单位内部用户')
        {
            $price = $course->inside_price;
            $type =  '内部员工优惠';
        }

        $joinYear = getSettingValueByKey('sign_year') ? getSettingValueByKey('sign_year') : '2019';

        $joinQuat = getSettingValueByKey('sign_quarter') ? getSettingValueByKey('sign_quarter') : '1';

        #报名记录添加
        $course_join = CourseJoin::create([
            'course_id'     => $course->id,
            'price'         => $price,
            'course_name'   => $course->name,
            'course_des'    => $course->brief,
            'type'          => $type,
            'user_id'       => $user->id,
            'join_status'   => $join_status,
            'join_year'     => $joinYear,
            'join_quarter'  => $joinQuat
        ]);

        if($join_status == '超额参与')
        {
            return zcjy_callback_data('当前课程报名人数已满,您的报名记录已提交给系统后台,有新的课程开设将会第一时间联系您!',1);
        }

    
        #价格没有就直接报名成功结算订单
        if($price == 0)
        {
            $order = app('zcjy')->OrderRepo()->generateZeroOrder($user,$course_join);
            return zcjy_callback_data('报名成功');
        }

        return zcjy_callback_data('添加成功');

    }

    //用户已经添加的课程
    public function userAddedCourses($user)
    {
        return [
            'courses'=>CourseJoin::where('user_id',$user->id)
            ->whereNull('order_id')
            ->where('join_status','正常参与')
            ->with('course.attachs')
            ->get(),
            'price'=>CourseJoin::where('user_id',$user->id)
            ->whereNull('order_id')
            ->where('join_status','正常参与')
            ->sum('price')
           ];
    }

    

}
