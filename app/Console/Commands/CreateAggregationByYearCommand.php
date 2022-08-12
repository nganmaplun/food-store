<?php

namespace App\Console\Commands;

use App\Constants\AggYearConstant;
use App\Entities\AggYear;
use App\Repositories\OrderRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateAggregationByYearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agg:year';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggregation by year';

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
            $condition['type'] = 'year';
            $results = $this->orderRepository->aggOrder($condition);
            $data = [];
            $i = 0;
            foreach ($results as $result) {
                $data[$i][AggYearConstant::TOTAL_FOOD_FIELD] = $result[AggYearConstant::TOTAL_FOOD_FIELD] ?? 0;
                $data[$i][AggYearConstant::TOTAL_PRICE_FIELD] = $result[AggYearConstant::TOTAL_PRICE_FIELD] ?? 0;
                $data[$i][AggYearConstant::ORDER_DATE_FIELD] = $result[AggYearConstant::ORDER_DATE_FIELD];
                $data[$i][AggYearConstant::JAPANESE_GUEST_FIELD] = $result[AggYearConstant::JAPANESE_GUEST_FIELD] ?? 0;
                $data[$i][AggYearConstant::VIETNAMESE_GUEST_FIELD] = $result[AggYearConstant::VIETNAMESE_GUEST_FIELD] ?? 0;
                $data[$i][AggYearConstant::ENGLISH_GUEST_FIELD] = $result[AggYearConstant::ENGLISH_GUEST_FIELD] ?? 0;
                $i++;
            }
            if (!empty($data)) {
                AggYear::truncate();
                AggYear::insert($data);
            }
        } catch (\Exception $e) {
            Log::channel('customError')->error($e->getMessage());
            exit();
        }
    }
}
