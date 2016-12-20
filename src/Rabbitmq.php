<?php
namespace bgcolor\rabbitmq;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Rabbitmq {

    private static $instance = null;

    public static function getInstance() {
        if (self::$instance) {
            return self::$instance->channel();
        }

        $config = require_once __DIR__.'/config.php';
        self::$instance = (new AMQPStreamConnection($config['host'], $config['port'], $config['username'], $config['password']));

        return self::$instance->channel();
    }
}