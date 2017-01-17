# message

编解码用于MessageQueue的消息体，同时进行加密

## Installation 安装

1. 使用 composer

```shell
# composer require peixinchen/message
```

## Tutorial 使用指导

1. 初始化编码器

```php
<?php

use Peixinchen\Message\MessageCoder;

// 加/解密算法
$cipher = 'blowfish';

// 密钥
$secretKey = 'some random key';

$coder = new MessageCoder($cipher, $secretKey);
```

2. 编码

```php
// 消息体版本，用于格式升级时做不同处理
$version = '1.0.0';

// 实际要传递的消息内容
$payload = [
  'id' => 1,
  'text' => 'Some important message!',
];

$messageText = $coder->encode($version, $payload);
```

3. 解码
```php
// 返回一个强约束的\Peixinchen\Message对象实例
$message = $coder->decode($messageText);

// 1.0.0
var_dump($message->version());

// [
//   'id' => 1,
//   'text' => 'Some important message!',
// ]
var_dump($message->payload());
```
