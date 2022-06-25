<?php

namespace App\Http\Controllers;

use App\Constants\BaseConstant;
use App\Constants\TableConstant;
use App\Repositories\TableRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * @var TableRepository
     */
    private TableRepository $tableRepository;

    /**
     * @param TableRepository $tableRepository
     */
    public function __construct(
        TableRepository $tableRepository
    ) {
        $this->tableRepository = $tableRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeTableStatus(Request $request): JsonResponse
    {
        $request = $request->all();
        $this->checkTableStatus($request[BaseConstant::ID_FIELD]);
        $result = $this->tableRepository->changeTableStatus($request[BaseConstant::ID_FIELD]);
        if ($result) {
            return response()->json([
                'code' => '222',
                'message' => 'Trạng thái của bàn đã được thay đổi'
            ]);
        }

        return response()->json([
            'code' => '333',
            'message' => 'Lỗi hệ thông, vui lòng thử lại'
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse|void
     */
    private function checkTableStatus($id)
    {
        $result = $this->tableRepository->checkTableStatus($id);
        if ($result === BaseConstant::TABLE_ORDERED) {
            return response()->json([
                'code' => '111',
                'message' => 'Bàn đã được order'
            ]);
        }
    }
}
