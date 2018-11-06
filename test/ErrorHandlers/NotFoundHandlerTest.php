<?php

namespace SergeiKukhariev\ApiSkeleton\ErrorHandlers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class NotFoundHandlerTest extends TestCase
{
    /** @var NotFoundHandler */
    private $handler;
    /** @var ServerRequest */
    private $emptyRequest;

    public function setUp()
    {
        $this->handler = new NotFoundHandler();
        $this->emptyRequest = new ServerRequest([], [], new Uri('http://localhost:8080/'));
    }

    public function testIsRequestHandler()
    {
        self::assertInstanceOf(RequestHandlerInterface::class, $this->handler);
    }

    public function testReturnsResponseInstance()
    {
        self::assertInstanceOf(ResponseInterface::class, $this->handler->handle($this->emptyRequest));
    }

    public function testAssertContainsErrorMessage()
    {
        $response = $this->handler->handle($this->emptyRequest);

        self::assertEquals('Cannot GET http://localhost:8080/', $response->getBody());
    }

    public function testGivesInfoAboutMethod()
    {
        /** @noinspection PhpParamsInspection */
        $response = $this->handler->handle($this->emptyRequest->withMethod('PUT'));

        self::assertContains('Cannot PUT', (string)$response->getBody());
    }

    public function testIncludesInfoAboutPath()
    {
        /** @noinspection PhpParamsInspection */
        $response = $this->handler->handle($this->emptyRequest->withUri(new Uri('http://some/other/uri')));

        self::assertContains('http://some/other/uri', (string)$response->getBody());
    }

    public function testResponseHasNotFoundStatusCode()
    {
        self::assertEquals(StatusCodeInterface::STATUS_NOT_FOUND, $this->handler->handle($this->emptyRequest)->getStatusCode());
    }

    //TODO content type
}
