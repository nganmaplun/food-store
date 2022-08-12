<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodDayConstant;
use App\Constants\FoodOrderConstant;
use App\Constants\UserConstant;
use App\Http\Requests\ChangePasswodRequest;
use App\Repositories\AggDayRepository;
use App\Repositories\FoodOrderRepository;
use App\Repositories\FoodRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;
use App\Repositories\UserRepository;
use App\Services\MessageService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Pusher\ApiErrorException;
use Pusher\PusherException;

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
     * @var MessageService
     */
    private MessageService $messageService;

    /**
     * @var AggDayRepository
     */
    private AggDayRepository $foodDayRepository;

    /**
     * @var FoodRepository
     */
    private FoodRepository $foodRepository;

    /**
     * @param TableRepository $tableRepository
     * @param OrderRepository $orderRepository
     * @param FoodOrderRepository $foodOrderRepository
     * @param UserRepository $userRepository
     * @param MessageService $messageService
     */
    public function __construct(
        TableRepository     $tableRepository,
        OrderRepository     $orderRepository,
        FoodOrderRepository $foodOrderRepository,
        UserRepository      $userRepository,
        MessageService      $messageService,
        AggDayRepository    $foodDayRepository,
        FoodRepository      $foodRepository,
    ) {
        $this->tableRepository = $tableRepository;
        $this->orderRepository = $orderRepository;
        $this->foodOrderRepository = $foodOrderRepository;
        $this->userRepository = $userRepository;
        $this->messageService = $messageService;
        $this->foodDayRepository = $foodDayRepository;
        $this->foodRepository = $foodRepository;
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
        if ($result == BaseConstant::TABLE_ORDERED) {
            return response()->json([
                'code' => '111',
                'message' => 'Bàn đã được order'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addFoodToOrder(Request $request)
    {
        $request = $request->all();
        $result = $this->checkOrderStatus($request['orderId']);
        if ($result === 'true') {
            return response()->json([
                'code' => '111',
                'message' => 'Không thêm món vì bàn đã được yêu cầu thanh toán'
            ]);
        }
        $result = $this->checkFoodRemain($request);
        if ($result != 'true') {
            return response()->json([
                'code' => '333',
                'message' => 'Không đủ suất. Món ' . $result['food_name'] . ' còn ' . $result['remain'] . ' suất. Vui lòng chọn lại'
            ]);
        }
        $result = $this->foodOrderRepository->addFoodToOrder($request);

        if ($result) {
            $this->foodRepository->updateOrderCount($request['foodId']);
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

    /**
     * @param $request
     * @return bool
     */
    public function checkFoodRemain($request)
    {
        $today = Carbon::now()->toDateString();
        $result = $this->foodDayRepository->checkFoodRemain($request['foodId'], $today);
        $oldFood = $this->foodOrderRepository->getOldFoodOrder($request['orderId'], $request['foodId']);
        if ($result && $request['orderNum'] > $result[FoodDayConstant::NUMBER_FIELD]) {
            return [
                'remain' => $result[FoodDayConstant::NUMBER_FIELD],
                'food_name' => $result[FoodConstant::VIETNAMESE_NAME_FIELD]
            ];
        }
        if ($result && $oldFood && $request['orderNum'] + $oldFood[FoodOrderConstant::ORDER_NUM_FIELD] > $result[FoodDayConstant::NUMBER_FIELD]) {
            return [
                'remain' => $result[FoodDayConstant::NUMBER_FIELD],
                'food_name' => $result[FoodConstant::VIETNAMESE_NAME_FIELD]
            ];
        }

        return 'true';
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createOrder(Request $request)
    {
        $user = Auth::user();
        $request = $request->all();
        $result = $this->orderRepository->createOrder($request, $user[BaseConstant::ID_FIELD]);

        if ($result) {
            try {
                $tableData = $this->tableRepository->getTableName($request['id']);
                $listFoodInOrder = $this->orderRepository->getListFoodsInOrder($result[BaseConstant::ID_FIELD]);
                $this->messageService->sendNotify($tableData, $result[BaseConstant::ID_FIELD], $listFoodInOrder, BaseConstant::SEND_WAITER, $request['id'], null, true);
                $this->messageService->sendNotify($tableData, $result[BaseConstant::ID_FIELD], $listFoodInOrder, BaseConstant::SEND_CASHIER, null, null, true);
                return response()->json([
                    'code' => '222',
                    'message' => 'Đã tạo order',
                    'order_id' => $result[BaseConstant::ID_FIELD]
                ]);
            } catch (\Exception $e) {
                Log::channel('customError')->error($e->getMessage());
                return response()->json([
                    'code' => '333',
                    'message' => 'Hãy thử lại'
                ]);
            }
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function removeOrderFood(Request $request)
    {
        $request = $request->all();
        $id = $request['tId'];
        $tableId = $request['tableId'];
        $orderId = $request['orderId'];
        $messageType = $request['messageType'];
        $tableData = $this->tableRepository->getTableName($tableId);
        $listFoodInOrder = $this->orderRepository->getListFoodsInOrder($orderId);
        try {
            $this->messageService->sendNotify($tableData, $orderId, $listFoodInOrder, $messageType);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return response()->json([
                'code' => '333',
                'message' => 'Hãy thử lại'
            ]);
        } catch (GuzzleException $e) {
            Log::channel('customError')->error($e->getMessage());
            return response()->json([
                'code' => '333',
                'message' => 'Hãy thử lại'
            ]);
        }
        $this->foodOrderRepository->removeOrderFood($id);

        return response()->json([
            'code' => '222',
            'message' => 'Đã xóa'
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeToTableStatus(Request $request)
    {
        $request = $request->all();
        $result = $this->foodOrderRepository->changeToTableStatus($request['index']);
        if ($result) {
            return response()->json([
                'code' => '222',
                'message' => 'Đã đưa món lên cho khách',
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }

    /**
     * @param mixed $orderId
     * @return void
     */
    private function checkOrderStatus(mixed $orderId)
    {
        $result = $this->orderRepository->checkOrderStatus($orderId);
        if ($result == BaseConstant::TABLE_PAID) {
            return 'true';
        }
        return 'false';
    }
}
