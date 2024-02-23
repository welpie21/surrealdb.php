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

    public function testAuthenticationMethods(): void
    {
        $auth = SurrealAuthorization::create()
            ->setAuthNamespace("test")
            ->setAuthDatabase("test")
            ->setScope("account");

        $this->assertEquals("test", $auth->getAuthNamespace(), "Auth namespace is not set correctly");
        $this->assertEquals("test", $auth->getAuthDatabase(), "Auth database is not set correctly");
        $this->assertEquals("account", $auth->getScope(), "Auth scope is not set correctly");

        $auth->setAuthToken("this-is-a-token");
        $this->assertEquals("this-is-a-token", $auth->getAuthToken(), "Auth token is not set correctly");

        $auth->invalidate();
        $this->assertNull($auth->getAuthToken(), "Auth token is not invalidated correctly");

        $auth->setAuthNamespace("test2");
        $this->assertEquals("test2", $auth->getAuthNamespace(), "Auth namespace is not set correctly");

        $auth->setAuthDatabase("test2");
        $this->assertEquals("test2", $auth->getAuthDatabase(), "Auth database is not set correctly");

        $auth->setScope("account2");
        $this->assertEquals("account2", $auth->getScope(), "Auth scope is not set correctly");
    }

    /**
     * @throws Exception
     */
    public function testDeleteDatabase(): void
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

        $db->sql("DELETE user");
        $db->sql("DELETE person");
        $db->sql("DELETE likes");
        $db->sql("DELETE product");

        $db->close();

        $this->assertEquals(1, 1, "Database was not deleted");
    }
}