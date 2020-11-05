<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StatisticRequest;
use App\Models\Click;
use App\Models\Close;
use App\Models\Notification;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;

class StatisticController extends Controller
{
    public function notification(StatisticRequest $request)
    {
        $notification = new Notification([
            'message_id' => $request->message_id,
            'subscriber_id' => $request->subscriber_id
        ]);
        $notification->save();
        $subscriber = Subscriber::where('id', $request->subscriber_id)->first();
        $subscriber->delivered_at = date('Y-m-d H:i:s');
        $subscriber->save();
        return response()->json([
            'status' => 'success',
        ], JsonResponse::HTTP_OK);
    }

    public function click(StatisticRequest $request)
    {
        $click = new Click([
            'message_id' => $request->message_id,
            'subscriber_id' => $request->subscriber_id
        ]);
        $click->save();
        return response()->json([
            'status' => 'success',
        ], JsonResponse::HTTP_OK);
    }

    public function close(StatisticRequest $request)
    {
        $close = new Close([
            'message_id' => $request->message_id,
            'subscriber_id' => $request->subscriber_id
        ]);
        $close->save();
        return response()->json([
            'status' => 'success',
        ], JsonResponse::HTTP_OK);
    }
}
