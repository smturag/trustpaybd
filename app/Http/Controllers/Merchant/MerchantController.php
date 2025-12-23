<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Auth;
use Session;

use Illuminate\Support\Facades\DB;
use App\Models\BalanceManager;
use App\Models\ServiceRequest;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;

class MerchantController extends Controller
{
    public function dashboard(Request $request, $sim_number = null)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '50';

        $sender = $request->get('sender');
        $start_date = $request->get('from');
        $end_date = $request->get('to');

        $authData = auth('merchant')->user();
        $authid = $authData->id;
        $username = $authData->username;
        $type = $authData->merchant_type;

        $qrdata = BalanceManager::where('merchent_id', $username)
            ->where('status', [20, 77])
            ->orderBy($sort_by, $sort_type);

        // if($type == 'sub_merchant'){
        //     $qrdata = BalanceManager::where('merchent_id', $username)->where('status', [20, 77])->orderBy($sort_by, $sort_type);
        // }else{

        // }

        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date('Y-m-d H:i', strtotime($start_date));
            $end_date = date('Y-m-d H:i', strtotime($end_date));
            $qrdata->whereBetween(DB::raw('date(idate)'), [$start_date, $end_date]);
        }

        if (!empty($request->type)) {
            if ($request->type == 'cashout') {
                $qrdata->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout']);
            }

            if ($request->type == 'cashin') {
                $qrdata->whereIn('type', ['ngcashin', 'bkcashin', 'rccashin']);
            }

            if ($request->type == 'b2b') {
                $qrdata->whereIn('type', ['bkB2B', 'ngB2B', 'rcB2B']);
            }
            if ($request->type == 'RC') {
                $qrdata->whereIn('type', ['bkRC', 'ngB2BRC', 'rcB2BRC']);
            }
        }

        if (!empty($sender)) {
            $qrdata->where('sender', $sender);
        }

        if (!empty($request->trxid)) {
            $qrdata->where('trxid', $request->trxid);
        }

        $data = $qrdata->paginate($rows);

        if ($request->ajax()) {
            return view('merchant.transaction-content', compact('data'));
        }

        $pageTitle = 'Dashboard';
        return view('merchant.dashboard', compact('data', 'pageTitle'));
    }

    public function requestService()
    {
        $authid = auth('merchant')->user()->id;
        $username = auth('merchant')->user()->username;

        $all_request = ServiceRequest::where('merchant_id', $authid)->limit(10)->orderByDesc('id')->get();

        return view('merchant.mfs_request', compact('all_request'));
    }

    public function allRequestService()
    {
        $authid = auth('merchant')->user()->id;
        $username = auth('merchant')->user()->username;

        $all_request = ServiceRequest::where('merchant_id', $authid)->paginate('15');

        return view('merchant.mfs_request-all', compact('all_request'));
    }

    public function requestAction(Request $request)
    {
        $request->validate([
            'amount' => 'required|min:1',
            'number' => 'required',
            'type' => 'required',
            'pin_code' => 'required',
            'mfs' => 'required',
        ]);

        $authid = auth('merchant')->user()->id;
        $old_balance = auth('merchant')->user()->balance;
        $pincode = auth('merchant')->user()->pincode;

        $amount = $request->amount;
        $pin_code = $request->pin_code;

        $new_balance = $old_balance - $request->amount;

        $trx = rand(11111111, 99999999);

        DB::beginTransaction();

        if ($pincode != $pin_code) {
            Session::flash('alert', 'Wrong PIN');
            return redirect()->back()->with('alert', 'Wrong PIN');
        }

        if ($old_balance >= $request->amount) {
        } else {
            Session::flash('alert', 'insufficient Balance');
            return redirect()->back()->with('alert', 'insufficient Balance');
        }

        $updatebal = Merchant::where('id', $authid)->update(['balance' => $new_balance]);

        $send = ServiceRequest::create([
            'merchant_id' => $authid,
            'old_balance' => $old_balance,
            'new_balance' => $new_balance,
            'trxid' => $trx,
            'number' => $request->number,
            'type' => $request->type,
            'mfs' => $request->mfs,
            'amount' => $request->amount,
            'sim_balance' => 0,
        ]);

        $trx = rand(11111111, 99999999);

        $trxcrt = Transaction::create([
            'user_id' => $authid,
            'amount' => $amount,
            'charge' => 0,
            'old_balance' => $old_balance,
            'trx_type' => 'debit',
            'trx' => $trx,
            'details' => $request->mfs . ' Request',
            'user_type' => 'merchant',
            'wallet_type' => 'main',
        ]);

        if ($trxcrt && $updatebal && $send) {
            DB::commit();

            Session::flash('message', translate('Request Submitted Successfull'));
        } else {
            DB::rollback();
            Session::flash('alert', translate('not_work'));
        }

        return redirect()->back();
    }

    public function TransactionContentIndex(Request $request, $sim_number = null)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '50';

        $sender = $request->get('sender');
        $start_date = $request->get('from');
        $end_date = $request->get('to');

        $authid = auth('merchant')->user()->id;
        $username = auth('merchant')->user()->username;

        //  $qrdata = BalanceManager::where('merchent_id', $username)->where('status', [20,77])->orderBy($sort_by, $sort_type);
        //  $qrdata = BalanceManager::where('merchent_id',$username)->get();
        $qrdata = BalanceManager::where('merchent_id', $username)
            ->whereIn('status', [20, 77])
            ->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout'])
            ->orderBy($sort_by, $sort_type);

        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date('Y-m-d H:i', strtotime($start_date));
            $end_date = date('Y-m-d H:i', strtotime($end_date));
            $qrdata->whereBetween(DB::raw('date(idate)'), [$start_date, $end_date]);
        }

        if (!empty($request->type)) {
            if ($request->type == 'cashout') {
                $qrdata->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout']);
            }

            if ($request->type == 'cashin') {
                $qrdata->whereIn('type', ['ngcashin', 'bkcashin', 'rccashin']);
            }

            if ($request->type == 'b2b') {
                $qrdata->whereIn('type', ['bkB2B', 'ngB2B', 'rcB2B']);
            }
            if ($request->type == 'RC') {
                $qrdata->whereIn('type', ['bkRC', 'ngB2BRC', 'rcB2BRC']);
            }
        }

        if (!empty($sender)) {
            $qrdata->where('sender', $sender);
        }

        if (!empty($request->trxid)) {
            $qrdata->where('trxid', $request->trxid);
        }

        $data = $qrdata->paginate($rows);

        if ($request->ajax()) {
            return view('merchant.transaction-content', compact('data'));
        }

        return view('merchant.transaction', compact('data', 'sim'));
    }

    public function withdraw_list()
    {
        $authid = auth('merchant')->user()->id;

        $all_request = WalletTransaction::where('merchant_id', $authid)->where('type', 'withdraw')->limit(10)->orderByDesc('id')->get();

        return view('merchant.withdraw-list', compact('all_request'));
    }

    public function withdraw()
    {
        $authid = auth('merchant')->user()->id;

        if (auth('merchant')->user()->withdraw_status == 0) {
            return 'your withdraw permission is off. Please contact with admin ';
            // return response()->json([
            //     'status'=> false,
            //     'message'=> 'your withdraw permission is off. Please contact with admin '
            // ]);
        }

        $all_request = ServiceRequest::where('merchant_id', $authid)->orderByDesc('id')->get();

        $paymentMethods = DB::table('mfs_operators')->get();

        return view('merchant.withdraw', compact('all_request', 'paymentMethods'));
    }

    public function withdraw_save(Request $request)
    {
        $request->validate([
            'mfs_operator' => 'required|string|exists:mfs_operators,name',
            'amount' => 'required|numeric|min:1',
            'withdraw_number' => 'required|string',
        ]);

        $merchant = auth('merchant')->user();
        $merchantId = $merchant->id;
        $merchant_type = $merchant->merchant_type === 'sub_merchant' ? 'sub_merchant' : 'general';
        $adminMerchantId = $merchant_type === 'sub_merchant' ? $merchant->create_by : null;
        $actualMerchantId = $adminMerchantId ?? $merchantId;

        // return$actualMerchantId;

        $balance = $merchant_type === 'sub_merchant' ? $merchant->balance : $merchant->available_balance;

        if ($request->amount > $balance) {
            return redirect()->back()->with('alert', 'Insufficient Balance');
        }

        $agentId = getRandom($request->amount, $request->mfs_operator);
        $invoiceNumber = 'TRX-' . $actualMerchantId . '-' . now()->format('YmdHis') . '-' . rand(1000, 9999);

        DB::beginTransaction();

        try {
            $mfs = DB::table('mfs_operators')->where('name', $request->mfs_operator)->first();

            if (!$mfs) {
                return redirect()->back()->with('alert', 'Invalid MFS Operator');
            }

            if ($actualMerchantId) {
                $merchantRate = calculateAmountFromRate($request->mfs_operator, 'P2A', 'withdraw', $actualMerchantId, $request->amount);
            } else {
                $merchantRate = calculateAmountFromRate($request->mfs_operator, 'P2A', 'withdraw', $actualMerchantId, $request->amount);
            }

            $memberRate = calculateAmountFromRateForMember($request->mfs_operator, 'P2A', 'withdraw', $agentId, $request->amount);

            $service = new ServiceRequest();
            $service->trxid = $invoiceNumber;
            $service->merchant_id = $actualMerchantId;
            $service->sub_merchant = $merchant_type === 'sub_merchant' ? $merchantId : null;
            $service->mfs = $request->mfs_operator;
            $service->mfs_id = $mfs->id;
            $service->old_balance = $balance;
            $service->amount = $request->amount;
            $service->new_balance = $balance - $request->amount;
            $service->number = $request->withdraw_number;
            $service->type = 'personal';
            $service->status = $agentId ? 1 : 0;
            $service->agent_id = $agentId;
            $service->sim_balance = 0;
            $service->merchant_fee = $merchantRate['general']['fee_amount'];
            $service->merchant_commission = $merchantRate['general']['commission_amount'];
            $service->sub_merchant_fee = $merchantRate['sub_merchant']['fee_amount'];
            $service->sub_merchant_commission = $merchantRate['sub_merchant']['commission_amount'];
            $service->merchant_main_amount = $merchantRate['general']['net_amount'];
            $service->sub_merchant_main_amount = $merchantRate['sub_merchant']['net_amount'];

            if ($agentId) {
                $service->partner = $memberRate['member']['partner_id'];
                $service->partner_fee = $memberRate['member']['fee_amount'];
                $service->partner_commission = $memberRate['member']['commission_amount'];
                $service->user_fee = $memberRate['agent']['fee_amount'];
                $service->user_commission = $memberRate['agent']['commission_amount'];
                $service->partner_main_amount = $memberRate['member']['net_amount'];
                $service->user_main_amount = $memberRate['agent']['net_amount'];
            }

            $service->save();

            // if($merchant_type === 'sub_merchant'){
            //     merchantBalanceAction($merchantId, 'minus', $request->amount , false);
            //     merchantBalanceAction($actualMerchantId, 'minus', $request->amount , false);
            // }else{
            //     merchantBalanceAction($actualMerchantId, 'minus', $request->amount , true);
            // }

            DB::commit();

            return redirect()->route('merchant.service-request')->with('success', 'Withdrawal request submitted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
            return redirect()->back()->with('alert', 'Something went wrong. Please try again.');
        }
    }

    public function create_support_view()
    {
        return view('merchant.support.create_support_view');
    }

    public function support_submit(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'detail' => 'required',
        ]);

        $a = strtoupper(md5(uniqid(rand(), true)));

        $ticket = SupportTicket::create([
            'subject' => $request->subject,
            'ticket' => substr($a, 0, 8),
            'customer_id' => auth('merchant')->user()->id,
            'customer_type' => 0,
        ]);

        SupportTicketComment::create([
            'ticket_id' => $ticket->ticket,
            'type' => 1,
            'comment' => $request->detail,
        ]);

        Session::flash('message', 'Successfully Created Ticket');
        return redirect()->route('merchant.support_list_view');
    }

    public function support_list()
    {
        // dd(auth('merchant')->user()->id);

        $all_ticket = SupportTicket::where('customer_id', auth('merchant')->user()->id)
            ->where('customer_type', 0)
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('merchant.support.support_list', compact('all_ticket'));
    }

    public function ticketReply($ticket)
    {
        $ticket_object = SupportTicket::where('customer_id', auth('merchant')->user()->id)
            ->where('customer_type', 0)
            ->where('ticket', $ticket)
            ->first();
        $ticket_data = SupportTicketComment::where('ticket_id', $ticket)->get();

        if ($ticket_object == '') {
            return redirect()->route('pagenot.found');
        } else {
            return view('merchant.support.ticket_reply', compact('ticket_data', 'ticket_object'));
        }
    }

    public function ticketClose($ticket)
    {
        SupportTicket::where('ticket', $ticket)->update([
            'status' => 9,
        ]);

        Session::flash('message', 'Your Ticket is closed');
        return redirect()->route('merchant.support_list_view');
        //return redirect()->back()->with('message', 'Conversation closed, But you can start again');
    }
    public function ticketReplyStore(Request $request, $ticket)
    {
        $this->validate($request, [
            'comment' => 'required',
        ]);

        SupportTicketComment::create([
            'ticket_id' => $ticket,
            'type' => 1,
            'comment' => $request->comment,
        ]);

        SupportTicket::where('ticket', $ticket)->update([
            'status' => 3,
        ]);

        return redirect()->back()->with('message', 'Message Send Successful');
    }
}
