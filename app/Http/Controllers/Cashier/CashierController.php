<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;

class CashierController extends Controller
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;
    private $tableRepository;

    public function __construct(
        OrderRepository $orderRepository,
        TableRepository $tableRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->tableRepository = $tableRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        $listOrder = $this->orderRepository->getToPaidOrder();
        $tableList = $this->tableRepository->getListTable();

        return view('cashier.dashboard', ['listOrder' => $listOrder, 'tableList' => $tableList]);
    }

    public function detailOrder($orderId)
    {
        $detail = $this->orderRepository->getDetailOrder($orderId);

        return view('cashier.detail-order', ['detail' => $detail]);
    }
}
