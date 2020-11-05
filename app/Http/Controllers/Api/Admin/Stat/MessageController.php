<?php


namespace App\Http\Controllers\Api\Admin\Stat;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\MessageResource;
use App\Models\Message;

class MessageController extends Controller
{
    public function index(){
        $messages = Message::query()
            ->withCount('notifications')
            ->withCount('clicks')
            ->withCount('closes')
            ->get();
        MessageResource::withoutWrapping();
        return MessageResource::collection($messages);
    }

    public function show(Message $message){
        $message = Message::where('id', $message->id)
            ->withCount('notifications')
            ->withCount('clicks')
            ->withCount('closes')
            ->first();
        MessageResource::withoutWrapping();
        return new MessageResource($message);
    }
}
