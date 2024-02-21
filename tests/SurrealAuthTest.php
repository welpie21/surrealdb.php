<?php

use PHPUnit\Framework\TestCase;
use Surreal\Surreal;
use Surreal\SurrealAuthorization;

class SurrealAuthTest extends TestCase
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    /**
     * @throws Exception
     */
    public function testSignup(): void
    {
        $db = new Surreal(
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test",
            authorization: SurrealAuthorization::create()
                ->setAuthNamespace("test")
                ->setAuthDatabase("test")
                ->setScope("account"),
        );

        $token = $db->signup([
            "email" => "beau.doe",
            "pass" => "123456"
        ]);

        $this->assertIsString($token, "Token is not a string");

        $db->close();
    }

    /**
     * @throws Exception
     */
    public function testSignin(): void
    {
        $db = new Surreal(
            host: "http://127.0.0.1:8000",
            namespace: "test",
            database: "test",
            authorization: SurrealAuthorization::create()
                ->setAuthNamespace("test")
                ->setAuthDatabase("test")
                ->setScope("account"),
        );

        $token = $db->signin([
            "email" => "beau.doe",
            "pass" => "123456"
        ]);

        $this->assertIsString($token, "Token is not a string");
        $db->close();
    }
}