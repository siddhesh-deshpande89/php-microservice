<?php
require_once __DIR__.'/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitpoc', 5672, 'siddhesh', 'demo1234');
$channel = $connection->channel();
$channel->queue_declare('product_queue', false, false, false, false);

$msg = new AMQPMessage('Execute Transaction');
$channel->basic_publish($msg, '', 'product_queue');
echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();
?>