<?php
namespace App\Models;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Illuminate\Support\Facades\Log;


class Chat implements MessageComponentInterface {

    protected $map;

    public function __construct() {
        $this->map = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "\n\nConnection {$conn->resourceId} has connected\n\n";
    }

    private function removeConnection($user_id, $conn) {
        if ($this->isClientRegistered($user_id)) {
            $this->map[$user_id] = array_filter($this->map[$user_id],
                function ($element) use ($conn) {
                    return $element->resourceId !== $conn->resourceId;
                });
        }
    }

    private function addConnection($user_id, $conn) {
        if (!$this->isClientRegistered($user_id)) {
            $this->map[$user_id] = array();
        }
        $this->map[$user_id][] = $conn;
    }

    private function isClientRegistered($user_id) {
        return array_key_exists($user_id, $this->map);
    }

    public function onMessage(ConnectionInterface $conn, $msg) {

        $message = json_decode($msg);

        echo "Incoming Message Object: {$msg}\n";

        if (isset($message->connect)) {
            $this->addConnection($message->userId, $conn);
            foreach ($this->map as $user_id => $connections) {
                $count = count($connections);
                echo "User ID: {$user_id} Connections: {$count}\n";
            }
        }
        else {

            echo "From ID: {$message->from_id} Resource ID: {$conn->resourceId}\n";

            $new_message = Message::create([
                'customer_id' => $message->direction == 0
                    ? $message->from_id
                    : $message->to_id,
                'breeder_id' => $message->direction == 1
                    ? $message->from_id
                    : $message->to_id,
                'message' => $message->message,
                'direction' => $message->direction,
            ]);

            $new_message->save();

            if ($this->isClientRegistered($message->to_id)) {

                $message->id = $new_message->id;
                $message->createdAt = $new_message->created_at
                    ->toDateTimeString();

                $message_string = json_encode($message);
                echo "Outgoing Message Object: {$message_string}\n\n";

                foreach ($this->map[$message->to_id] as $connection) {
                    $connection->send($message_string);
                }

                foreach ($this->map[$message->from_id] as $connection) {
                    if ($conn->resourceId !== $connection->resourceId) {
                        $connection->send($message_string);
                    }
                }

            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        foreach ($this->map as $user_id => $connections) {
            $this->removeConnection($user_id, $conn);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n\n";
        $conn->close();
    }

}
