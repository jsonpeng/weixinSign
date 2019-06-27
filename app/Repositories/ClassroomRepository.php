<?php

namespace App\Repositories;

use App\Models\Classroom;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ClassroomRepository
 * @package App\Repositories
 * @version November 30, 2018, 3:53 pm CST
 *
 * @method Classroom findWithoutFail($id, $columns = ['*'])
 * @method Classroom find($id, $columns = ['*'])
 * @method Classroom first($columns = ['*'])
*/
class ClassroomRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'location',
        'address'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Classroom::class;
    }
}
