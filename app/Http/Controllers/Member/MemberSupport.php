<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Session;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Events\TicketCreated;
use App\Events\TicketReplied;

class MemberSupport extends Controller
{
    public function supportList()
    {

        $all_ticket = SupportTicket::where('customer_id', Auth::user('web')->id)->where('customer_type',1)
           ->orderBy('id', 'desc')->paginate(15);

       return view('member.support.support_list',compact('all_ticket'));
    }

    public function ticketCreate()
    {
        return view('member.support.add_ticket');
    }

    public function ticketStore(Request $request)
    {
        $this->validate($request, [
            'subject' =>'required',
            'detail' => 'required'
        ]);

        $a = strtoupper(md5(uniqid(rand(), true)));

        $ticket = SupportTicket::create([
           'subject' => $request->subject,
            'ticket' => substr($a, 0, 8),
            'customer_type'=>1,
            'customer_id' => Auth::user()->id,
        ]);

        SupportTicketComment::create([
           'ticket_id' => $ticket->ticket,
           'type' => 1,
           'comment' => $request->detail,
        ]);

        // Fire event to notify admin
        event(new TicketCreated($ticket));

        Session::flash('message', 'Successfully Created Ticket');
        return redirect()->route('supportList');


    }
	
	 public function ticketReply($ticket)
    {
        $ticket_object = SupportTicket::where('customer_id', Auth::user()->id)
            ->where('ticket', $ticket)->first();
        $ticket_data = SupportTicketComment::where('ticket_id', $ticket)->get();

        if ($ticket_object  == '')
        {
            return redirect()->route('pagenot.found');
        }else{
            return view('member.support.view_reply', compact('ticket_data', 'ticket_object'));
        }
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
	
	public function ticketClose($ticket)
    {
        SupportTicket::where('ticket', $ticket)
            ->update([
                'status' => 9
            ]);

            Session::flash('message', 'Your Ticket is closed');
            return redirect()->route('supportList');
        //return redirect()->back()->with('message', 'Conversation closed, But you can start again');
    }
}
