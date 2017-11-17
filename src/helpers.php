<?php

namespace BitbucketWebhooks\helpers;

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