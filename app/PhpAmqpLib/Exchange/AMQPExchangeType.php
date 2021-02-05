<?php
namespace App\PhpAmqpLib\Exchange;

final class AMQPExchangeType
{
    const DIRECT = 'direct';
    const FANOUT = 'fanout';
    const TOPIC = 'topic';
    const HEADERS = 'headers';
}
