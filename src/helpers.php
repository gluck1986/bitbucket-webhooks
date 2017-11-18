<?php

namespace BitbucketWebhooks\helpers;

use Psr\Log\LoggerInterface;

function checkIpInRange($ip, $cidrRanges): bool
{
    // Check if given IP is inside a IP range with CIDR format
    $ip = ip2long($ip);
    if (!is_array($cidrRanges)) {
        $cidrRanges = array($cidrRanges);
    }

    foreach ($cidrRanges as $cidrRange) {
        list($subnet, $mask) = explode('/', $cidrRange);
        if (($ip & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
            return true;
        }
    }

    return false;
}

function getProtocol()
{
    return isset($_SERVER['SERVER_PROTOCOL'])
        ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
}

function getIp()
{
    return isset($_SERVER['HTTP_X_REAL_IP'])
        ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
}

function getBody()
{
    return json_decode(file_get_contents('php://input'));
}

function getBranch($body, LoggerInterface $logger)
{
    try {
        $branch = $body->pullrequest->destination->branch->name;
    } catch (\Exception $exception) {
        $logger->error(
            $exception->getMessage()
        );
        $branch = '';
    }

    return trim(mb_convert_case($branch, MB_CASE_LOWER));
}
