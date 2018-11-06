<?php

namespace SergeiKukhariev\ApiSkeleton\Errors;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;

class ServerErrorTest extends TestCase
{
    /** @var ServerError */
    private $error;

    public function setUp()
    {
        $this->error = new ServerError();
    }

    public function testIsApiError()
    {
        self::assertInstanceOf(ApiError::class, $this->error);
    }

    public function testIsRuntimeException()
    {
        self::assertInstanceOf(\RuntimeException::class, $this->error);
    }

    public function testIs400ByDefault()
    {
        self::assertEquals(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, $this->error->getCode());
    }

    /**
     * @dataProvider provideInvalidClientCodes
     * @param int $statusCode
     */
    public function testRequires4xxStatusCode(int $statusCode)
    {
        self::expectException(\InvalidArgumentException::class);

        new ServerError($statusCode);
    }

    public function provideInvalidClientCodes()
    {
        return [
            [100],
            [200],
            [300],
            [400],
            [0],
        ];
    }

    public function testAllowsTitles()
    {
        $serverError = new ServerError(501, 'some title');

        self::assertEquals('some title', $serverError->getTitle());
    }

    public function testDefaultTitleIsEmpty()
    {
        self::assertEmpty($this->error->getTitle());
    }

    public function testAllowsDetails()
    {
        $serverError = new ServerError(501, 'some title', 'some detail');

        self::assertEquals('some detail', $serverError->getDetail());
    }

    public function testDefaultDetailIsEmpty()
    {
        self::assertEmpty($this->error->getDetail());
    }

    public function testCanReturnStatusCode()
    {
        $serverError = new ServerError(523);

        self::assertEquals(523, $serverError->getStatusCode());
    }

    public function testDefaultStatusCodeIsInternalServerError()
    {
        self::assertEquals(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, $this->error->getStatusCode());
    }
}
