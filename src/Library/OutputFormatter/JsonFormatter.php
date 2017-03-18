<?php
namespace Library\OutputFormatter;
use Library\OutputFormatterInterface;
class JsonFormatter implements OutputFormatterInterface
{
    public function output($status, $message, $format='Content-Type: application/json')
    {
        header($format);
        return json_encode(compact('status', 'message'));
    }
}