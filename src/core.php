<?php

namespace BitbucketWebhooks\core;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use function BitbucketWebhooks\helpers\doJobs;
use function BitbucketWebhooks\helpers\getBody;
use function BitbucketWebhooks\helpers\getBranch;
use function BitbucketWebhooks\helpers\getBranchConfig;
use function BitbucketWebhooks\helpers\getIp;
use function BitbucketWebhooks\helpers\getProtocol;
use function BitbucketWebhooks\validators\validateConfig;
use function BitbucketWebhooks\validators\validateIp;

function run(array $config)
{
    $logger = getLogger();
    list($ips, $branches) = getConfig($logger, $config);
    $protocol = getProtocol();
    $ip = getIp();

    if (!validateIp($ip, $ips, $logger)) {
        responseBad($protocol, 'invalid ip address.');
    }

    $body = getBody();
    $branch = '';
    if (!$body || !($branch = getBranch($body, $logger))) {
        responseBad($protocol, 'missing payload');
    }

    $branchConfig = getBranchConfig($branch, $branches, $logger);
    if (!$branchConfig) {
        responseOk($protocol, 'not required branch');
    }

    doJobs(
        $branchConfig['actions'],
        $branchConfig['path'],
        $branchConfig['branch'],
        $logger
    );
}

function getLogger()
{
    $logPath = dirname(dirname(__FILE__))
        . DIRECTORY_SEPARATOR . 'logs'
        . DIRECTORY_SEPARATOR . 'logs.log';
    $log = new Logger('main');
    $log->pushHandler(new StreamHandler($logPath, Logger::INFO));

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
