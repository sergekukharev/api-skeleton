<?php

namespace SergeiKukhariev\ApiSkeleton\Errors;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;

class NotFoundErrorTest extends TestCase
{
    public function testIsClientError()
    {
        self::assertInstanceOf(ClientError::class, new NotFoundError());
    }

    public function testHas404StatusCode()
    {
        self::assertEquals(StatusCodeInterface::STATUS_NOT_FOUND, (new NotFoundError())->getStatusCode());
    }

    public function testAllowsTitles()
    {
        $error = new NotFoundError('some title');

        self::assertEquals('some title', $error->getTitle());
    }

    public function testDefaultTitleIsEmpty()
    {
        self::assertEmpty((new NotFoundError())->getTitle());
    }

    public function testAllowsDetails()
    {
        $error = new NotFoundError('some title', 'some detail');

        self::assertEquals('some detail', $error->getDetail());
    }

    public function testDefaultDetailIsEmpty()
    {
        self::assertEmpty((new NotFoundError())->getDetail());
    }
}
