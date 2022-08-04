<?php

namespace App\Http\Controllers\Cashier;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodOrderConstant;
use App\Constants\OrderConstant;
use App\Constants\TableConstant;
use App\Http\Controllers\Controller;
use App\Repositories\FoodOrderRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;
use App\Services\MessageService;
use App\Services\TableService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var TableRepository
     */
    private TableRepository $tableRepository;

    /**
     * @var FoodOrderRepository
     */
    private FoodOrderRepository $foodOrderRepository;

    /**
     * @var TableService
     */
    private TableService $tableService;
    /**
     * @var
     */
    private $messageService;

    public function __construct(
        OrderRepository $orderRepository,
        TableRepository $tableRepository,
        FoodOrderRepository $foodOrderRepository,
        TableService $tableService,
        MessageService $messageService
    ) {
        $this->orderRepository = $orderRepository;
        $this->tableRepository = $tableRepository;
        $this->foodOrderRepository = $foodOrderRepository;
        $this->tableService = $tableService;
        $this->messageService = $messageService;
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
        $chkCheckout = $this->orderRepository->getListCheckoutOrder($lstId, $today);
        $lstCount = $this->foodOrderRepository->getListCountOrder($lstId, $today);

        return view('cashier.dashboard', [
            'listFloors' => $listFloors,
            'listTables' => $listTables,
            'page' => $page,
            'chkCheckout' => $chkCheckout,
            'lstCount' => $lstCount
        ]);
    }

    public function detailOrder($orderId)
    {
        $detail = $this->orderRepository->getDetailOrder($orderId);
        $orderInfo = $this->orderRepository->detailOrder($orderId);

        return view('cashier.detail-order', ['detail' => $detail, 'orderId' => $orderId, 'orderInfo' => $orderInfo]);
    }

    public function checkout(Request $request, $orderId)
    {
        $request = $request->all();
        $discount = $request['voucher'] ?? 0;
        $note = $request['other_note'] ?? '';
        $otherMoney = $request['other_money'] ?? 0;
        $paidType = $request['paid_type'] ?? 0;
        $detail = $this->orderRepository->getDetailOrder($orderId);
        $price = 0;
        foreach ($detail as $dtl) {
            $price = $price + $dtl[FoodOrderConstant::ORDER_NUM_FIELD] * $dtl[FoodConstant::PRICE_FIELD];
        }
        $totalPrice = $price + $otherMoney + ($price + $otherMoney) * 0.08 - ($price + $otherMoney + ($price + $otherMoney) * 0.08) * $discount / 100;
        $result = $this->orderRepository->updateFinalOrder($orderId, $totalPrice, $note, $paidType, $discount, $otherMoney);

        if ($result) {
            $result = $this->tableRepository->updateTableStatus($result[OrderConstant::TABLE_ID_FIELD]);
            if ($result) {
                $tableData = $this->tableRepository->getTableName($result[OrderConstant::TABLE_ID_FIELD]);
                $this->messageService->sendNotify($tableData, $orderId, [], BaseConstant::SEND_WAITER_BACK);
                return redirect()->route('cashier-dashboard')->with(['status' => 'Thanh toán thành công']);
            }
            return redirect()->back()->with(['status' => 'Thanh toán thất bại, hãy thử lại']);
        }

        return redirect()->back()->with(['status' => 'Thanh toán thất bại, hãy thử lại']);
    }
}
