<?php

namespace App\Repositories;

use App\Models\CourseCat;
use InfyOm\Generator\Common\BaseRepository;

use Cache;
use Config;
/**
 * Class CourseCatRepository
 * @package App\Repositories
 * @version November 30, 2018, 3:58 pm CST
 *
 * @method CourseCat findWithoutFail($id, $columns = ['*'])
 * @method CourseCat find($id, $columns = ['*'])
 * @method CourseCat first($columns = ['*'])
*/
class CourseCatRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'image',
        'pid',
        'content'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CourseCat::class;
    }

    /**
     * [获取可以的分类列表]
     * @param  [type] $catIdOrType [description]
     * @return [type]         [description]
     */
    public function getCatsList($catIdOrType,$type=null)
    {
        $cats = CourseCat::where('pid',0);

        #不是整形就查下
        if(!is_numeric($catIdOrType) && $type == null){
            $cats = $cats->where('type',$catIdOrType);
        }#是数字就往后面走
        else{
            $cats = $cats->where('type',$type);
        }

        $cats = $cats->get();

        $cat = null;

        if(!empty($type)){
            $cat = CourseCat::find($catIdOrType);
        }

        foreach ($cats as $key => $value) {
                if(!empty($catIdOrType) && is_numeric($catIdOrType)){
                    $value['disabled'] = 0;
                    $value['selected'] = 0;
                    if($catIdOrType == $value->id){
                        $value['disabled'] = 1;
                    }
                    #如果有pid并且pid
                    if(!empty($cat) && $cat->pid){
                        if($value->id == $cat->pid){
                            $value['selected'] = 1;
                        }
                    }
                }
                else{
                    $value['selected'] = 0;
                    $value['disabled'] = 0;
                }
        }

        return $cats;
    }


    public function getCacheChildCats($catId)
    {
      return Cache::remember('zcjy_child_cats_'.$catId,Config::get('web.shrottimecache'),function() use($catId){
          return $this->getChildCats($catId);
      });
    }



    //获取对应分类下的子分类
    public function getChildCats($catId,$front_show=true,$first=false)
    {
      $cats = CourseCat::where('pid',$catId);

      if($front_show)
      {
        $cats = frontShow($cats);
      }
      if($first){
          $cats = $cats->first();
      }
      else{
          $cats = $cats->get();
      }
      return $cats;
    }

    /**
     * [带上缓存的方式获取对应类型的分类并且带上子分类]
     * @return [type] [description]
     */
    public function getCacheTypeCats($type,$with_child=false)
    {
      return Cache::remember('zcjy_cache_type_cats_'.$type.$with_child,Config::get('web.shrottimecache'),function() use($type,$with_child){
          return $this->getAllTypeCats($type,$with_child,true);
      });
    }

    /**
     * [获取对应类型的分类并且带上子分类]
     * @return [type] [description]
     */
    public function getAllTypeCats($type,$with_child=false,$front_show=true,$first=false)
    {
        $this->generateDefaultCat($type);

        $cats = CourseCat::where('type',$type)->where('pid',0);

        if($front_show){
           $cats = frontShow($cats);
        }

        $cats = $cats->get();

        foreach ($cats as $key => $value) {
            if($with_child){
                $value['child_cats'] = $this->getChildCats($value->id,$front_show,$first);
            }
        }
        return $cats;
    }

    /**
     * [为兴趣小组和活动报名生成默认分类]
     * @param  [type] $type [description]
     * @return [type]       [description]
     */
    private function generateDefaultCat($type)
    {
        if($type == '兴趣小组' || $type == '活动'){
            if(CourseCat::where('type',$type)->count() == 0){
                 CourseCat::create([
                  'name'=>$type,
                  'type'=>$type
                ]);
            } 
        }
    }

    /**
     * [验证分类id所在的类型]
     * @param  [type] $cat_id [description]
     * @param  [type] $type   [description]
     * @return [type]         [description]
     */
    public function varifyCatIdType($cat_id,$type)
    {
        if(!is_numeric($cat_id)){
          return null;
        }

        $cat = CourseCat::find($cat_id);

        if(!empty($cat)){
          if($cat->type == $type){
            return true;
          }
          else{
            return null;
          }
        }
        else{
          return null;
        }

    }
}
