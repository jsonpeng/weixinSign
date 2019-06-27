<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Repositories\OrderRepository;
use App\Models\Order;
use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;


class OrderController extends AppBaseController
{
    /** @var  OrderRepository */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
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


     public function allJoinLog(Request $request)
     {
         $lists = Order::where('pay_status','已支付')
         ->where('price','>',0)
         ->orderBy('created_at','asc')
         ->get();

        $time = Carbon::now()->format('Y-m-d H:i:s');

        $con = $this;

        Excel::create('截止到'.$time.'平台所有参与记录', function($excel) use($lists,$con) {

            $lists = app('zcjy')->OrderRepo()->joinsLog($lists);
            
            #第二列参与纪录
            $excel->sheet('课程记录列表', function ($sheet) use ($lists,$con) {

                $sheet->setWidth(array(
                    'A' => 14,
                    'B' => 50,
                    'C' => 60,
                    'D' => 14,
                    'E' => 68,
                    'F' => 60,
                    'G' => 34,
                    'H' => 34,
                    'I' => 34,
                    'J' => 34,
                    'K' => 34
                ));

                $sheet->appendRow(array('报名人姓名','报名人退休单位','报名人微信昵称','课程价格','课程名称','类型','参与状态','支付平台','报名登记时间','支付单号','支付时间'));
              
                foreach ($lists as $key => $courseJoin) 
                {
                    $joinOrder = optional($courseJoin->order);
                    $sheet->appendRow(
                        array(
                            optional($courseJoin->user)->name,
                            optional($courseJoin->user)->ret_unit,
                            $con->emoji_encode(optional($courseJoin->user)->nickname),
                            $courseJoin->price,
                            $courseJoin->course_name,
                            $courseJoin->type,
                            $courseJoin->join_status,
                            $joinOrder->pay_platform,
                            $courseJoin->created_at,
                            $joinOrder->number,
                            $joinOrder->updated_at
                        )
                    );
                }

            });
        })->download('xls');


     }

    //批量导出
    public function reportMany(Request $request)
    {
        $lists = Order::orderBy('created_at','desc')->get();

        if(count($lists) == 0)
        {
            Flash::error('当前没有数据可以导出');
            return redirect(route('orders.index'));
        }

        $time = Carbon::now()->format('Y-m-d H:i:s');
        
        $con = $this;

        Excel::create('截止到'.$time.'订单及参与记录', function($excel) use($lists,$con) {

            //第一列sheet
            $excel->sheet('订单记录列表', function ($sheet) use ($lists,$con) {

                $sheet->setWidth(array(
                    'A' => 14,
                    'B' => 50,
                    'C' => 14,
                    'D' => 14,
                    'E' => 68,
                    'F' => 60,
                    'G' => 60
                ));

                $sheet->appendRow(array('订单金额','下单购买人','支付平台','支付状态','备注说明','购买时间','订单号'));
              
                foreach ($lists as $key => $item) 
                {
                    $sheet->appendRow(
                        array(
                            $item->price,
                            $con->emoji_encode(optional($item->user)->nickname),
                            $item->pay_platform,
                            $item->pay_status,
                            $item->remark,
                            $item->created_at,
                            $item->number
                        )
                    );
                }

            });

            $lists = app('zcjy')->OrderRepo()->joinsLog($lists);
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

    /**
     * Display a listing of the Order.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        
        $this->orderRepository->pushCriteria(new RequestCriteria($request));

        $input = $request->all();

        $orders = $this->orderRepository->model()::where('id','>',0);

        if(isset($input['name']))
        {
            $users = User::where('name','like','%'.$input['name'].'%')->get();
            $userIdArr = [];
            foreach ($users as $key => $user) 
            {
                $userIdArr[] = $user->id;
            }
            $orders = $orders->whereIn('user_id',$userIdArr);
        }

        if(isset($input['number']))
        {
            $orders = $orders->where('number','like','%'.$input['number'].'%');
        }


        if(isset($input['pay_platform']))
        {
            $orders = $orders->where('pay_platform',$input['pay_platform']);
        }

        if(isset($input['pay_status']))
        {
            $orders = $orders->where('pay_status',$input['pay_status']);
        }

        $orders = $this->descAndPaginateToShow($orders);

        return view('admin.orders.index')
            ->with('orders', $orders)
            ->with('input',$input);
    }

    /**
     * Show the form for creating a new Order.
     *
     * @return Response
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param CreateOrderRequest $request
     *
     * @return Response
     */
    public function store(CreateOrderRequest $request)
    {
        $input = $request->all();

        $order = $this->orderRepository->create($input);

        Flash::success('保存成功.');

        return redirect(route('orders.index'));
    }

    /**
     * Display the specified Order.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }

        return view('admin.orders.show')->with('order', $order);
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }

        return view('admin.orders.edit')->with('order', $order);
    }

    /**
     * Update the specified Order in storage.
     *
     * @param  int              $id
     * @param UpdateOrderRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrderRequest $request)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }

        $order = $this->orderRepository->update($request->all(), $id);

        Flash::success('更新成功.');

        return redirect(route('orders.index'));
    }

    /**
     * Remove the specified Order from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $order = $this->orderRepository->findWithoutFail($id);

        if (empty($order)) {
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }

        $this->orderRepository->delete($id);

        Flash::success('删除成功.');

        return redirect(route('orders.index'));
    }
}
