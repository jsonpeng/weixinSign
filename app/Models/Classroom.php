<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Classroom
 * @package App\Models
 * @version November 30, 2018, 3:53 pm CST
 *
 * @property string name
 * @property string location
 * @property string address
 */
class Classroom extends Model
{
    use SoftDeletes;

    public $table = 'classrooms';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'location',
        'address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'location' => 'string',
        'address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
         'name' => 'required'
    ];

    
}
