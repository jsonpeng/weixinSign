<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CourseJoin
 * @package App\Models
 * @version November 30, 2018, 4:45 pm CST
 *
 * @property integer course_id
 * @property float price
 * @property string course_name
 * @property string course_des
 * @property integer order_id
 * @property string type
 */
class CourseJoin extends Model
{
    use SoftDeletes;

    public $table = 'course_joins';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'course_id',
        'price',
        'course_name',
        'course_des',
        'order_id',
        'type',
        'user_id',
        'join_status',
        'join_year',
        'join_quarter'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'course_id' => 'integer',
        'price' => 'float',
        'course_name' => 'string',
        'course_des' => 'string',
        'order_id' => 'integer',
        'type' => 'string'
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

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    
}
