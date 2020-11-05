<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:send {message_id=0} {site_id=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $webPush;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Subscriber::whereNull('unsubscribed_at');
        $query->where(function ($query){
            $query->whereNotNull('delivered_at');
            $query->orWhereNull('notification_at');
            $date = Carbon::now()->subHour(2);
            $query->orWhere('notification_at', '<=', $date);
        });

        if($this->argument('site_id')){
            $query->where('site_id', $this->argument('site_id'));
        }
        $subscribers = $query->get();
        if($this->argument('message_id')){
            $message = Message::where('id', $this->argument('message_id'))->first();
        }else{
            $message = Message::whereNull('notification_at')->first();
        }
        if(!$message){
            $this->warn('No message to send');
            die();
        }
        $message->notification_at = Carbon::now();
        $message->save();
        $this->info("[i] Send '$message->title' msg");
        $this->info("[i] Send to ".count($subscribers)." subscribers");
        $i = 0;
        $this->initPush();
        foreach ($subscribers as $subscriber){
            $i++;
            if($i % 20 === 0){
                $this->checkPush();
                $this->initPush();
            }
            $this->addToPush($subscriber, $message);
        }
        $this->checkPush();
    }

    protected function initPush()
    {
        $public_key = config('services.push.public_key');
        $private_key = config('services.push.private_key');
        $options = [
            'TTL' => 120,
            'batchSize' => 30,
        ];
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => 'admin@example.com',
                'publicKey' => $public_key,
                'privateKey' => $private_key
            ],
            $options
        ]);
    }

    protected function addToPush($subscriber, $message)
    {
        $subscriber->message_id = $message->id;
        $subscriber->notification_at = date('Y-m-d H:i:s');
        $subscriber->delivered_at = null;
        $subscriber->save();
        $this->webPush->sendNotification(Subscription::create([
            'endpoint' => $subscriber->endpoint,
            'keys' => [
                'p256dh' => $subscriber->p256dh,
                'auth' => $subscriber->auth
            ]]),
            json_encode([
                'title' => $message->title,
                'body' => $message->body ?? '',
                'image' => $message->image ?? '',
                'icon' => $message->icon,
                'badge' => $message->badge,
                'data' => [
                    'link' => $message->link . '?utm_source=push&utm_medium=cpc&utm_campaign='.$subscriber->site_id.'&utm_term='.$subscriber->id,
                    'subscriber_id' => $subscriber->id,
                    'message_id' => $message->id
                ],
                'tag' => $message->id
            ]));
    }

    protected function checkPush()
    {
        foreach ($this->webPush->flush() as $report) {

            $endpoint = $report->getRequest()->getUri()->__toString();
            if ($report->isSuccess()) {
                $this->info("[i] Success {$endpoint}.");
            } else {
                $subscriber = Subscriber::where('endpoint', $endpoint)->first();
                $subscriber->unsubscribed_at = date('Y-m-d H:i:s');
                $subscriber->save();
                $this->warn("[w] Fail {$endpoint}: {$report->getReason()}");
            }
        }
    }
}
