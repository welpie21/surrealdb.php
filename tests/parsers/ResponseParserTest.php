<?php

namespace parsers;

use PHPUnit\Framework\TestCase;
use Surreal\classes\exceptions\SurrealException;
use Surreal\classes\ResponseParser;
use Surreal\classes\responses\AuthResponse;
use Surreal\classes\responses\ForbiddenResponse;
use Surreal\classes\responses\WebsocketResponse;

class ResponseParserTest extends TestCase
{
    public function testAuthResponse(): void
    {
        $data = [
            "code" => 200,
            "details" => "success",
            "token" => "sometoken"
        ];

        try {
            $response = ResponseParser::create($data);
            $this->assertInstanceOf(AuthResponse::class, $response);
        } catch (\Exception $exception) {
            // does nothing
        }
    }

    public function testErrorResponse(): void
    {
        $response = [
            "code" => 400,
            "details" => "some details",
            "description" => "some description",
            "information" => "some information"
        ];

        try {
            ResponseParser::create($response);
        } catch (SurrealException $e) {
            $this->assertInstanceOf(SurrealException::class, $e);
            $this->assertEquals("some information", $e->getMessage());
        }
    }

    public function testForbiddenResponse(): void
    {
        $data = [
            "code" => 403,
            "details" => "some details",
            "information" => "some information"
        ];

        try {
            ResponseParser::create($data);
        } catch (SurrealException $e) {
            $this->assertInstanceOf(SurrealException::class, $e);
            $this->assertEquals("some information", $e->getMessage());
        }

        try {
            new ForbiddenResponse($data);
        } catch (SurrealException $e) {
            $this->assertInstanceOf(SurrealException::class, $e);
            $this->assertEquals("some information", $e->getMessage());
        }
    }

    public function testWebsocketResponse(): void
    {
        $data = [
            "id" => 1,
            "result" => "success"
        ];

        try {
            /** @var WebsocketResponse $response */
            $response = ResponseParser::create($data);

            $this->assertInstanceOf(WebsocketResponse::class, $response);
            $this->assertEquals("success", $response->result);

        } catch (\Exception $exception) {
            // does nothing
        }
    }

    public function testWebsocketErrorResponse(): void
    {
        $data = [
            "error" => [
                "code" => 400,
                "message" => "some message"
            ],
            "id" => 1
        ];

        try {
            ResponseParser::create($data);
        } catch (SurrealException $e) {
            $this->assertInstanceOf(SurrealException::class, $e);
            $this->assertEquals("some message", $e->getMessage());
        }
    }
}