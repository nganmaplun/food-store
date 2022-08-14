<?php

namespace App\Http\Controllers\Cashier;

use App\Constants\BaseConstant;
use App\Constants\FoodConstant;
use App\Constants\FoodOrderConstant;
use App\Constants\OrderConstant;
use App\Constants\TableConstant;
use App\Constants\UserConstant;
use App\Http\Controllers\Controller;
use App\Repositories\FoodOrderRepository;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;
use App\Repositories\UserRepository;
use App\Services\MessageService;
use App\Services\TableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    private MessageService $messageService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(
        OrderRepository $orderRepository,
        TableRepository $tableRepository,
        FoodOrderRepository $foodOrderRepository,
        TableService $tableService,
        MessageService $messageService,
        UserRepository $userRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->tableRepository = $tableRepository;
        $this->foodOrderRepository = $foodOrderRepository;
        $this->tableService = $tableService;
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
        $chkCheckout = $this->orderRepository->getListCheckoutOrder($lstId, $today);
        $lstCount = $this->foodOrderRepository->getListCountOrder($lstId, $today);
//dd($chkCheckout);
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

    /**
     * @param $orderId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function detailOrderFinal($orderId)
    {
        $detail = $this->orderRepository->getDetailOrder($orderId);
        $orderInfo = $this->orderRepository->detailOrder($orderId);
        $cashierName = $this->userRepository->getAllCashierUser();

        return view('cashier.detail-final', ['detail' => $detail, 'orderId' => $orderId, 'orderInfo' => $orderInfo, 'cashierName' => $cashierName]);
    }

    public function checkout(Request $request, $orderId)
    {
        $role = Auth::user()[UserConstant::ROLE_FIELD];
        $request = $request->all();
        $discount = $request['voucher'] ?? 0;
        $note = $request['other_note'] ?? '';
        $otherMoney = $request['other_money'] ?? 0;
        $paidType = $request['paid_type'] ?? 0;
        $detail = $this->orderRepository->getDetailOrder($orderId);
        $userId = Auth::user()[BaseConstant::ID_FIELD];
        $price = 0;
        foreach ($detail as $dtl) {
            $price = $price + $dtl[FoodOrderConstant::ORDER_NUM_FIELD] * $dtl[FoodConstant::PRICE_FIELD];
        }
        $totalPrice = $price + $otherMoney - ($price + $otherMoney) * $discount / 100;
        $result = $this->orderRepository->updateFinalOrder($orderId, $totalPrice, $note, $paidType, $discount, $otherMoney, $userId);

        if ($result) {
            $result = $this->tableRepository->updateTableStatusToWaiter($result[OrderConstant::TABLE_ID_FIELD]);
            if ($result) {
                $tableData = $this->tableRepository->getTableName($result[OrderConstant::TABLE_ID_FIELD]);
                $this->messageService->sendNotify($tableData, $orderId, [], BaseConstant::SEND_WAITER_BACK);
                return redirect()->route($role == 'admin' ? 'admin-cashier' : 'cashier-dashboard')->with(['status' => 'Thanh toán thành công']);
            }
            return redirect()->back()->with(['status' => 'Thanh toán thất bại, hãy thử lại']);
        }

        return redirect()->back()->with(['status' => 'Thanh toán thất bại, hãy thử lại']);
    }

    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pusher\ApiErrorException
     * @throws \Pusher\PusherException
     */
    public function updateFinal($orderId)
    {
        $result = $this->orderRepository->updateFinal($orderId);

        if ($result) {
            $this->tableRepository->updateTableStatus($result[OrderConstant::TABLE_ID_FIELD]);
            return response()->json([
                'code' => '222',
                'message' => 'Đã lưu thông tin',
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }
}
