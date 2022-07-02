<?php

namespace App\Http\Controllers;

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
            $messageType = $request['messageType'];
            $tableData = $this->tableRepository->getTableName($tableId);
            $listFoodInOrder = $this->orderRepository->getListFoodsInOrder($orderId);
            $this->messageService->sendNotify($tableData, $listFoodInOrder, $messageType);

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
