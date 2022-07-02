<?php

namespace App\Http\Controllers\Waiter;

use App\Constants\BaseConstant;
use App\Constants\TableConstant;
use App\Http\Controllers\Controller;
use App\Repositories\FoodRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;
use App\Services\TableService;
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
     * @param TableRepository $tableRepository
     */
    public function __construct(
        TableRepository $tableRepository,
        FoodRepository $foodRepository,
        TableService $tableService,
        OrderRepository $orderRepository
    ){
        $this->tableRepository = $tableRepository;
        $this->foodRepository = $foodRepository;
        $this->tableService = $tableService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard(Request $request)
    {
        $request = $request->all();
        $floor = $request[TableConstant::FLOOR_FIELD] ?? null;
        $page = $request[BaseConstant::PAGE_TEXT] ?? 1;
        $listFloors = $this->tableRepository->getListFloors();
        $listTables = $this->tableService->listTables($floor);

        return view('waiter.dashboard', ['listFloors' => $listFloors, 'listTables' => $listTables, 'page' => $page
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
        $tableName = $this->tableRepository->getTableName($tableId);
        $listFoods = $this->foodRepository->getListFoods();

        return view('waiter.list-food-order', [
            'tableName' => $tableName,
            'tableId' => $tableId,
            'orderId' => $orderId,
            'listFoods' => $listFoods
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
        $tableName = $this->tableRepository->getTableName($tableId);
        $listFoods = $this->foodRepository->getListFoods($menuId);

        return view('waiter.list-food-order', [
            'tableName' => $tableName,
            'tableId' => $tableId,
            'orderId' => $orderId,
            'menuId' => $menuId,
            'listFoods' => $listFoods
        ]);
    }

    public function orderTable($tableId, $orderId)
    {
        $tableName = $this->tableRepository->getTableName($tableId);
        $listFoodsInOrder = $this->orderRepository->getListFoodsInOrder($orderId);

        return view('vendor.table-order', [
            'tableName' => $tableName,
            'tableId' => $tableId,
            'orderId' => $orderId,
            'listFoods' => $listFoodsInOrder
        ]);
    }
}
