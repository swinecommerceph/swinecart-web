<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use React\Socket\Server;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;
use App\Models\Pusher;

class WSPubSubServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubsub:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start publish-subscribe websocket server for Product status real-time changes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $loop   = Factory::create();
	    $pusher = new Pusher;
        $zmqHost = env('ZMQ_HOST', 'localhost');
        $zmqPort = env('ZMQ_PORT', '5555');
        $pubsubServerPort = env('PUBSUBSERVER_HOST', '8080');

	    // Listen for the web server to make a ZeroMQ push after an ajax request
	    $context = new Context($loop);
	    $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
	    $pull->bind("tcp://" . $zmqHost . ":" . $zmqPort);
	    $pull->on('message', array($pusher, 'onDatabaseChange'));

		$this->info("Starting publish-subscribe websocket server on port 8080");

	    // Set up our WebSocket server for clients wanting real-time updates
	    $webSock = new Server($loop);
	    $webSock->listen($pubsubServerPort, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
	    $webServer = new IoServer(
	        new HttpServer(
	            new WsServer(
	                new WampServer(
	                    $pusher
	                )
	            )
	        ),
	        $webSock
	    );

	    $loop->run();
    }
}
