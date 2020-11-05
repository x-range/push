<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubscriptionRequest;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function store(SubscriptionRequest $request)
    {
        $subscriber = Subscriber::firstOrCreate([
            'site_id' => $request->site_id,
            'endpoint' => $request->endpoint,
            'p256dh' => $request->p256dh,
            'auth' => $request->auth,
        ], [
            'referer' => $request->referer,
            'timezone' => $request->timezone
        ]);
        $subscriber->timezone = $request->timezone;
        $subscriber->save();
        return response()->json([
            'status' => 'success',
        ], JsonResponse::HTTP_OK);
    }
}
