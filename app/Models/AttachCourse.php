<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AttachCourse
 * @package App\Models
 * @version November 30, 2018, 4:31 pm CST
 *
 * @property string weekday
 * @property string start_time
 * @property string end_time
 * @property string classroom_name
 * @property string teacher_name
 * @property integer course_id
 */
class AttachCourse extends Model
{
    use SoftDeletes;

    public $table = 'attach_courses';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'weekday',
        'start_time',
        'end_time',
        'classroom_name',
        'teacher_name',
        'course_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'weekday' => 'string',
        'start_time' => 'string',
        'end_time' => 'string',
        'classroom_name' => 'string',
        'teacher_name' => 'string',
        'course_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
    
}
