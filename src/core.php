<?php

namespace BitbucketWebhooks\core;

use function BitbucketWebhooks\validators\validateConfig;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

function run(array $config)
{
    $loger = getLogger();
    $ips = $config['ips'];
    $branches = $config['branches'];
}

function getLogger() {
    $logPath = dirname(__FILE__)
        . DIRECTORY_SEPARATOR . 'logs'
        . DIRECTORY_SEPARATOR . 'logs.log';
    $log = new Logger('main');
    $log->pushHandler(new StreamHandler($logPath, Logger::WARNING));

    return $log;
}

function getSettings(LoggerInterface $loger, array $config)
{
    if (!validateConfig($config, $loger)) {
        throw new \Exception('err config');
    }



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