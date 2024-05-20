<?php
/**
 * Created by PhpStorm.
 * User: Jream
 * Date: 5/12/2020
 * Time: 11:33 PM
 */
namespace App\Modules\Backend\Controllers;

use App\Services\Chat;
use App\Services\CommentService;
// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use LRedis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Events\CustomMessageEvent;
use App\Models\User;
use App\Jobs\PushNotificationJob;

class ChatController extends Controller{

    private $redis;

    public function __construct()
    {
        // $this->middleware('guest');
        $this->redis = LRedis::connection();
    }

    public function newChatView()
    {
        return $this->getView($this->getFolderView('chat.chat'), [
            // 'serviceData' => $postData,
            'title' => __('Add new car'),
            'new' => true
        ]);
    }

    public function socket()
    {
        return $this->getView($this->getFolderView('chat.socket'),[
            'title' => __('Add new car'),
            'new' => true
        ]);
    }

    public function writemessage()
    {
        return $this->getView($this->getFolderView('chat.writemessage'),[
            'title' => __('Add new car'),
            'new' => true
        ]);
    }

    public function sendMessage()
    {
        $message = request()->input('message');
        Log::info("Received message: $message");
        $redis = LRedis::connection();
        LRedis::publish('message', json_encode(['type' => 'custom', 'message' => $message]));
        // broadcast(new CustomMessageEvent($message))->toOthers();

        return $this->getView($this->getFolderView('chat.writemessage'), [
            'title' => __('Add new car'),
            'new' => true,
        ]);
    }

    public function indexSendNotification()
    {
        return $this->getView($this->getFolderView('fcm.sendNoti'),[
            'title' => __('Add new car'),
            'new' => true
        ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function saveToken(Request $request)
    {
        auth()->user()->update(['fcm_token' => $request->token]);
        return response()->json(['token saved successfully.']);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('fcm_token')->pluck('fcm_token')->all();
        $SERVER_API_KEY = env('FCM_SERVER_KEY');
        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
                "content_available" => true,
                'icon'  => "https://image.flaticon.com/icons/png/512/270/270014.png",
                'image' => "https://picsum.photos/536/354",
                "priority" => "high",
                'receiver' => 'erw',
                'sound' => 'mySound'
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
        curl_close( $ch );
    }

    public function sendNotificationJob(Request $request) {
        // $deviceTokens = User::whereDay('birthday', now()->format('d'))
        //     ->whereMonth('birthday', now()->format('m'))
        //     ->pluck('device_token')
        //     ->toArray();
        $deviceTokens =  User::whereNotNull('fcm_token')->pluck('fcm_token')->all();
        PushNotificationJob::dispatch('sendBatchNotification', [
            $deviceTokens,
            [
                'topicName' => 'birthday',
                'title' => 'Chúc mừnng sinh nhật',
                'body' => 'Chúc bạn sinh nhật vui vẻ',
                'image' => 'https://picsum.photos/536/354',
            ],
        ]);

        return $this->getView($this->getFolderView('fcm.sendNoti'),[
            'title' => __('Add new car'),
            'new' => true
        ]);

    }
    
}