<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Models\Admin;
use App\Models\Merchant;
use App\Models\AdminNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTicketNotification
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
     * @param  \App\Events\TicketCreated  $event
     * @return void
     */
    public function handle(TicketCreated $event)
    {
        $ticket = $event->ticket;
        
        // Determine who created the ticket
        $userType = $ticket->customer_type == 0 ? 'Merchant' : ($ticket->customer_type == 1 ? 'Agent' : 'Customer');
        
        // Get user name
        $userName = '';
        if ($ticket->customer_type == 0) {
            $merchant = Merchant::find($ticket->customer_id);
            $userName = $merchant ? $merchant->fullname : 'Unknown';
        } else {
            $user = \App\Models\User::find($ticket->customer_id);
            $userName = $user ? $user->fullname : 'Unknown';
        }

        // Notify all admins
        $admins = Admin::all();
        foreach ($admins as $admin) {
            AdminNotification::create([
                'user_id' => $admin->id,
                'user_type' => 'admin',
                'title' => 'New Support Ticket',
                'message' => "{$userType} {$userName} has created a new support ticket: {$ticket->subject}",
                'ticket_id' => $ticket->ticket,
                'is_read' => 0,
                'notification_type' => 'ticket_created'
            ]);
        }

        // If ticket is from customer/agent, notify the merchant (if applicable)
        if ($ticket->customer_type != 0) {
            // You can add logic here to notify specific merchants if needed
        }
    }
}
