<?php

namespace App\Http\Controllers\Waiter;

use App\Constants\BaseConstant;
use App\Constants\TableConstant;
use App\Http\Controllers\Controller;
use App\Repositories\FoodOrderRepository;
use App\Repositories\FoodRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;
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
     * @param TableRepository $tableRepository
     * @param FoodRepository $foodRepository
     * @param TableService $tableService
     * @param OrderRepository $orderRepository
     * @param FoodOrderRepository $foodOrderRepository
     */
    public function __construct(
        TableRepository $tableRepository,
        FoodRepository $foodRepository,
        TableService $tableService,
        OrderRepository $orderRepository,
        FoodOrderRepository $foodOrderRepository
    ){
        $this->tableRepository = $tableRepository;
        $this->foodRepository = $foodRepository;
        $this->tableService = $tableService;
        $this->orderRepository = $orderRepository;
        $this->foodOrderRepository = $foodOrderRepository;
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

        return view('waiter.dashboard', [
            'listFloors' => $listFloors,
            'listTables' => $listTables,
            'page' => $page,
            'lstCount' => $lstCount
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
}
