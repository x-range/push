<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QueueRequest;
use App\Models\Message;
use Illuminate\Http\JsonResponse;

class QueueController extends Controller
{
    public function store(QueueRequest $request)
    {
        $message = new Message($request->only([
            'title',
            'body',
            'image',
            'icon',
            'badge',
            'link'
        ]));
        $message->save();
        return response()->json([
            'status' => 'success'
        ], JsonResponse::HTTP_OK);
    }
}
