<?php

namespace App\Repositories;

use App\Models\AttentionCourse;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AttentionCourseRepository
 * @package App\Repositories
 * @version December 24, 2018, 10:38 am CST
 *
 * @method AttentionCourse findWithoutFail($id, $columns = ['*'])
 * @method AttentionCourse find($id, $columns = ['*'])
 * @method AttentionCourse first($columns = ['*'])
*/
class AttentionCourseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'course_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AttentionCourse::class;
    }
}
