<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class McWithdrawCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $code;
    public $userid;
    public $workcode;
    public $mcpassword;
    public $betuserid;
    public $trxid;
    public $appguid;

    /**
     * Create a new event instance.
     */
    public function __construct($workcode,$userid,$mcpassword,$appguid,$trxid,$betuserid,$code)
    {
        $this->userid = $userid;
        $this->mcpassword = $mcpassword;
        $this->workcode = $workcode;
        $this->code = $code;
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
