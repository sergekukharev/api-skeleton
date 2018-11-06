<?php

namespace SergeiKukhariev\ApiSkeleton\Actions\Health;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ServerRequest;

class GetHealthActionTest extends TestCase
{
    /** @var GetHealthAction */
    private $action;

    public function setUp()
    {
        $this->action = new GetHealthAction();
    }

    public function testIsRequestHandler()
    {
        self::assertInstanceOf(RequestHandlerInterface::class, $this->action);
    }

    public function testReturnsOkByDefault()
    {
        $request = new ServerRequest();
        self::assertEquals(
            StatusCodeInterface::STATUS_NO_CONTENT,
            $this->action->handle($request)->getStatusCode()
        );
    }
}
