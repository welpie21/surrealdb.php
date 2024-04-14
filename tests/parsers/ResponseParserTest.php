<?php

namespace parsers;

use PHPUnit\Framework\TestCase;
use Surreal\Exceptions\SurrealException;
use Surreal\Responses\Auth\AuthResponse;
use Surreal\Responses\Error\ForbiddenResponse;
use Surreal\Responses\Http\HTTPErrorResponse;
use Surreal\Responses\ResponseInterface;
use Surreal\Responses\Rpc\RpcMessageErrorResponse;
use Surreal\Responses\Rpc\RpcMessageResponse;

class ResponseParserTest extends TestCase
{
    public function testAuthResponse(): void
    {
        $data = [
            "code" => 200,
            "details" => "success",
            "token" => "sometoken"
        ];

        $response = ResponseInterface::resolve($data);
        $this->assertInstanceOf(AuthResponse::class, $response);
    }

    public function testErrorResponse(): void
    {
        $response = [
            "code" => 400,
            "details" => "some details",
            "description" => "some description",
            "information" => "some information"
        ];

        $response = ResponseInterface::resolve($response);
        $this->assertInstanceOf(HTTPErrorResponse::class, $response);
    }

    public function testForbiddenResponse(): void
    {
        $data = [
            "code" => 403,
            "details" => "some details",
            "information" => "some information"
        ];

        $response = ResponseInterface::resolve($data);
        $this->assertInstanceOf(ForbiddenResponse::class, $response);
    }

    public function testWebsocketResponse(): void
    {
        $response = ResponseInterface::resolve(["id" => 1, "result" => "success"]);

        $this->assertInstanceOf(RpcMessageResponse::class, $response);
        $this->assertEquals("success", $response->result);
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

        $response = ResponseInterface::resolve($data);
        $this->assertInstanceOf(RpcMessageErrorResponse::class, $response);
    }
}