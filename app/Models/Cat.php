<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cat
 * @package App\Models
 * @version December 26, 2018, 4:22 pm CST
 *
 * @property string name
 * @property integer sort
 */
class Cat extends Model
{
    use SoftDeletes;

    public $table = 'cats';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'sort'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'sort' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
    ];

    
}
