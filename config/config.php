<?php

use Phalcon\Config;
use Phalcon\Logger;

$database = include BASE_DIR . '/config/database.php';
$session = include BASE_DIR . '/config/session.php';

return new Config([
    'application' => [
        'cacheDir'        => STORAGE_DIR . '/framework/cache/',
        'baseUri'         => getenv('BASE_URL', 'http://localhost.dev'),
        'publicUrl'       => getenv('PUBLIC_URL', 'http://static-localhost.dev/'),
        'cryptSalt'       => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D'
    ],
    'database' => $database,
    'mail' => [
        'fromName'  => 'Vokuro',
        'fromEmail' => 'phosphorum@phalconphp.com',
        'smtp'      => [
            'server'   => 'smtp.gmail.com',
            'port'     => 587,
            'security' => 'tls',
            'username' => '',
            'password' => ''
        ]
    ],
    'amazon' => [
        'AWSAccessKeyId' => '',
        'AWSSecretKey' => ''
    ],
    'logger' => [
        'path'     => STORAGE_DIR . '/logs/',
        'format'   => '%date% [%type%] %message%',
        'date'     => 'D j H:i:s',
        'logLevel' => Logger::DEBUG,
        'filename' => 'application.log',
    ],
    'session' => $session,
    'path' => [
        'client_key' => '/data/clientkey/',
        'server_key' => '/data/serverkey/',
     ],

]);
