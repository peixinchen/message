<?php

namespace Peixinchen\Message;

use Peixinchen\Message\Exceptions\NotUTF8Exception;
use Peixinchen\Message\Exceptions\BadMessageException;
use Peixinchen\Message\Exceptions\MessageValidateException;

class MessageCoder
{
    protected $cipher;

    protected $secretKey;

    public function __construct($cipher, $secretKey)
    {
        $this->cipher = $cipher;

        $this->secretKey = $secretKey;
    }

    public function encode($version, $payload)
    {
        $message = [
            'version' => $version,
            'payload' => $payload,
        ];

        $messagePlaintext = json_encode($message);

        if ($messagePlaintext === false) {
            throw new NotUTF8Exception;
        }

        $messageCiphertext = $this->encrypt($messagePlaintext);

        return base64_encode($messageCiphertext);
    }

    public function decode($messageText)
    {
        $messageCiphertext = base64_decode($messageText);

        $messagePlaintext = $this->decrypt($messageCiphertext);

        $message = json_decode($messagePlaintext, true);
        if ($message === null) {
            throw new BadMessageException($messagePlaintext);
        }

        $this->validate($message);

        return new Message($message['version'], $message['payload']);
    }

    protected function encrypt($plaintext)
    {
        return mcrypt_encrypt($this->cipher, $this->secretKey, $plaintext, 'ecb');
    }

    protected function decrypt($ciphertext)
    {
        return trim(mcrypt_decrypt($this->cipher, $this->secretKey, $ciphertext, 'ecb'));
    }

    protected function validate(array $message)
    {
        if (!array_key_exists('version', $message)) {
            throw new MessageValdateException('Field version is missing.');
        }

        if (!array_key_exists('payload', $message)) {
            throw new MessageValdateException('Field payload is missing.');
        }
    }
}
