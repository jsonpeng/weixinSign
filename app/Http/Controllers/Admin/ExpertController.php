<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateExpertRequest;
use App\Http\Requests\UpdateExpertRequest;
use App\Repositories\ExpertRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ExpertController extends AppBaseController
{
    /** @var  ExpertRepository */
    private $expertRepository;

    public function __construct(ExpertRepository $expertRepo)
    {
        $this->expertRepository = $expertRepo;
    }

    /**
     * Display a listing of the Expert.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->expertRepository->pushCriteria(new RequestCriteria($request));
        $experts = $this->expertRepository
        ->orderBy('created_at','desc')
        ->paginate(15);

        return view('experts.index')
            ->with('experts', $experts);
    }

    /**
     * Show the form for creating a new Expert.
     *
     * @return Response
     */
    public function create()
    {
        return view('experts.create');
    }

    /**
     * Store a newly created Expert in storage.
     *
     * @param CreateExpertRequest $request
     *
     * @return Response
     */
    public function store(CreateExpertRequest $request)
    {
        $input = $request->all();

        $expert = $this->expertRepository->create($input);

        Flash::success('添加成功');

        return redirect(route('experts.index'));
    }

    /**
     * Display the specified Expert.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $expert = $this->expertRepository->findWithoutFail($id);

        if (empty($expert)) {
            Flash::error('Expert not found');

            return redirect(route('experts.index'));
        }

        return view('experts.show')->with('expert', $expert);
    }

    /**
     * Show the form for editing the specified Expert.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $expert = $this->expertRepository->findWithoutFail($id);

        if (empty($expert)) {
            Flash::error('Expert not found');

            return redirect(route('experts.index'));
        }

        return view('experts.edit')->with('expert', $expert);
    }

    /**
     * Update the specified Expert in storage.
     *
     * @param  int              $id
     * @param UpdateExpertRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExpertRequest $request)
    {
        $expert = $this->expertRepository->findWithoutFail($id);

        if (empty($expert)) {
            Flash::error('Expert not found');

            return redirect(route('experts.index'));
        }

        $expert = $this->expertRepository->update($request->all(), $id);

        Flash::success('更新成功.');

        return redirect(route('experts.index'));
    }

    /**
     * Remove the specified Expert from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $expert = $this->expertRepository->findWithoutFail($id);

        if (empty($expert)) {
            Flash::error('Expert not found');

            return redirect(route('experts.index'));
        }

        $this->expertRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('experts.index'));
    }
}
