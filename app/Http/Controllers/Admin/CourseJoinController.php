<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateCourseJoinRequest;
use App\Http\Requests\UpdateCourseJoinRequest;
use App\Repositories\CourseJoinRepository;
use App\User;
use App\Models\Order;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Maatwebsite\Excel\Facades\Excel;

class CourseJoinController extends AppBaseController
{
    /** @var  CourseJoinRepository */
    private $courseJoinRepository;

    public function __construct(CourseJoinRepository $courseJoinRepo)
    {
        $this->courseJoinRepository = $courseJoinRepo;
    }

    /**
     * Display a listing of the CourseJoin.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->courseJoinRepository->pushCriteria(new RequestCriteria($request));

        $courseJoins = $this->courseJoinRepository->model()::where('id','>',0);

        $input = app('zcjy')->filterNullInput($request->all());

        $courseJoins = $this->dealJoins($courseJoins,$input);

        return view('admin.course_joins.index')
            ->with('courseJoins', $courseJoins)
            ->with('input',$input);
    }

   private  function emoji_encode($nickname){
          $strEncode = '';
          $length = mb_strlen($nickname,'utf-8');
          for ($i=0; $i < $length; $i++) {
              $_tmpStr = mb_substr($nickname,$i,1,'utf-8');
              if(strlen($_tmpStr) >= 4){
                  $strEncode .= '[[EMOJI:'.rawurlencode($_tmpStr).']]';
              }else{
                  $strEncode .= $_tmpStr;
             }
         }
        return $strEncode;
     }
     
    //批量导出
    public function reportMany(Request $request)
    {
        $courseJoins = $this->courseJoinRepository->model()::where('id','>',0);

        $input = app('zcjy')->filterNullInput($request->all());

        $lists = $this->dealJoins($courseJoins,$input,0);

        if(count($lists) == 0)
        {
            Flash::error('当前没有数据可以导出');
            return redirect(route('courseJoins.index'));
        }

        $time = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        
        $con = $this;

        Excel::create('截止到'.$time.'参与记录', function($excel) use($lists,$con) {

            #第二列参与纪录
            $excel->sheet('课程记录列表', function ($sheet) use ($lists,$con) {

                $sheet->setWidth(array(
                    'A' => 14,
                    'B' => 50,
                    'C' => 60,
                    'D' => 14,
                    'E' => 68,
                    'F' => 60,
                    'G' => 60
                ));

                $sheet->appendRow(array('报名人姓名','报名人退休单位','报名人微信昵称','课程价格','课程名称','类型','参与状态','报名时间'));
              
                foreach ($lists as $key => $courseJoin) 
                {
                    $sheet->appendRow(
                        array(
                            optional($courseJoin->user)->name,
                            optional($courseJoin->user)->ret_unit,
                            $con->emoji_encode(optional($courseJoin->user)->nickname),
                            $courseJoin->price,
                            $courseJoin->course_name,
                            $courseJoin->type,
                            $courseJoin->join_status,
                            $courseJoin->created_at
                        )
                    );
                }

            });

        })->download('xls');
    }

    private function dealJoins($courseJoins,$input,$paginate = 1)
    {
        if(isset($input['name']))
        {

            $users = User::where('name','like','%'.$input['name'].'%')->get();
            $users_id_arr = [];
            if(count($users))
            {
                foreach ($users as $key => $user) 
                {
                    $users_id_arr[] = $user->id;
                }
            }
            $courseJoins = $courseJoins->whereIn('user_id',$users_id_arr);
        }

        if(isset($input['course_name']))
        {
            $courseJoins = $courseJoins->where('course_name','like','%'.$input['course_name'].'%');
        }

        if(isset($input['join_year']))
        {
            $courseJoins = $courseJoins->where('join_year',$input['join_year']);
        }

        if(isset($input['join_quarter']))
        {
            $courseJoins = $courseJoins->where('join_quarter',$input['join_quarter']);
        }

        if(isset($input['type']))
        {
            $courseJoins = $courseJoins->where('type',$input['type']);
        }

        if(isset($input['join_status']))
        {
            $courseJoins = $courseJoins->where('join_status',$input['join_status']);
        }

        if(isset($input['order_status']))
        {
            if($input['order_status'] == '未加入订单')
            {
                $courseJoins = $courseJoins->whereNull('order_id');
            }
            elseif($input['order_status'] == '已加入订单')
            {
                $courseJoins = $courseJoins->whereNotNull('order_id');
            }
        }

        if(isset($input['pay_status']))
        {
           $orders = Order::where('pay_status','已支付')->get();
           $orders_id_arr = [];
           foreach ($orders as $key => $order) 
           {
                $orders_id_arr[] = $order->id;
           }

            if($input['pay_status'] == '已支付')
            {
              
                $courseJoins = $courseJoins->whereIn('order_id',$orders_id_arr);
            }
            else{
    
                $courseJoins = $courseJoins->whereNotIn('order_id',$orders_id_arr);
                
            }
        }

        $courseJoins = $courseJoins
        ->orderBy('created_at','desc');

        if($paginate)
        {
            $courseJoins =  $courseJoins->paginate(15);
        }
        else{
            $courseJoins =  $courseJoins->get();
        }

        return $courseJoins;
    }

    /**
     * Show the form for creating a new CourseJoin.
     *
     * @return Response
     */
    public function create()
    {
        return view('course_joins.create');
    }

    /**
     * Store a newly created CourseJoin in storage.
     *
     * @param CreateCourseJoinRequest $request
     *
     * @return Response
     */
    public function store(CreateCourseJoinRequest $request)
    {
        $input = $request->all();

        $courseJoin = $this->courseJoinRepository->create($input);

        Flash::success('Course Join saved successfully.');

        return redirect(route('courseJoins.index'));
    }

    /**
     * Display the specified CourseJoin.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $courseJoin = $this->courseJoinRepository->findWithoutFail($id);

        if (empty($courseJoin)) {
            Flash::error('Course Join not found');

            return redirect(route('courseJoins.index'));
        }

        return view('course_joins.show')->with('courseJoin', $courseJoin);
    }

    /**
     * Show the form for editing the specified CourseJoin.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $courseJoin = $this->courseJoinRepository->findWithoutFail($id);

        if (empty($courseJoin)) {
            Flash::error('Course Join not found');

            return redirect(route('courseJoins.index'));
        }

        return view('course_joins.edit')->with('courseJoin', $courseJoin);
    }

    /**
     * Update the specified CourseJoin in storage.
     *
     * @param  int              $id
     * @param UpdateCourseJoinRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCourseJoinRequest $request)
    {
        $courseJoin = $this->courseJoinRepository->findWithoutFail($id);

        if (empty($courseJoin)) {
            Flash::error('Course Join not found');

            return redirect(route('courseJoins.index'));
        }

        $courseJoin = $this->courseJoinRepository->update($request->all(), $id);

        Flash::success('Course Join updated successfully.');

        return redirect(route('courseJoins.index'));
    }

    /**
     * Remove the specified CourseJoin from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $courseJoin = $this->courseJoinRepository->findWithoutFail($id);

        if (empty($courseJoin)) {
            Flash::error('Course Join not found');

            return redirect(route('courseJoins.index'));
        }

        $this->courseJoinRepository->delete($id);

        Flash::success('Course Join deleted successfully.');

        return redirect(route('courseJoins.index'));
    }
}
