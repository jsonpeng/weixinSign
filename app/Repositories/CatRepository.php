<?php

namespace App\Repositories;

use App\Models\Cat;
use InfyOm\Generator\Common\BaseRepository;

use App\Models\Post;
use Config;
use Cache;

/**
 * Class CatRepository
 * @package App\Repositories
 * @version December 26, 2018, 4:22 pm CST
 *
 * @method Cat findWithoutFail($id, $columns = ['*'])
 * @method Cat find($id, $columns = ['*'])
 * @method Cat first($columns = ['*'])
*/
class CatRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'sort'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Cat::class;
    }

    /**
     * 所有的分类
     * @return [type] [description]
     */
    public function getCacheAllCats()
    {
        return Cache::remember('zcjy_post_cats',Config::get('web.shrottimecache'),function(){
            return Cat::orderBy('sort','desc')->get();
        });
    }

    /**
     * 第一个分类
     * @return [type] [description]
     */
    public function getFirstCat()
    {
          return Cache::remember('zcjy_post_first_cat',Config::get('web.shrottimecache'),function(){
             return Cat::orderBy('sort','desc')->first();
          });
    }

    /**
     * 获取指定分类的文章
     * @param  [type] $cat_name [description]
     * @return [type]           [description]
     */
    public function getCacheCatPosts($cat_name)
    {
         return Cache::remember('zcjy_post_cat_posts_'.$cat_name,Config::get('web.shrottimecache'),function() use($cat_name){
            return Post::where('cat_name',$cat_name)
                    ->where('status',1)
                    ->orderBy('created_at','desc')
                    ->get();
         });    
    }


}
