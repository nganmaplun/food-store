<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class AggregationController extends Controller
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function aggregateOrder(Request $request)
    {
        $request = $request->all();
        $aggBy['type'] = $request['type'] ?? 'day';

        $results = $this->orderRepository->aggOrder($aggBy);

        return view('admin.aggregation', ['results' => $results, 'type' => $aggBy]);
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

        $results = $this->orderRepository->aggOrder($aggBy);

        return response()->json($results);
    }
}
