<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class McDepositCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $amount;
    public $userid;
    public $workcode;
    public $mcpassword;
    public $betuserid;
    public $trxid;
    public $appguid;

    /**
     * Create a new event instance.
     */
    public function __construct($workcode,$userid,$mcpassword,$appguid,$trxid,$betuserid,$amount)
    {
        $this->userid = $userid;
        $this->mcpassword = $mcpassword;
        $this->workcode = $workcode;
        $this->amount = $amount;
        $this->trxid = $trxid;
        $this->betuserid = $betuserid;
        $this->appguid = $appguid;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
