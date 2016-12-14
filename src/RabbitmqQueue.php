<?php
namespace bgcolor\rabbitmq;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitmqQueue extends Rabbitmq {

    public static function push($queue, $msg) {
        $ch = parent::getInstance();
        $ch->queue_declare($queue, false, true, false, false);
        $ch->basic_publish(new AMQPMessage($msg,
            array('delivery_mode' => 2) # make message persistent
        ), '', $queue);
    }

    public static function pop($queue, $callback) {
        $ch = self::getInstance();
        $ch->queue_declare($queue, false, true, false, false);
        $ch->basic_qos(null, 1, null);
        $ch->basic_consume($queue, '', false, false, false, false, $callback);
        while (true) {
            $ch->wait();
        }
    }
}