<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstant;
use App\Constants\UserConstant;
use App\Http\Requests\ChangePasswodRequest;
use App\Repositories\FoodOrderRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    /**
     * @var TableRepository
     */
    private TableRepository $tableRepository;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var FoodOrderRepository
     */
    private FoodOrderRepository $foodOrderRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param TableRepository $tableRepository
     * @param OrderRepository $orderRepository
     * @param FoodOrderRepository $foodOrderRepository
     */
    public function __construct(
        TableRepository $tableRepository,
        OrderRepository $orderRepository,
        FoodOrderRepository $foodOrderRepository,
        UserRepository $userRepository
    ) {
        $this->tableRepository = $tableRepository;
        $this->orderRepository = $orderRepository;
        $this->foodOrderRepository = $foodOrderRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('change-password');
    }

    public function changePassword(ChangePasswodRequest $request)
    {
        $request = $request->all();
        $user = Auth::user();
        $result = $this->userRepository->changePassword($user[BaseConstant::ID_FIELD], $request);
        if (!$result) {

            return redirect()->back()->with(['message' => 'Đổi mật khẩu thất bại, vui lòng thử lại']);
        }

        return redirect()->back()->with(['message' => 'Đổi mật khẩu thành công']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeTableStatus(Request $request): JsonResponse
    {
        $request = $request->all();
        $this->checkTableStatus($request[BaseConstant::ID_FIELD]);
        $result = $this->tableRepository->changeTableStatus($request[BaseConstant::ID_FIELD]);
        if ($result) {
            return response()->json([
                'code' => '222',
                'message' => 'Trạng thái của bàn đã được thay đổi'
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse|void
     */
    private function checkTableStatus($id)
    {
        $result = $this->tableRepository->checkTableStatus($id);
        if ($result === BaseConstant::TABLE_ORDERED) {
            return response()->json([
                'code' => '111',
                'message' => 'Bàn đã được order'
            ]);
        }
    }

    public function addFoodToOrder(Request $request)
    {
        $request = $request->all();
        $result = $this->foodOrderRepository->addFoodToOrder($request);

        if ($result) {
            return response()->json([
                'code' => '222',
                'message' => 'Đã thêm món vào order',
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }

    public function createOrder(Request $request)
    {
        $user = Auth::user();
        $request = $request->all();
        $result = $this->orderRepository->createOrder($request, $user[BaseConstant::ID_FIELD]);

        if ($result) {
            return response()->json([
                'code' => '222',
                'message' => 'Đã tạo order',
                'order_id' => $result[BaseConstant::ID_FIELD]
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }
}
