<?php

use Peixinchen\Message\MessageCoder;
use Peixinchen\Message\Message;

class MessageTest extends PHPUnit_Framework_TestCase
{
    protected $coder;

    protected $version = '1.0.0';
    protected $payload = [
        'id' => 10086,
        'text' => 'It is a secret message.',
    ];

    public function setUp()
    {
        $cipher = 'blowfish';
        $secretKey = 'random key';

        $this->coder = new MessageCoder($cipher, $secretKey);
    }

    public function testEncode()
    {
        $messageText = $this->coder->encode($this->version, $this->payload);

        $this->assertTrue(is_string($messageText));
        $this->assertFalse(strpos('It s a secret message.', $messageText));
        $this->assertFalse(strpos($this->version, $messageText));

        return $messageText;
    }

    /**
     * @depends testEncode
     */
    public function testDecode($messageText)
    {
        $message = $this->coder->decode($messageText);

        $this->assertEquals($this->version, $message->version());
        $this->assertEquals($this->payload, $message->payload());
    }
}
