<?php 
namespace App\Models;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;    
use Illuminate\Support\Facades\Log;                



class Chat implements MessageComponentInterface {

    protected $clients, $maps;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->maps = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        

        $msg = json_decode($msg);
        if($msg->to == null && $msg->direction == null && $msg->message == 'Connection established.' ){
            $this->maps[$msg->from] = $from;
            return;
        }
        else{
            // TODO: catch when the media_url is null or not
            
            // if message is text
            if ($msg->media_url) {
              // Log::info('message is media');

            }

            // if message is media
            else {
              // Log::info('message is text');
            }

            if($msg->direction == 0){
              
                Message::create([
                    'customer_id' => $msg->from,
                    'breeder_id' => $msg->to,
                    'message' => $msg->message,
                    /* 'media_url' => $msg->media_url,
                    'media_type' => $msg->media_type, */
                    'direction' => 0,
                ]);
            }else{
                 Message::create([
                    'customer_id' => $msg->to,
                    'breeder_id' => $msg->from,
                    'message' => $msg->message,
                    /* 'media_url' => $msg->media_url,
                    'media_type' => $msg->media_type, */
                    'direction' => 1,
                ]);
            }
            
            if(array_key_exists($msg->to, $this->maps)){
                //$msg->created_at = $test->created_at;
                $msg->from = User::where('id', $msg->from)->first()->name;
                $this->maps[$msg->to]->send(json_encode($msg));
            }
            


            /*
            foreach ($this->clients as $client) {
                if($client != $from){
                    $client->send(json_encode($msg));
                }

            }
            */

        }


    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        foreach ($maps as $key => $map) {
            if($map == $conn){
                unset($maps[$key]);
                break;
            }
        }

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}
