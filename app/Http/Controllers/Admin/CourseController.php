<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Repositories\CourseRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CourseController extends AppBaseController
{
    /** @var  CourseRepository */
    private $courseRepository;
    private $cat;
    public function __construct(CourseRepository $courseRepo)
    {
        $this->courseRepository = $courseRepo;
        if(!$this->dealCatId()){
            return redirect('/zcjy');
        }
    }

    //处理catid
    private function dealCatId($catid=null){
        $cat_id = request('cat_id');

        if(empty($cat_id)){
            $cat_id = $catid;
        }
       
        $cat = app('zcjy')->CourseCatRepo()->findWithoutFail($cat_id);
        if(empty($cat)){
            $this->cat = false;
            return false;
        }
        else{
            $this->cat = $cat;
            return $cat;
        }
    }

    //所有教室
    private function allRooms(){
        return app('zcjy')->ClassroomRepo()->all();
    }

    private function redirectDefault(){
         return redirect('/zcjy');
    }

    /**
     * Display a listing of the Course.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request,$cat_id)
    {
        session(['courseUrl'=>$request->fullUrl()]);

        if(empty($this->cat)){
            return $this->redirectDefault();
        }

        $this->courseRepository->pushCriteria(new RequestCriteria($request));

        $courses = $this->descAndPaginateToShow(app('zcjy')->CourseRepo()->catCourses($cat_id,true));

        return view('admin.courses.index')
            ->with('courses', $courses)
            ->with('cat',$this->cat);
    }

    //跳转
    private function redirectUrl()
    {
        return redirect(session('courseUrl'));
    }


    /**
     * Show the form for creating a new Course.
     *
     * @return Response
     */
    public function create($cat_id)
    {

        if(!$this->dealCatId($cat_id)){
           return $this->redirectDefault();
        }

        $allRooms = $this->allRooms();

        if(count($allRooms) == 0){
            Flash::error('请先添加教室!');
            return redirect(route('courses.index',$cat_id));
        }

        return view('admin.courses.create')
        ->with('cat',$this->cat)
        ->with('allRooms',$allRooms);
    }

    /**
     * Store a newly created Course in storage.
     *
     * @param CreateCourseRequest $request
     *
     * @return Response
     */
    public function store(CreateCourseRequest $request,$cat_id)
    {
        $input = $request->all();

        $input['now_num'] = 0;

        #活动或者兴趣小组
        if(app('zcjy')->CourseCatRepo()->varifyCatIdType($cat_id,'兴趣小组') || app('zcjy')->CourseCatRepo()->varifyCatIdType($cat_id,'活动')){
            
            $this->courseRepository->model()::create($input);
        }
        else{

            if(app('zcjy')->AttachCourseRepo()->actionAttachCourse($input)){
                return redirect(route('courses.create',$cat_id))
                       ->withErrors('添加失败,请先添加教室安排')
                        ->withInput($input);
            }

        }

        Flash::success('添加成功.');

        return redirect(route('courses.index',$cat_id));
    }

    /**
     * Display the specified Course.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id,$cat_id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');

            return redirect(route('courses.index'));
        }

        return view('admin.courses.show')->with('course', $course);
    }

    /**
     * Show the form for editing the specified Course.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($cat_id,$id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');

            return redirect(route('courses.index',$cat_id));
        }

        if(!$this->dealCatId()){
           return $this->redirectDefault();
        }

        $allRooms = $this->allRooms();

        if(count($allRooms) == 0){
            Flash::error('请先添加教室!');
            return redirect(route('courses.index',$cat_id));
        }

        $attachs = app('zcjy')->AttachCourseRepo()->courseAttachs($course);
        // dd($attachs);
        return view('admin.courses.edit')
        ->with('course', $course)
        ->with('cat',$this->cat)
        ->with('allRooms',$allRooms)
        ->with('attachs',$attachs);
    }

    /**
     * Update the specified Course in storage.
     *
     * @param  int              $id
     * @param UpdateCourseRequest $request
     *
     * @return Response
     */
    public function update($cat_id,$id,UpdateCourseRequest $request)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');

            return redirect(route('courses.index'));
        }
        
        $input = $request->all();

        #活动或者兴趣小组
        if(app('zcjy')->CourseCatRepo()->varifyCatIdType($cat_id,'兴趣小组') || app('zcjy')->CourseCatRepo()->varifyCatIdType($cat_id,'活动')){

            $course->update($input);
        }
        else{

            if(app('zcjy')->AttachCourseRepo()->actionAttachCourse($input,'update',$course)){
                return redirect(route('courses.edit',[$cat_id,$id]))
                       ->withErrors('修改失败,请先添加教室安排.')
                        ->withInput($input);
            }

        }

        Flash::success('更新成功.');

        return redirect(route('courses.index',$cat_id));
    }

    

     public function updateOpenStatus($cat_id,$id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');

            return redirect(route('courses.index'));
        }
        
        $status = '开放';

        if($course->open_status == '开放')
        {
            $status = '关闭';
        }

        $course->update(['open_status'=>$status]);

        Flash::success('更新开放状态成功.');

        return $this->redirectUrl();
    }

    public function updateAction($cat_id,$id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');

            return redirect(route('courses.index'));
        }
        
        $show = 1;
        $open_status = '开放';

        if($course->show)
        {
            $show = 0;
            $open_status = '关闭';
        }

        $course->update(['show'=>$show,'open_status'=>$open_status]);

        Flash::success('删除成功,已删除课程将无法继续恢复!');

        return $this->redirectUrl();
    }

    /**
     * Remove the specified Course from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($cat_id,$id)
    {
        $course = $this->courseRepository->findWithoutFail($id);

        if (empty($course)) {
            Flash::error('Course not found');

            return redirect(route('courses.index'));
        }

        $this->courseRepository->delete($id);

        Flash::success('删除成功.');

        return $this->redirectUrl();
    }
}
