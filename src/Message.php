<?php

namespace Peixinchen\Message;

class Message
{
    protected $version;

    protected $payload;

    public function __construct($version, $payload)
    {
        $this->version = $version;

        $this->payload = $payload;
    }

    public function version()
    {
        return $this->version;
    }

    public function payload()
    {
        return $this->payload;
    }
}
