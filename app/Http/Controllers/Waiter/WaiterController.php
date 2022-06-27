<?php

namespace App\Http\Controllers\Waiter;

use App\Constants\BaseConstant;
use App\Constants\TableConstant;
use App\Http\Controllers\Controller;
use App\Repositories\FoodRepository;
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
     * @param TableRepository $tableRepository
     */
    public function __construct(
        TableRepository $tableRepository,
        FoodRepository $foodRepository,
        TableService $tableService
    ){
        $this->tableRepository = $tableRepository;
        $this->foodRepository = $foodRepository;
        $this->tableService = $tableService;
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
}
