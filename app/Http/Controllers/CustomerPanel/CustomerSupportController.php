<?php

namespace App\Http\Controllers\CustomerPanel;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\BalanceManager;
use App\Models\ServiceRequest;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Events\TicketCreated;
use App\Events\TicketReplied;

class CustomerSupportController extends Controller
{
    public function create_support_view(){

        return view('customer-panel.support.create_support_view');
    }

    public function support_submit(Request $request){

        $this->validate($request, [
            'subject' =>'required',
            'detail' => 'required'
        ]);

        $a = strtoupper(md5(uniqid(rand(), true)));

        $ticket = SupportTicket::create([
           'subject' => $request->subject,
            'ticket' => substr($a, 0, 8),
            'customer_id' =>  auth('customer')->user()->id,
            'customer_type'=>2


        ]);

        SupportTicketComment::create([
           'ticket_id' => $ticket->ticket,
           'type' => 1,
           'comment' => $request->detail,
        ]);

        // Fire event to notify admin
        event(new TicketCreated($ticket));

        Session::flash('message', 'Successfully Created Ticket');
        return redirect()->route('customer.support_list_view');

    }


    public function support_list()
    {

        //  dd(auth('customer')->user()->id);

        $all_ticket = SupportTicket::where('customer_id',auth('customer')->user()->id)->where('customer_type',2)
           ->orderBy('id', 'desc')->paginate(15);

       return view('customer-panel.support.support_list',compact('all_ticket'));
    }

    public function ticketReply($ticket)
    {
        $ticket_object = SupportTicket::where('customer_id',auth('customer')->user()->id)->where('customer_type',2)
            ->where('ticket', $ticket)->first();
        $ticket_data = SupportTicketComment::where('ticket_id', $ticket)->get();

        if ($ticket_object  == '')
        {
            return redirect()->route('pagenot.found');
        }else{
            return view('customer-panel.support.ticket_reply', compact('ticket_data', 'ticket_object'));
        }
    }

    public function ticketClose($ticket)
    {
        SupportTicket::where('ticket', $ticket)
            ->update([
                'status' => 9
            ]);

            Session::flash('message', 'Your Ticket is closed');
            return redirect()->route('customer.support_list_view');
        //return redirect()->back()->with('message', 'Conversation closed, But you can start again');
    }
    public function ticketReplyStore(Request $request, $ticket)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);

        SupportTicketComment::create([
            'ticket_id' => $ticket,
            'type' => 1,
            'comment' => $request->comment,
        ]);

        SupportTicket::where('ticket', $ticket)
            ->update([
               'status' => 3,
               'last_reply_at' => now()
            ]);

        // Fire event to notify admin about reply
        $ticketObject = SupportTicket::where('ticket', $ticket)->first();
        event(new TicketReplied($ticketObject));

        return redirect()->back()->with('message', 'Message Send Successful');
    }
}
