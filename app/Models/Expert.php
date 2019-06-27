<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Expert
 * @package App\Models
 * @version June 13, 2019, 11:26 am CST
 *
 * @property string name
 * @property string image
 * @property string tel
 * @property string jiguan
 * @property string re_unit
 * @property string work_exp
 * @property string res_result
 */
class Expert extends Model
{
    use SoftDeletes;

    public $table = 'experts';
    
    protected $dates = ['deleted_at'];

    public $fillable = [
        'name',
        'image',
        'tel',
        'jiguan',
        're_unit',
        'work_exp',
        'res_result'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'image' => 'string',
        'tel' => 'string',
        'jiguan' => 'string',
        're_unit' => 'string',
        'work_exp' => 'string',
        'res_result' => 'string'
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
