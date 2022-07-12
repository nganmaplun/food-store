<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstant;
use App\Constants\FoodOrderConstant;
use App\Repositories\OrderRepository;
use App\Repositories\TableRepository;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class SendMessageController extends Controller
{
    /**
     * @var MessageService
     */
    private MessageService $messageService;

    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var TableRepository
     */
    private TableRepository $tableRepository;

    /**
     * @param MessageService $messageService
     * @param OrderRepository $orderRepository
     * @param TableRepository $tableRepository
     */
    public function __construct(
        MessageService $messageService,
        OrderRepository $orderRepository,
        TableRepository $tableRepository
    ){
        $this->messageService = $messageService;
        $this->orderRepository = $orderRepository;
        $this->tableRepository = $tableRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Pusher\ApiErrorException
     * @throws \Pusher\PusherException
     */
    public function sendMessage(Request $request)
    {
        try {
            $request = $request->all();
            $tableId = $request['tableId'];
            $orderId = $request['orderId'];
            $foodId = $request['foodId'] ?? '';
            $messageType = $request['messageType'];
            $tableData = $this->tableRepository->getTableName($tableId);
            $listFoodInOrder = $this->orderRepository->getListFoodsInOrder($orderId, $foodId);
            $hasNew = false;
            if ($messageType == BaseConstant::SEND_CHEF) {
                foreach ($listFoodInOrder as $food) {
                    if ($food[FoodOrderConstant::IS_NEW_FIELD]) {
                        $hasNew = true;
                        break;
                    }
                }
            }
            if (!$hasNew && $messageType == BaseConstant::SEND_CHEF) {
                return response()->json([
                    'code' => '444',
                    'message' => 'Không có món mới gửi bếp'
                ]);
            }
            $this->messageService->sendNotify($tableData, $orderId, $listFoodInOrder, $messageType, $tableId, $foodId);

            return response()->json([
                'code' => '222',
                'message' => 'Đã gửi'
            ]);
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            return response()->json([
                'code' => '333',
                'message' => 'Hãy thử lại'
            ]);
        }
    }
}
