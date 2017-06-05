<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendToPubSubServer implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $email;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $email, $data = [])
    {
        $this->type = $type;
        $this->email = $email;
        $this->data = $data;
    }

    /**
     * Execute the job. Send passed data to the pubsub server
     * for the client subscriber to consume
     *
     * @return void
     */
    public function handle()
    {
        $zmqHost = env('ZMQ_HOST', 'localhost');
        $zmqPort = env('ZMQ_PORT', '5555');
        $data = $this->data;
        $data['type'] = $this->type;
        $data['topic'] = crypt($this->email,md5($this->email));

        // This is our new stuff
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'Product Status Pusher');
        $socket->connect("tcp://" . $zmqHost . ":" . $zmqPort);

        $socket->send(json_encode($data));
    }
}
