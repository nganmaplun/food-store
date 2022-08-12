<?php

namespace App\Console\Commands;

use App\Constants\AggDayConstant;
use App\Entities\AggDay;
use App\Repositories\OrderRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateAggregationByDayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agg:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregation by day';

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
            $condition['type'] = 'day';
            $results = $this->orderRepository->aggOrder($condition);
            $data = [];
            $i = 0;
            foreach ($results as $result) {
                $data[$i][AggDayConstant::TOTAL_FOOD_FIELD] = $result[AggDayConstant::TOTAL_FOOD_FIELD] ?? 0;
                $data[$i][AggDayConstant::TOTAL_PRICE_FIELD] = $result[AggDayConstant::TOTAL_PRICE_FIELD] ?? 0;
                $data[$i][AggDayConstant::ORDER_DATE_FIELD] = $result[AggDayConstant::ORDER_DATE_FIELD];
                $data[$i][AggDayConstant::JAPANESE_GUEST_FIELD] = $result[AggDayConstant::JAPANESE_GUEST_FIELD] ?? 0;
                $data[$i][AggDayConstant::VIETNAMESE_GUEST_FIELD] = $result[AggDayConstant::VIETNAMESE_GUEST_FIELD] ?? 0;
                $data[$i][AggDayConstant::ENGLISH_GUEST_FIELD] = $result[AggDayConstant::ENGLISH_GUEST_FIELD] ?? 0;
                $i++;
            }
            if (!empty($data)) {
                AggDay::truncate();
                AggDay::insert($data);
            }
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            exit();
        }
    }
}
