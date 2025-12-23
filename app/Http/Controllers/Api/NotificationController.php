<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as MessagingNotification;
use Kreait\Firebase\Contract\Messaging;

class NotificationController extends Controller
{

    public $messaging;
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function index()
    {
        return view('layouts.notification');
    }

    public function send(Request $request)
    {

        $topic = 'all_targets';

        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification(MessagingNotification::create($request->title, $request->body));

        $output = $this->messaging->send($message);

        $string='';
        foreach ($output as $value){
           $string .=  $value.',';
        }

        echo $string;

        return $string;
    }
}
