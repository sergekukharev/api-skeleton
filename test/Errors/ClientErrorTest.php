<?php

namespace SergeiKukhariev\ApiSkeleton\Errors;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;

class ClientErrorTest extends TestCase
{
    /** @var ClientError */
    private $error;

    public function setUp()
    {
        $this->error = new ClientError();
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
        self::assertEquals(StatusCodeInterface::STATUS_BAD_REQUEST, $this->error->getCode());
    }

    /**
     * @dataProvider provideInvalidClientCodes
     * @param int $statusCode
     */
    public function testRequires4xxStatusCode(int $statusCode)
    {
        self::expectException(\InvalidArgumentException::class);

        new ClientError($statusCode);
    }

    public function provideInvalidClientCodes()
    {
        return [
            [100],
            [200],
            [300],
            [500],
            [0],
        ];
    }

    public function testAllowsTitles()
    {
        $clientError = new ClientError(401, 'some title');

        self::assertEquals('some title', $clientError->getTitle());
    }

    public function testDefaultTitleIsEmpty()
    {
        self::assertEmpty($this->error->getTitle());
    }

    public function testAllowsDetails()
    {
        $clientError = new ClientError(401, 'some title', 'some detail');

        self::assertEquals('some detail', $clientError->getDetail());
    }

    public function testDefaultDetailIsEmpty()
    {
        self::assertEmpty($this->error->getDetail());
    }

    public function testCanReturnStatusCode()
    {
        $clientError = new ClientError(423);

        self::assertEquals(423, $clientError->getStatusCode());
    }
}
