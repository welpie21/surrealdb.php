<?php

namespace parsers;

use PHPUnit\Framework\TestCase;
use Surreal\classes\ResponseParser;
use Surreal\classes\responses\AuthResponse;
use Surreal\classes\responses\HTTPErrorResponse;
use Surreal\classes\responses\ForbiddenResponse;
use Surreal\classes\responses\WebsocketErrorResponse;
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
            /** @var AuthResponse $response */
            $response = ResponseParser::create($data);
            $this->assertInstanceOf($response::class, AuthResponse::class);
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
        } catch (\Exception $e) {
            $this->assertInstanceOf($e::class, HTTPErrorResponse::class);
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
        } catch (\Exception $e) {
            $this->assertInstanceOf($e, ForbiddenResponse::class);
            $this->assertEquals("some information", $e->getMessage());
        }

        try {
            new ForbiddenResponse($data);
        } catch (\Exception $e) {
            $this->assertInstanceOf($e, ForbiddenResponse::class);
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

            $this->assertInstanceOf($response::class, AuthResponse::class);
            $this->assertEquals("success", $response->result);

        } catch (\Exception $exception) {
            // does nothing
        }
    }

    public function testWebsocketErrorResponse(): void
    {
        $data = [
            "id" => 1,
            "error" => [
                "code" => 400,
                "message" => "some message"
            ]
        ];

        try {
            ResponseParser::create($data);
        } catch (\Exception $e) {
            $this->assertInstanceOf($e::class, WebsocketErrorResponse::class);
            $this->assertEquals("some message", $e->getMessage());
        }
    }
}