<?php

namespace App\Http\Controllers\Admin;

use App\Constants\BaseConstant;
use App\Http\Controllers\Controller;
use App\Repositories\FoodDayRepository;
use App\Repositories\FoodRepository;
use App\Repositories\TimesheetRepository;
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
     * @var FoodDayRepository
     */
    private FoodDayRepository $foodDayRepository;

    /**
     * @param TimesheetRepository $timesheetRepository
     * @param FoodRepository $foodRepository
     */
    public function __construct(
        TimesheetRepository $timesheetRepository,
        FoodRepository $foodRepository,
        FoodDayRepository $foodDayRepository
    ) {
        $this->timesheetRepository = $timesheetRepository;
        $this->foodRepository = $foodRepository;
        $this->foodDayRepository = $foodDayRepository;
        $this->today = Carbon::now()->toDateString();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function timesheet(Request $request)
    {
        $request = $request->all();
        $page = $request[BaseConstant::PAGE_TEXT] ?? 1;
        $listTimesheet = $this->timesheetRepository->getListEmployee($this->today, $request);

        return view('admin.timesheet', ['listTimesheet' => $listTimesheet, 'today' => $this->today, 'page' => $page]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function approveTimesheet(Request $request): JsonResponse
    {
        $index = $request->get('index');
        $result = $this->timesheetRepository->approveTimesheet($index);
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

        return view('admin.setting-food', ['listFoods' => $listFoods, 'today' => $this->today]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function setFood(Request $request): JsonResponse
    {
        $request = $request->all();
        $result = $this->foodDayRepository->setFoodNumberToday($request);
        if ($result) {

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }
}
