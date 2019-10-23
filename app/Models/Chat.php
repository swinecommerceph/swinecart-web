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
            $new_message = Message::create([
                'customer_id' => $msg->direction == 0 ? $msg->from : $msg->to,
                'breeder_id' => $msg->direction == 0 ? $msg->to : $msg->from,
                'message' => $msg->message,
                'direction' => $msg->direction,
            ]);

            if(array_key_exists($msg->to, $this->maps)){

                $msg->id = $new_message->id;
                $msg->from_id = $msg->from;
                $msg->to_id = $msg->to;
                $msg->read_at = $new_message->read_at;
                $msg->created_at = $new_message->created_at->toDateTimeString();
                $msg->from = User::where('id', $msg->from)->first()->name;

                echo sprintf('Sending %s to %d' . "\n"  , json_encode($msg), $msg->to);

                $this->maps[$msg->to]->send(json_encode($msg));
            }
            

            // foreach ($this->clients as $client) {
            //     if($client != $from){
            //         $client->send(json_encode($msg));
            //     }

            // }

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
