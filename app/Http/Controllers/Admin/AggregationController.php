<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\AggDayRepository;
use App\Repositories\AggMonthRepository;
use App\Repositories\AggYearRepository;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class AggregationController extends Controller
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var AggDayRepository
     */
    private AggDayRepository $aggDayRepository;

    /**
     * @var AggMonthRepository
     */
    private AggMonthRepository $aggMonthRepository;

    /**
     * @var AggYearRepository
     */
    private AggYearRepository $aggYearRepository;

    /**
     * @param OrderRepository $orderRepository
     * @param AggDayRepository $aggDayRepository
     * @param AggMonthRepository $aggMonthRepository
     * @param AggYearRepository $aggYearRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        AggDayRepository $aggDayRepository,
        AggMonthRepository $aggMonthRepository,
        AggYearRepository $aggYearRepository,
    )
    {
        $this->orderRepository = $orderRepository;
        $this->aggDayRepository = $aggDayRepository;
        $this->aggMonthRepository = $aggMonthRepository;
        $this->aggYearRepository = $aggYearRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function aggregateOrder(Request $request)
    {
        $request = $request->all();
        $aggBy['type'] = $request['type'] ?? 'day';
        $results = match ($aggBy['type']) {
            'day' => $this->aggDayRepository->getAll(),
            'month' => $this->aggMonthRepository->getAll(),
            'year' => $this->aggYearRepository->getAll(),
        };

        return view('admin.aggregation', ['results' => $results, 'type' => $aggBy['type']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function aggregateOrderCustom(Request $request)
    {
        $request = $request->all();
        $aggBy['type'] = $request['type'] ?? 'day';
        $aggBy['from'] = $request['date_from'] ?? '';
        $aggBy['to'] = $request['date_to'] ?? '';

        $results = $this->aggDayRepository->customReport($aggBy);

        return response()->json($results);
    }
}
