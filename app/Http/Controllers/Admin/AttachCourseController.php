<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateAttachCourseRequest;
use App\Http\Requests\UpdateAttachCourseRequest;
use App\Repositories\AttachCourseRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AttachCourseController extends AppBaseController
{
    /** @var  AttachCourseRepository */
    private $attachCourseRepository;

    public function __construct(AttachCourseRepository $attachCourseRepo)
    {
        $this->attachCourseRepository = $attachCourseRepo;
    }

    /**
     * Display a listing of the AttachCourse.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->attachCourseRepository->pushCriteria(new RequestCriteria($request));
        $attachCourses = $this->attachCourseRepository->all();

        return view('attach_courses.index')
            ->with('attachCourses', $attachCourses);
    }

    /**
     * Show the form for creating a new AttachCourse.
     *
     * @return Response
     */
    public function create()
    {
        return view('attach_courses.create');
    }

    /**
     * Store a newly created AttachCourse in storage.
     *
     * @param CreateAttachCourseRequest $request
     *
     * @return Response
     */
    public function store(CreateAttachCourseRequest $request)
    {
        $input = $request->all();

        $attachCourse = $this->attachCourseRepository->create($input);

        Flash::success('Attach Course saved successfully.');

        return redirect(route('attachCourses.index'));
    }

    /**
     * Display the specified AttachCourse.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $attachCourse = $this->attachCourseRepository->findWithoutFail($id);

        if (empty($attachCourse)) {
            Flash::error('Attach Course not found');

            return redirect(route('attachCourses.index'));
        }

        return view('attach_courses.show')->with('attachCourse', $attachCourse);
    }

    /**
     * Show the form for editing the specified AttachCourse.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $attachCourse = $this->attachCourseRepository->findWithoutFail($id);

        if (empty($attachCourse)) {
            Flash::error('Attach Course not found');

            return redirect(route('attachCourses.index'));
        }

        return view('attach_courses.edit')->with('attachCourse', $attachCourse);
    }

    /**
     * Update the specified AttachCourse in storage.
     *
     * @param  int              $id
     * @param UpdateAttachCourseRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAttachCourseRequest $request)
    {
        $attachCourse = $this->attachCourseRepository->findWithoutFail($id);

        if (empty($attachCourse)) {
            Flash::error('Attach Course not found');

            return redirect(route('attachCourses.index'));
        }

        $attachCourse = $this->attachCourseRepository->update($request->all(), $id);

        Flash::success('Attach Course updated successfully.');

        return redirect(route('attachCourses.index'));
    }

    /**
     * Remove the specified AttachCourse from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $attachCourse = $this->attachCourseRepository->findWithoutFail($id);

        if (empty($attachCourse)) {
            Flash::error('Attach Course not found');

            return redirect(route('attachCourses.index'));
        }

        $this->attachCourseRepository->delete($id);

        Flash::success('Attach Course deleted successfully.');

        return redirect(route('attachCourses.index'));
    }
}
