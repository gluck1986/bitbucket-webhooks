<?php

namespace BitbucketWebhooks\core;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use function BitbucketWebhooks\validators\validateConfig;

function run(array $config)
{
    $loger = getLogger();
    list($ips, $branches) = getConfig($loger, $config);
}

function getLogger()
{
    $logPath = dirname(__FILE__)
        . DIRECTORY_SEPARATOR . 'logs'
        . DIRECTORY_SEPARATOR . 'logs.log';
    $log = new Logger('main');
    $log->pushHandler(new StreamHandler($logPath, Logger::WARNING));

    return $log;
}

function getConfig(LoggerInterface $loger, array $config)
{
    if (!validateConfig($config, $loger)) {
        throw new \Exception('err config');
    }

    return [$config['ips'], $config['branches']];
}

function responseBad($protocol, $message = '')
{
    header($protocol . ' 400 Bad Request');
    die($message);
}

function responseOk($protocol, $message = '')
{
    header($protocol . ' 200 OK');
    die($message);
}
