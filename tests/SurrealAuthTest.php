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
            authorization: SurrealAuthorization::create()
                ->setAuthNamespace("test"),
        );

        $result = $db->signup([
            "user" => "beau@test.nl",
            "pass" => "krillissue"
        ]);

        var_dump($result);

        $this->assertEquals(200, $db->status(), "Status check failed");
    }
}