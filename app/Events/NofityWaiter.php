<?php

namespace App\Events;

use App\Constants\BaseConstant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NofityWaiter extends Notify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $orderId;

    private string $tableId;

    private mixed $createTable;

    private mixed $paid;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->orderId = $data['orderId'];
        $this->tableId = $data['tableId'];
        $this->createTable = $data['createTable'];
        $this->paid = $data['paid'];
        parent::__construct($data);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel(BaseConstant::WAITER_CHANNEL);
    }
}
