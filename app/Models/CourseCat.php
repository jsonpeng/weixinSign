<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CourseCat
 * @package App\Models
 * @version November 30, 2018, 3:58 pm CST
 *
 * @property string name
 * @property string image
 * @property integer pid
 * @property string content
 */
class CourseCat extends Model
{
    use SoftDeletes;

    public $table = 'course_cats';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'image',
        'pid',
        'content',
        'type',
        'show'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'image' => 'string',
        'pid' => 'integer',
        'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name'=>'required'
    ];

    public function getShowSAttribute()
    {
        return $this->show ? 
        '<button type=button class="btn btn-success">展示</button>'
        : '<button type=button class="btn btn-danger">不展示</button>';
    }

    public function child_cats()
    {
        return $this->hasMany('App\Models\CourseCat','pid','id');
    }

    
}
