<?php

namespace App\Http\Controllers\Waiter;

use App\Constants\BaseConstant;
use App\Constants\OrderConstant;
use App\Constants\TableConstant;
use App\Http\Controllers\Controller;
use App\Repositories\FoodOrderRepository;
use App\Repositories\FoodRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;
use App\Repositories\UserRepository;
use App\Services\MessageService;
use App\Services\TableService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WaiterController extends Controller
{
    /**
     * @var TableRepository
     */
    private TableRepository $tableRepository;

    /**
     * @var FoodRepository
     */
    private FoodRepository $foodRepository;

    /**
     * @var TableService
     */
    private TableService $tableService;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var FoodOrderRepository
     */
    private FoodOrderRepository $foodOrderRepository;
    /**
     * @var
     */
    private MessageService $messageService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @param TableRepository $tableRepository
     * @param FoodRepository $foodRepository
     * @param TableService $tableService
     * @param OrderRepository $orderRepository
     * @param FoodOrderRepository $foodOrderRepository
     * @param MessageService $messageService
     */
    public function __construct(
        TableRepository $tableRepository,
        FoodRepository $foodRepository,
        TableService $tableService,
        OrderRepository $orderRepository,
        FoodOrderRepository $foodOrderRepository,
        MessageService $messageService,
        UserRepository $userRepository
    ){
        $this->tableRepository = $tableRepository;
        $this->foodRepository = $foodRepository;
        $this->tableService = $tableService;
        $this->orderRepository = $orderRepository;
        $this->foodOrderRepository = $foodOrderRepository;
        $this->messageService = $messageService;
        $this->userRepository = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard(Request $request)
    {
        $request = $request->all();
        $floor = $request[TableConstant::FLOOR_FIELD] ?? null;
        $page = $request[BaseConstant::PAGE_TEXT] ?? 1;
        $today = Carbon::now()->toDateString();
        $listFloors = $this->tableRepository->getListFloors();
        $listTables = $this->tableService->listTables($floor);
        $lstId = [];
        foreach ($listTables as $tbl) {
            $lstId[] = $tbl[BaseConstant::ID_FIELD];
        }
        $lstCount = $this->foodOrderRepository->getListCountOrder($lstId, $today);
        $chkCheckout = $this->orderRepository->getListCheckoutOrder($lstId, $today);

        return view('waiter.dashboard', [
            'listFloors' => $listFloors,
            'listTables' => $listTables,
            'page' => $page,
            'lstCount' => $lstCount,
            'currentFloor' => $floor,
            'chkCheckout' => $chkCheckout,
        ]);
    }

    /**
     * @param Request $request
     * @param $tableId
     * @param $orderId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function listFoodsForOrder(Request $request, $tableId, $orderId)
    {
        $foodName = $request->food_name ?? '';
        $tableName = $this->tableRepository->getTableName($tableId);
        $listFoods = $this->foodRepository->getListFoodsMenu($foodName);

        return view('waiter.list-food-order', [
            'tableName' => $tableName,
            'tableId' => $tableId,
            'orderId' => $orderId,
            'listFoods' => $listFoods,
            'foodName' => $foodName
        ]);
    }

    /**
     * @param Request $request
     * @param $tableId
     * @param $orderId
     * @param $menuId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function listFoodsByMenu(Request $request, $tableId, $orderId, $menuId)
    {
        $foodName = $request->food_name ?? '';
        $tableName = $this->tableRepository->getTableName($tableId);
        $listFoods = $this->foodRepository->getListFoods($menuId, $foodName);

        return view('waiter.list-food-order', [
            'tableName' => $tableName,
            'tableId' => $tableId,
            'orderId' => $orderId,
            'menuId' => $menuId,
            'listFoods' => $listFoods,
            'foodName' => $foodName
        ]);
    }

    /**
     * @param $tableId
     * @param $orderId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function orderTable($tableId, $orderId)
    {
        $tableName = $this->tableRepository->getTableName($tableId);
        $listFoodsInOrder = $this->orderRepository->getListFoodsInOrder($orderId);
        $orderInfo = $this->orderRepository->detailOrder($orderId);

        return view('vendor.table-order', [
            'tableName' => $tableName,
            'tableId' => $tableId,
            'orderId' => $orderId,
            'listFoods' => $listFoodsInOrder,
            'orderInfo' => $orderInfo
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function foodStand()
    {
        $today = Carbon::now()->toDateString();
        $listFoods = $this->foodOrderRepository->getAllFinishFood($today);

        return view('waiter.finished_food', ['listFoods' => $listFoods]);
    }

    /**
     * @param $orderId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateFinal($orderId)
    {
        $result = $this->orderRepository->updateFinal($orderId);

        if ($result) {
            $this->tableRepository->updateTableStatusToWaiter($result[OrderConstant::TABLE_ID_FIELD]);
            $tableData = $this->tableRepository->getTableName($result[OrderConstant::TABLE_ID_FIELD]);
            $this->messageService->sendNotify($tableData, $orderId, [], BaseConstant::SEND_CASHIER);
            return response()->json([
                'code' => '222',
                'message' => 'Xác nhận thành công',
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }

    /**
     * @param $orderId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function detailOrder($orderId)
    {
        $detail = $this->orderRepository->getDetailOrder($orderId);
        $orderInfo = $this->orderRepository->detailOrder($orderId);
        $cashierName = $this->userRepository->getAllCashierUser();

        return view('waiter.detail-order', ['detail' => $detail, 'orderId' => $orderId, 'orderInfo' => $orderInfo, 'cashierName' => $cashierName]);
    }

    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function reEditOrder($orderId)
    {
        $result = $this->orderRepository->updateOrderStatus($orderId, BaseConstant::SEND_CASHIER);
        if ($result) {
            $this->tableRepository->updateReEditTableStatus($result[OrderConstant::TABLE_ID_FIELD]);
            $tableData = $this->tableRepository->getTableName($result[OrderConstant::TABLE_ID_FIELD]);
            $this->messageService->sendNotify($tableData, $orderId, [], BaseConstant::SEND_CASHIER);
            return response()->json([
                'code' => '222',
                'message' => 'Đã yêu cầu sửa lại hóa đơn',
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }
}
