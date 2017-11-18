<?php

namespace BitbucketWebhooks\Tests;

/**
 * Class Base
 * @property \PHPUnit_Framework_MockObject_MockObject $loger
 */
class Base extends \PHPUnit\Framework\TestCase
{
    protected $loger;

    public function setUp()
    {
        $this->loger = $this
            ->getMockBuilder(\Monolog\Logger::class)
            ->disableOriginalConstructor()
            ->setMethods(['critical', 'error', 'warning', 'info'])
            ->getMock();
    }

}
