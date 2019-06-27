<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 * @package App\Models
 * @version November 30, 2018, 4:56 pm CST
 *
 * @property float price
 * @property string number
 * @property integer user_id
 * @property string pay_platform
 * @property string pay_status
 * @property string remark
 */
class Order extends Model
{
    use SoftDeletes;

    public $table = 'orders';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'price',
        'number',
        'user_id',
        'pay_platform',
        'pay_status',
        'remark'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'number' => 'string',
        'user_id' => 'integer',
        'pay_platform' => 'string',
        'pay_status' => 'string',
        'remark' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function joins()
    {
        return $this->hasMany('App\Models\CourseJoin','order_id','id');
    }

    
}
