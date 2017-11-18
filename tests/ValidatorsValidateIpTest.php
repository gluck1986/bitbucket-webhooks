<?php

namespace BitbucketWebhooks\Tests;


use function BitbucketWebhooks\validators\validateIp;

class ValidatorsValidateIpTest extends Base
{
    public function testIpValidator()
    {
        $ips = [
            /* bitbucket_IP_ranges */
            '131.103.20.160/27',
            '165.254.145.0/26',
            '104.192.143.0/24'
        ];

        $validIp1 = '165.254.145.10';
        $validIp2 = '104.192.143.8';
        $invalidIp = '104.192.144.8';

        $this->loger->expects($this->once())
            ->method('warning')
            ->with($this->anything(), $this->anything());
        $result = validateIp($invalidIp, $ips, $this->loger);
        $this->assertFalse($result);

        $this->loger->expects($this->never())
            ->method('warning')
            ->with($this->anything(), $this->anything());
        $result = validateIp($validIp1, $ips, $this->loger);
        $this->assertTrue($result);

        $this->loger->expects($this->never())
            ->method('warning')
            ->with($this->anything(), $this->anything());
        $result = validateIp($validIp2, $ips, $this->loger);
        $this->assertTrue($result);
    }
}
