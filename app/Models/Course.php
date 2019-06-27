<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CourseJoin;

/**
 * Class Course
 * @package App\Models
 * @version November 30, 2018, 4:20 pm CST
 *
 * @property string name
 * @property string cat_name
 * @property integer cat_id
 * @property string content
 * @property string brief
 * @property string inside_price
 * @property float price
 * @property integer max_num
 */
class Course extends Model
{
    use SoftDeletes;

    public $table = 'courses';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'cat_name',
        'cat_id',
        'content',
        'brief',
        'inside_price',
        'price',
        'max_num',
        'now_num',
        'activity_time',
        'sign_time',
        'sign_time_end',
        'code',
        'show',
        'open_status',
        'end_time',
        'course_end_time'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'cat_name' => 'string',
        'cat_id' => 'integer',
        'content' => 'string',
        'brief' => 'string',
        'inside_price' => 'string',
        'price' => 'float',
        'max_num' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'inside_price' => 'required',
        'content' => 'required',
        'price' => 'required',
        'max_num' => 'required'
    ];

    public function attachs()
    {
        return $this->hasMany('App\Models\AttachCourse','course_id','id');
    }


    public function getTypeAttribute()
    {
        return $this->cat_name == '兴趣小组' || $this->cat_name == '活动' ? '活动' : '课程班';
    }

    public function getUrlAttribute()
    {
        $type = $this->Type;
        if($type == '活动')
        {
            return '/like_group/'.$this->id;
        }
        else{
            return '/course/'.$this->id;
        }
    }

    //现在参与的人
    public function getNowJoinAttribute()
    {
        return app('zcjy')->CourseJoinRepo()->successJoinNum($this->id);
        //CourseJoin::where('course_id',$this->id)->where('join_status','正常参与')->count();
    }

    public function getIsExcessAttribute()
    {
        return $this->NowJoin >= $this->max_num;
    }

    public function getShowSAttribute()
    {
        return $this->show ? 
        '<button type=button class="btn btn-success">展示</button>'
        : '<button type=button class="btn btn-danger">不展示</button>';
    }

    public function getOpenAttribute()
    {
        return $this->open_status == '开放' ? 
        '<button type=button class="btn btn-success">开放</button>'
        : '<button type=button class="btn btn-danger">关闭</button>';
    }

    
}
