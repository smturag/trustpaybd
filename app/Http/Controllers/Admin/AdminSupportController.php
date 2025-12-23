<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Models\Merchant;
use App\Models\User;
use Session;
use Illuminate\Support\Facades\Redirect;

class AdminSupportController extends Controller
{
    public function index(){

        $all_ticket = SupportTicket::orderBy('id', 'desc')->paginate(15);

        return view('admin.support.support_list',compact('all_ticket'));
    }

    public function view_ticket($ticket){

        $data['ticket'] = SupportTicketComment::where('ticket_id',$ticket)->get();
        $data['ticket_head']= SupportTicket::where('ticket',$ticket)->first();
        if($data['ticket_head']->customer_type == 0){
            $data['ticket_user'] = Merchant::where('id',$data['ticket_head']->customer_id)->first();
        }
        $data['ticket_user'] = User::where('id',$data['ticket_head']->customer_id)->first();

        return view('admin.support.view_reply_ticket',compact('data'));


        
    }

    public function submitSolutionTicket(Request $request){

      $ticket_reply = SupportTicketComment::where('ticket_id', $request->ticket)->get();
      $get_last_massage = $ticket_reply->last();
      $solution_ticket= SupportTicketComment::where('id',$get_last_massage->id)->first();
      $solution_ticket->comment_reply = $request->detail;
      $solution_ticket->save();

      if( $solution_ticket){
        $change_status = SupportTicket::where('ticket', $request->ticket)
        ->update([
            'status' => 2
        ]);

        if($change_status){
           
            return Redirect::back();
      
        }
      }

      return Redirect::back()->with('alert', 'Solution  not posted .');
      


     }

    public function closeTicket(Request $request){
        $ticket = SupportTicket::where('ticket', $request->ticket_name)
            ->update([
                'status' => 9
            ]);

            if($ticket){
                $data=[
                    'success'=> true,
                ];
    
                return response()->json($data);
            }

            $data=[
                'success'=> false,
            ];

            return response()->json($data);
           
    }
}
