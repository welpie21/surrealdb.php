<?php

namespace abstract;

use PHPUnit\Framework\TestCase;
use Surreal\abstracts\AbstractAuth;

class AbstractAuthTest extends TestCase
{
    public function testSetToken(): void
    {
        $mock = new class extends AbstractAuth {};
        $mock->setToken('test');

        $this->assertEquals('test', $mock->getToken());
    }

    public function testSetScope(): void
    {
        $mock = new class extends AbstractAuth {};
        $mock->setScope('test');

        $this->assertEquals('test', $mock->getScope());
    }

    public function testGetHeaders(): void
    {
        $mock = $this->getMockBuilder(AbstractAuth::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();

        $mock->setToken('test');
        $mock->setScope('test');

        $this->assertEquals('test', $mock->getToken());
        $this->assertEquals('test', $mock->getScope());

        $this->assertEquals(['Authorization: Bearer test', 'Surreal-SC: test'], $mock->getHeaders());
    }
}