<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AttentionCourse
 * @package App\Models
 * @version December 24, 2018, 10:38 am CST
 *
 * @property integer user_id
 * @property integer course_id
 */
class AttentionCourse extends Model
{
    use SoftDeletes;

    public $table = 'attention_courses';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'course_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'course_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
