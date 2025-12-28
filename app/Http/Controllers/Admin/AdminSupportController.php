<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Models\Merchant;
use App\Models\User;
use App\Models\AdminNotification;
use App\Models\MerchantNotification;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;

class AdminSupportController extends Controller
{
    public function index(Request $request){

        $query = SupportTicket::orderBy('id', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        // Filter by customer type
        if ($request->has('customer_type') && $request->customer_type !== '') {
            $query->where('customer_type', $request->customer_type);
        }

        // Search by ticket ID or subject
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $all_ticket = $query->paginate(15);

        return view('admin.support.support_list',compact('all_ticket'));
    }

    public function view_ticket($ticket){

        $data['ticket'] = SupportTicketComment::where('ticket_id',$ticket)->get();
        $data['ticket_head']= SupportTicket::where('ticket',$ticket)->first();
        if($data['ticket_head']->customer_type == 0){
            $data['ticket_user'] = Merchant::where('id',$data['ticket_head']->customer_id)->first();
        } else {
            $data['ticket_user'] = User::where('id',$data['ticket_head']->customer_id)->first();
        }
        
        // Get attachments for this ticket
        $attachments = \App\Models\SupportAttachment::where('ticket_id', $data['ticket_head']->id)->get();

        return view('admin.support.view_reply_ticket',compact('data', 'attachments'));
    }

    public function submitSolutionTicket(Request $request){

      $this->validate($request, [
          'detail' => 'required',
          'attachments.*' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx,txt,zip',
      ]);

      $ticket_reply = SupportTicketComment::where('ticket_id', $request->ticket)->get();
      $get_last_massage = $ticket_reply->last();
      $solution_ticket= SupportTicketComment::where('id',$get_last_massage->id)->first();
      $solution_ticket->comment_reply = $request->detail;
      $solution_ticket->save();

      if( $solution_ticket){
        $ticketObj = SupportTicket::where('ticket', $request->ticket)->first();
        
        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = time() . '_' . uniqid() . '_' . $originalName;
                $filePath = $file->storeAs('support_attachments', $fileName, 'public');
                
                \App\Models\SupportAttachment::create([
                    'ticket_id' => $ticketObj->id,
                    'original_name' => $originalName,
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'uploaded_by_type' => 'admin',
                    'uploaded_by_id' => Auth::guard('admin')->id(),
                ]);
            }
        }
        
        $change_status = SupportTicket::where('ticket', $request->ticket)
        ->update([
            'status' => 2,
            'last_reply_at' => now()
        ]);

        // Notify merchant about admin reply
        if($change_status && $ticketObj && $ticketObj->customer_type == 0){
            MerchantNotification::create([
                'merchant_id' => $ticketObj->customer_id,
                'type' => 'support_reply',
                'title' => 'Admin Replied to Your Ticket',
                'message' => "Admin has replied to your ticket #{$ticketObj->ticket}",
                'ticket_id' => $ticketObj->id,
                'ticket_number' => $ticketObj->ticket,
                'is_read' => 0
            ]);
        }

        if($change_status){
            Session::flash('message', 'Solution posted successfully');
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

    public function getNotifications()
    {
        $notifications = AdminNotification::where('user_id', Auth::guard('admin')->id())
            ->where('user_type', 'admin')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $unreadCount = AdminNotification::where('user_id', Auth::guard('admin')->id())
            ->where('user_type', 'admin')
            ->where('is_read', 0)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markNotificationAsRead($id)
    {
        $notification = AdminNotification::where('id', $id)
            ->where('user_id', Auth::guard('admin')->id())
            ->first();

        if ($notification) {
            $notification->is_read = 1;
            $notification->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function markAllNotificationsAsRead()
    {
        AdminNotification::where('user_id', Auth::guard('admin')->id())
            ->where('user_type', 'admin')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json(['success' => true]);
    }

    public function downloadAttachment($id)
    {
        $attachment = \App\Models\SupportAttachment::findOrFail($id);
        
        $filePath = storage_path('app/public/' . $attachment->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        // For images and PDFs, display inline in browser
        $mimeType = $attachment->file_type;
        if (str_contains($mimeType, 'image') || str_contains($mimeType, 'pdf')) {
            return response()->file($filePath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $attachment->original_name . '"'
            ]);
        }
        
        // For other files, force download
        return response()->download($filePath, $attachment->original_name);
    }

    public function viewAttachment($id)
    {
        $attachment = \App\Models\SupportAttachment::findOrFail($id);
        
        $filePath = storage_path('app/public/' . $attachment->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }
        
        return response()->file($filePath, [
            'Content-Type' => $attachment->file_type,
            'Content-Disposition' => 'inline; filename="' . $attachment->original_name . '"'
        ]);
    }
}
