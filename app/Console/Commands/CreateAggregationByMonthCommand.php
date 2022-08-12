<?php

namespace App\Console\Commands;

use App\Constants\AggMonthConstant;
use App\Entities\AggMonth;
use App\Repositories\OrderRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateAggregationByMonthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agg:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregation by month';

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
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $condition['type'] = 'month';
            $results = $this->orderRepository->aggOrder($condition);
            $data = [];
            $i = 0;
            foreach ($results as $result) {
                $data[$i][AggMonthConstant::TOTAL_FOOD_FIELD] = $result[AggMonthConstant::TOTAL_FOOD_FIELD] ?? 0;
                $data[$i][AggMonthConstant::TOTAL_PRICE_FIELD] = $result[AggMonthConstant::TOTAL_PRICE_FIELD] ?? 0;
                $data[$i][AggMonthConstant::ORDER_DATE_FIELD] = $result[AggMonthConstant::ORDER_DATE_FIELD];
                $data[$i][AggMonthConstant::JAPANESE_GUEST_FIELD] = $result[AggMonthConstant::JAPANESE_GUEST_FIELD] ?? 0;
                $data[$i][AggMonthConstant::VIETNAMESE_GUEST_FIELD] = $result[AggMonthConstant::VIETNAMESE_GUEST_FIELD] ?? 0;
                $data[$i][AggMonthConstant::ENGLISH_GUEST_FIELD] = $result[AggMonthConstant::ENGLISH_GUEST_FIELD] ?? 0;
                $i++;
            }
            if (!empty($data)) {
                AggMonth::truncate();
                AggMonth::insert($data);
            }
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            exit();
        }
    }
}
