<?php

namespace abstract;

use Exception;
use PHPUnit\Framework\TestCase;
use Surreal\abstracts\AbstractProtocol;
use Surreal\SurrealHTTP;
use Surreal\SurrealWebsocket;

class AbstractProtocolTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testTimeout()
    {
        $mock = new class extends AbstractProtocol {

            private int $timeout = 0;

            public function close(): void
            {
                echo "closed";
            }

            public function setTimeout(int $seconds): void
            {
                $this->timeout = $seconds;
            }

            public function getTimeout(): int
            {
                return $this->timeout;
            }
        };

        $http = new SurrealHTTP(
            host: "http://localhost:8080",
            target: ["namespace" => "test", "database" => "test"]
        );

        $ws = new SurrealWebsocket(
            host: "ws://localhost:8080",
            target: ["namespace" => "test", "database" => "test"]
        );

        $this->assertEquals(5, $http->getTimeout());
        $this->assertEquals(5, $ws->getTimeout());
        $this->assertEquals(0, $mock->getTimeout());

        $http->setTimeout(10);
        $ws->setTimeout(10);
        $mock->setTimeout(10);

        $this->assertEquals(10, $http->getTimeout());
        $this->assertEquals(10, $ws->getTimeout());
        $this->assertEquals(10, $mock->getTimeout());

        $http->close();
        $ws->close();
    }
}