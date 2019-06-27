<?php

namespace App\Repositories;

use App\Models\Expert;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ExpertRepository
 * @package App\Repositories
 * @version June 13, 2019, 11:26 am CST
 *
 * @method Expert findWithoutFail($id, $columns = ['*'])
 * @method Expert find($id, $columns = ['*'])
 * @method Expert first($columns = ['*'])
*/
class ExpertRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'image',
        'tel',
        'jiguan',
        're_unit',
        'work_exp',
        'res_result'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Expert::class;
    }
}
