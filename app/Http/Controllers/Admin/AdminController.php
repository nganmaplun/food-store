<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\TableConstant;
use App\Http\Controllers\Controller;
use App\Repositories\AggDayRepository;
use App\Repositories\FoodOrderRepository;
use App\Repositories\FoodRepository;
use App\Repositories\TableRepository;
use App\Repositories\TimesheetRepository;
use App\Repositories\UserRepository;
use App\Services\TableService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController  extends Controller
{
    /**
     * @var TimesheetRepository
     */
    private TimesheetRepository $timesheetRepository;

    /**
     * @var FoodRepository
     */
    private FoodRepository $foodRepository;

    /**
     * @var string
     */
    private string $today;

    /**
     * @var AggDayRepository
     */
    private AggDayRepository $foodDayRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var TableRepository
     */
    private TableRepository $tableRepository;

    /**
     * @var TableService
     */
    private TableService $tableService;

    /**
     * @var FoodOrderRepository
     */
    private FoodOrderRepository $foodOrderRepository;

    /**
     * @param TimesheetRepository $timesheetRepository
     * @param UserRepository $userRepository
     * @param FoodRepository $foodRepository
     * @param AggDayRepository $foodDayRepository
     * @param TableRepository $tableRepository
     */
    public function __construct(
        TimesheetRepository $timesheetRepository,
        UserRepository      $userRepository,
        FoodRepository      $foodRepository,
        AggDayRepository    $foodDayRepository,
        TableRepository     $tableRepository,
        TableService        $tableService,
        FoodOrderRepository $foodOrderRepository
    ) {
        $this->timesheetRepository = $timesheetRepository;
        $this->userRepository = $userRepository;
        $this->foodRepository = $foodRepository;
        $this->foodDayRepository = $foodDayRepository;
        $this->tableRepository = $tableRepository;
        $this->tableService = $tableService;
        $this->foodOrderRepository = $foodOrderRepository;
        $this->today = Carbon::now()->toDateString();
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

        return view('admin.dashboard', [
            'listFloors' => $listFloors,
            'listTables' => $listTables,
            'page' => $page,
            'lstCount' => $lstCount,
            'currentFloor' => $floor
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function timesheet(Request $request)
    {
        $request = $request->all();
        $page = $request[BaseConstant::PAGE_TEXT] ?? 1;
        $listTimesheet = $this->userRepository->getListEmployee($this->today, $request);

        return view('admin.timesheet', ['listTimesheet' => $listTimesheet, 'today' => $this->today, 'page' => $page]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function approveTimesheet(Request $request): JsonResponse
    {
        $index = $request->get('index');
        $type = $request->get('type');
        $result = $this->timesheetRepository->approveTimesheet($index, $type);
        if ($result) {

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function settingFood(Request $request)
    {
        $listFoods = $this->foodRepository->getListFoods($this->today);
        $listAllFoodName = $this->foodRepository->listAllFoodName();

        return view('admin.setting-food', [
            'listFoods' => $listFoods,
            'today' => $this->today,
            'listAllFoodName' => $listAllFoodName
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function setFood(Request $request): JsonResponse
    {
        $request = $request->all();
        $foodId = $this->foodRepository->getFoodIdByName($request);
        $request['index'] = $foodId[BaseConstant::ID_FIELD] ?? $request['index'] ;
        $result = $this->foodDayRepository->setFoodNumberToday($request);
        if ($result) {

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetTable()
    {
        $result = $this->tableRepository->resetTable();

        if ($result) {
            return redirect()->route('admin-dashboard')->with(['status' => 'Reset bàn thành công']);
        }
        return redirect()->route('admin-dashboard')->with(['status' => 'Reset bàn thất bại. Hãy thử lại']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteFood(Request $request)
    {
        $request = $request->all();
        $result = $this->foodDayRepository->deleteSetFoodToday($request);
        if ($result) {

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }
}
