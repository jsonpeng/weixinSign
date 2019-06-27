<?php

namespace App\Repositories;

use App\Models\Topic;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * Class TopicRepository
 * @package App\Repositories
 * @version July 31, 2018, 4:30 pm CST
 *
 * @method Topic findWithoutFail($id, $columns = ['*'])
 * @method Topic find($id, $columns = ['*'])
 * @method Topic first($columns = ['*'])
*/
class TopicRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'name',
        'attach_url',
        'sec_sort',
        'num_sort',
        'subject_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Topic::class;
    }


    public function getNextGroupNum(){
          $topic  = Topic::orderBy('group','desc')->first();
          $num = 1;
          if(!empty($topic)){
            $num = $topic->group + 1;
          }
          return $num;
    }

    public function getGroupTopics(){
        return Topic::where('group',0)->get();
    }

    public function getSubjectSecTopics($subject_id,$sec){
        return Topic::where('subject_id',$subject_id)->where('sec_sort',$sec);
    }


    /**
     * [根据关键字搜索题目]
     * @param  [type] $word [description]
     * @return [type]       [description]
     */
    public function searchTopics($word){
          return Cache::remember('get_cache_search_topics_'.$word, Config::get('web.cachetime'), function() use($word){
                return Topic::where('is_delete',0)
                    ->where('name','like','%'.$word.'%')
                    ->with('selections')
                    ->orderBy('num_sort','asc')
                    ->get();
          });
    }

    /**
     * [根据科目和章节获取题目]
     * @param  [type]  $subject_id [description]
     * @param  [type]  $sec        [description]
     * @param  integer $take       [description]
     * @return [type]              [description]
     */
    public function getCacheTopics($subject_id,$sec,$take=10){
        return Cache::remember('get_cache_topics_'.$subject_id.'_'.$sec.'_'.$take, Config::get('web.cachetime'), function() use($subject_id,$sec,$take){
            if($take == 'all'){
                  return $this->getSubjectSecTopics($subject_id,$sec)
                    ->where('is_delete',0)
                    ->with('selections')
                    ->orderBy('num_sort','asc')
                    ->get();
            }else{
            return $this->getSubjectSecTopics($subject_id,$sec)
            ->where('is_delete',0)
            ->with('selections')
            ->orderBy(\DB::raw('RAND()'))
            ->take($take)
            ->get();
            }
        });
    }

       /**
     * [根据科目和章节获取题目]
     * @param  [type]  $subject_id [description]
     * @param  [type]  $sec        [description]
     * @param  integer $take       [description]
     * @return [type]              [description]
     */
    public function getCacheTopicsWithSkipTake($subject_id,$sec,$skip=0,$take=50){
        return Cache::remember('get_cache_topics_with_skip'.$subject_id.'_'.$sec.'_'.$skip.'_'.$take, Config::get('web.cachetime'), function() use($subject_id,$sec,$skip,$take){
   
            return $this->getSubjectSecTopics($subject_id,$sec)
            ->where('is_delete',0)
            ->with('selections')
            ->orderBy('num_sort','asc')
            ->skip($skip)
            ->take($take)
            ->get();
            
        });
    }




}
