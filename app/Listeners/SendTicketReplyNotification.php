<?php

namespace App\Listeners;

use App\Events\TicketReplied;
use App\Models\Admin;
use App\Models\Merchant;
use App\Models\User;
use App\Models\AdminNotification;
use App\Models\MerchantNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTicketReplyNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TicketReplied  $event
     * @return void
     */
    public function handle(TicketReplied $event)
    {
        $ticket = $event->ticket;
        
        // Notify the ticket owner about the reply
        if ($ticket->customer_type == 0) {
            // Notify merchant
            $merchant = Merchant::find($ticket->customer_id);
            if ($merchant) {
                // You can create a MerchantNotification model similar to AdminNotification
                // For now, we'll use AdminNotification with a user_type field
            }
        } else {
            // Notify user/agent
            $user = User::find($ticket->customer_id);
            if ($user) {
                // Notify user about the reply
            }
        }

        // Also notify admins about the customer reply
        $admins = Admin::all();
        foreach ($admins as $admin) {
            AdminNotification::create([
                'user_id' => $admin->id,
                'user_type' => 'admin',
                'title' => 'Ticket Reply Received',
                'message' => "New reply received on ticket #{$ticket->ticket}",
                'ticket_id' => $ticket->ticket,
                'is_read' => 0,
                'notification_type' => 'ticket_reply'
            ]);
        }
    }
}
