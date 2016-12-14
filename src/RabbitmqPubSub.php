<?php
namespace bgcolor\rabbitmq;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Created by PhpStorm.
 * User: sunshengbo
 * Date: 2016/10/25
 * Time: 15:14
 */
class RabbitmqPubSub extends Rabbitmq {

    /**
     * @param $channel 发布消息的频道
     * @param $msg 消息数据
     */
    public static function publish($channel, $msg) {
        $ch = parent::getInstance();
        $ch->basic_publish(new AMQPMessage($msg), 'amq.topic', $channel);
    }

    /**
     * @param $channels 订阅的频道
     * @param $callback 收到通知后的回调
     * @return bool
     */
    public static function subscribe($channels, $callback){
        if (!is_array($channels)) {
            return false;
        }

        $ch = self::getInstance();
        list($queue_name, ,) = $ch->queue_declare("", false, false, true, false);

        foreach($channels as $channel) {
            $ch->queue_bind($queue_name, 'amq.topic', $channel);
        }

        $ch->basic_consume($queue_name, '', false, true, false, false, $callback);

        while(true) {
            $ch->wait();
        }
    }
}