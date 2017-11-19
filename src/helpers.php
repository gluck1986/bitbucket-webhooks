<?php

namespace BitbucketWebhooks\helpers;

use Psr\Log\LoggerInterface;

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

function doJobs(
    array $jobs,
    string $dir,
    $branch,
    LoggerInterface $loger,
    $worker = null
) {
    if (!$worker) {
        $worker = function (string $job) {
            return doJob($job);
        };
    }
    chdir($dir);
    foreach ($jobs as $job) {
        list($output, $err) = $worker($job);
        if ($err) {
            $loger->warning(buildJobLogMessage($job, $dir, $output, $branch, $err));

            return;
        } else {
            $loger->info(buildJobLogMessage($job, $dir, $output, $branch, $err));
        }
    }
}

function doJob(string $job)
{
    $output = array();
    $err = false;
    try {
        exec($job, $output);
    } catch (\Exception $exception) {
        $err = $exception->getMessage();
    }

    return [implode(', ', $output), $err];
}

function buildJobLogMessage($job, $dir, $output, $branch, $err = '')
{
    $templateOk = '*success* branch: %s; dir: %s; job: %s; result: %s;';
    $templateErr = '*Error* branch: %s; dir: %s;'
        . ' job: %s; result: %s; err: %s';
    if ($err) {
        return sprintf(
            $templateErr,
            $branch,
            $dir,
            $job,
            $output,
            $err
        );
    } else {
        return sprintf(
            $templateOk,
            $branch,
            $dir,
            $job,
            $output
        );
    }
}

function getBranchConfig($branch, $branches, LoggerInterface $loger)
{
    $searchResult = array_filter(
        $branches,
        function ($val) use ($branch) {
            return trim(mb_convert_case($val['branch'], MB_CASE_LOWER))
                === trim(mb_convert_case($branch, MB_CASE_LOWER));
        }
    );
    if (count($searchResult) < 1) {
        return null;
    } elseif (count($searchResult) > 1) {
        $loger->warning(
            'For branch ' . $branch
            . ' ' . count($searchResult) . ' options!'
        );

        return null;
    } else {
        return array_shift($searchResult);
    }
}
