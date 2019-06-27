<?php

namespace App\Repositories;

use App\Models\AttachCourse;
use App\Models\Course;
use InfyOm\Generator\Common\BaseRepository;


/**
 * Class AttachCourseRepository
 * @package App\Repositories
 * @version November 30, 2018, 4:31 pm CST
 *
 * @method AttachCourse findWithoutFail($id, $columns = ['*'])
 * @method AttachCourse find($id, $columns = ['*'])
 * @method AttachCourse first($columns = ['*'])
*/
class AttachCourseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'weekday',
        'start_time',
        'end_time',
        'classroom_name',
        'teacher_name',
        'course_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AttachCourse::class;
    }

    public function courseAttachs($course)
    {
        return AttachCourse::where('course_id',$course->id)->get();
    }

    //去除课程关联的信息
    public function delAllCourseAttach($course)
    {   
        return AttachCourse::where('course_id',$course->id)->delete();
    }

    /**
     * [课程操作]
     * @param  [type] $input  [description]
     * @param  [type] $course [description]
     * @param  string $action [description]
     * @return [type]         [description]
     */
    public function actionAttachCourse($input,$action='create',$course=null)
    {

        #操作状态 返回true说明参数欠缺
        $action_status = app('zcjy')->varifyInputParam($input,'classroom_name,weekday,teacher_name,start_time,end_time');

        $input = app('zcjy')->dealInputStringToArr($input,'classroom_name,weekday,teacher_name,start_time,end_time');

        if($action_status) return true;

        #处理下这几个参数中存在空数组的
        $action_attach_status = false;
        foreach ($input['classroom_name'] as $key => $val) {
            if(!isset($input['classroom_name'][$key]) || !isset($input['weekday'][$key]) || !isset($input['teacher_name'][$key]) || !isset($input['start_time'][$key]) || !isset($input['end_time'][$key])){
                $action_attach_status = true;
            }
        }

      

        if(!$action_status && !$action_attach_status){

            if($action == 'create'){

                $course = Course::create([
                        'name' => $input['name'],
                        'cat_name'=> $input['cat_name'],
                        'cat_id'=> $input['cat_id'],
                        'content'=> $input['content'],
                        'brief'=> !isset($input['brief']) ? des($input['content'],50) : $input['brief'],
                        'inside_price'=> $input['inside_price'],
                        'price'=> $input['price'],
                        'max_num'=> $input['max_num'],
                        'now_num' => $input['now_num'],
                        'code'    => $input['code'],
                        'course_end_time'=> $input['course_end_time']
                ]);
                
            }
            elseif($action == 'update'){
                $course->update([
                        'name' => $input['name'],
                        'cat_name'=> $input['cat_name'],
                        'cat_id'=> $input['cat_id'],
                        'content'=> $input['content'],
                        'brief'=> $input['brief'],
                        'inside_price'=> $input['inside_price'],
                        'price'=> $input['price'],
                        'max_num'=> $input['max_num'],
                        'code'    => $input['code'],
                        'course_end_time'=> $input['course_end_time']
                ]);
                $this->delAllCourseAttach($course);
            }

            foreach ($input['classroom_name'] as $key => $val) {
               
                AttachCourse::create([
                    'classroom_name' => $val,
                    'weekday'        =>$input['weekday'][$key],
                    'teacher_name'   =>$input['teacher_name'][$key],
                    'start_time'     =>$input['start_time'][$key],
                    'end_time'       =>$input['end_time'][$key],
                    'course_id'      =>$course->id
                ]);
            }

        }
      
        return $action_status || $action_attach_status;

    }

}
