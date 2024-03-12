<?php

namespace abstract;

use PHPUnit\Framework\TestCase;
use Surreal\abstracts\AbstractAuth;

class AbstractAuthTest extends TestCase
{
    public function testSetToken(): void
    {
        $mock = $this->getMockBuilder(AbstractAuth::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();


        $mock->method('setToken')
            ->with('test');

        $mock->method('getToken')
            ->willReturn('test');

        $this->assertEquals('test', $mock->getToken());
    }

    public function testSetScope(): void
    {
        $mock = $this->getMockBuilder(AbstractAuth::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();

        $mock->method('setScope')
            ->with('test');

        $mock->method('getScope')
            ->willReturn('test');

        $this->assertEquals(
            "test",
            $mock->getScope()
        );
    }

    public function testGetHeaders(): void
    {
        $mock = $this->getMockBuilder(AbstractAuth::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->getMock();

        $mock->method('setToken')
            ->with('sometoken');

        $mock->method('getToken')
            ->willReturn('sometoken');

        $mock->method('getHeaders')
            ->willReturn(
                ['Authorization: Bearer test'],
                ['Surreal-SC: test'],
                ['Authorization: Bearer test', 'Surreal-SC: test']
            );

        $mock->method('setScope')
            ->with('test');

        $mock->method('getScope')
            ->willReturn('test');

        $this->assertEquals(
            ['Authorization: Bearer test'],
            $mock->getHeaders()
        );

        $this->assertEquals(
            ['Surreal-SC: test'],
            $mock->getHeaders()
        );

        $this->assertEquals(
            ['Authorization: Bearer test', 'Surreal-SC: test'],
            $mock->getHeaders()
        );
    }
}