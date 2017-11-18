<?php

namespace BitbucketWebhooks\validators;

use Psr\Log\LoggerInterface;

function validateConfig(array $config, LoggerInterface $loger): bool
{
    $result = true;
    if (!array_key_exists('ips', $config)) {
        $loger->critical('config.php need key "ips"');
        $result = false;
    }
    if (!array_key_exists('branches', $config)) {
        $loger->critical('config.php need key "branches"');
        $result = false;
    }
    if (!is_array($config['branches'])) {
        $loger->critical('config.php "branches" must be an array');
        $result = false;
    } else {
        $result = array_reduce(
            $config['branches'],
            function ($result, $val) use ($loger) {
                if (!array_key_exists('branch', $val) || !$val['branch']) {
                    $result = false;
                    $loger->critical(
                        'config.php "branches" need key "branch", '
                        .'containing branch name'
                    );
                }
                if (!array_key_exists('path', $val) || !$val['path']) {
                    $result = false;
                    $loger->critical(
                        'config.php "branches" need key "path", '
                        .'containing path to project root'
                    );
                }
                if (!array_key_exists('actions', $val)
                    || !$val['actions']
                    || !is_array($val['actions'])
                ) {
                    $result = false;
                    $loger->critical(
                        'config.php "branches" need key "actions", '
                        .'containing console commands'
                    );
                }

                return $result;

            },
            $result
        );
    }

    return $result;
}