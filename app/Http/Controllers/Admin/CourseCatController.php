<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateCourseCatRequest;
use App\Http\Requests\UpdateCourseCatRequest;
use App\Repositories\CourseCatRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CourseCatController extends AppBaseController
{
    /** @var  CourseCatRepository */
    private $courseCatRepository;
    private $type;
    public function __construct(CourseCatRepository $courseCatRepo)
    {
        $this->courseCatRepository = $courseCatRepo;
        $this->type = $this->dealType();
    }


    private function dealType(){
        $type = request('type');
        if(is_numeric($type)){
            $type = optional(app('zcjy')->CourseCatRepo()->findWithoutFail($type))->type;
        }

        if(empty($type)){
            $type = '课程班';
        }

        $this->type = $type;
        return $type;
    }


    /**
     * Display a listing of the CourseCat.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
       
        $type = $this->type;

        $this->courseCatRepository->pushCriteria(new RequestCriteria($request));

        $courseCats = app('zcjy')->CourseCatRepo()->model()::where('type',$type)
        ->where('pid',0)
        ->where('show',1)
        ->with('child_cats')
        ->orderBy('show','desc')
        ->paginate(5);

        return view('admin.course_cats.index')
            ->with('courseCats', $courseCats)
            ->with('type',$type);
    }

    /**
     * Show the form for creating a new CourseCat.
     *
     * @return Response
     */
    public function create($type)
    {
        $cats = $this->courseCatRepository->getCatsList($type);
        return view('admin.course_cats.create',compact('cats','type'));
    }

    /**
     * Store a newly created CourseCat in storage.
     *
     * @param CreateCourseCatRequest $request
     *
     * @return Response
     */
    public function store(CreateCourseCatRequest $request,$type)
    {
        $input = $request->all();

        $courseCat = $this->courseCatRepository->create($input);

        Flash::success('添加成功.');

        return redirect(route('courseCats.index',$type));
    }

    /**
     * Display the specified CourseCat.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id,$type)
    {
        $courseCat = $this->courseCatRepository->findWithoutFail($id);

        if (empty($courseCat)) {
            Flash::error('没有找到该分类');

            return redirect(route('courseCats.index'));
        }

        return view('admin.course_cats.show')->with('courseCat', $courseCat);
    }

    /**
     * Show the form for editing the specified CourseCat.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id,$type)
    {
        $courseCat = $this->courseCatRepository->findWithoutFail($id);

        if (empty($courseCat)) {
            Flash::error('没有找到该分类');

            return redirect(route('courseCats.index'));
        }
        $cats = $this->courseCatRepository->getCatsList($id,$type);

        return view('admin.course_cats.edit')
        ->with('courseCat', $courseCat)
        ->with('cats',$cats)
        ->with('type',$type);
    }

    /**
     * Update the specified CourseCat in storage.
     *
     * @param  int              $id
     * @param UpdateCourseCatRequest $request
     *
     * @return Response
     */
    public function update($id,$type, UpdateCourseCatRequest $request)
    {
        $courseCat = $this->courseCatRepository->findWithoutFail($id);

        if (empty($courseCat)) {
            Flash::error('没有找到该分类');

            return redirect(route('courseCats.index'));
        }

        $input = $request->all();

        $courseCat = $this->courseCatRepository->update($input, $id);

        Flash::success('更新成功.');

        return redirect(route('courseCats.index',$type));
    }

    /**
     * Remove the specified CourseCat from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id,$type)
    {
        $courseCat = $this->courseCatRepository->findWithoutFail($id);

        if (empty($courseCat)) {
            Flash::error('没有找到该分类');

            return redirect(route('courseCats.index'));
        }

        $this->courseCatRepository->delete($id);

        #子id一起干掉
        $this->courseCatRepository->model()::where('pid',$id)->delete();
        
        Flash::success('删除成功.');

        return redirect(route('courseCats.index',$type));
    }
}
