<?php

namespace SergeiKukhariev\ApiSkeleton\ErrorHandlers;

use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use SergeiKukhariev\ApiSkeleton\Errors\ApiError;
use SergeiKukhariev\ApiSkeleton\Errors\ClientError;
use SergeiKukhariev\ApiSkeleton\Errors\ServerError;
use Zend\Diactoros\ServerRequest;
use Zend\ProblemDetails\ProblemDetailsResponseFactory;

class ApiErrorHandlerTest extends TestCase
{
    /** @var RequestHandlerInterface | MockObject */
    private $nextMock;
    /** @var ApiErrorHandler */
    private $handler;
    /** @var ServerRequest */
    private $request;

    private static function assertHasProblemDetailsPayload(
        \Psr\Http\Message\ResponseInterface $response,
        ApiError $error
    ) {
        $payload = json_decode($response->getBody(), true);

        self::assertEquals($payload['title'], $error->getTitle());
        self::assertEquals($payload['detail'], $error->getDetail());
        self::assertEquals($payload['status'], $error->getStatusCode());
        self::assertEquals($payload['type'], sprintf('https://httpstatus.es/%d', $error->getStatusCode()));
    }

    private static function assertIsProblemDetailsResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        self::assertEquals($response->getHeaderLine('Content-Type'), ProblemDetailsResponseFactory::CONTENT_TYPE_JSON);
    }

    public function setUp()
    {
        $this->handler = new ApiErrorHandler(true);
        $this->request = new ServerRequest();
        $this->nextMock = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
    }

    public function testProcessWillNotCatchGenericExceptions()
    {
        $expectedException = new \RuntimeException();
        $this->nextMock->method('handle')->willThrowException($expectedException);

        self::expectException(\RuntimeException::class);

        $this->handler->process($this->request, $this->nextMock);
    }

    public function testWillCatchClientApiErrorsAndReturnProblemDetailsResponse()
    {
        $clientError = new ClientError(410, 'something wrong from client side', 'some detail');

        $this->nextMock->method('handle')->willThrowException($clientError);

        $response = $this->handler->process($this->request, $this->nextMock);

        self::assertIsProblemDetailsResponse($response);
        self::assertHasProblemDetailsPayload($response, $clientError);
    }

    public function testWillCatchServerApiErrorsAndReturnProblemDetailsResponse()
    {
        $serverError = new ServerError(510, 'something wrong from server side', 'some detail');

        $this->nextMock->method('handle')->willThrowException($serverError);

        $response = $this->handler->process($this->request, $this->nextMock);

        self::assertIsProblemDetailsResponse($response);
        self::assertHasProblemDetailsPayload($response, $serverError);
    }

    public function testWillChangeClientErrorToInternalServerErrorAndStripDetailsOnProduction()
    {
        $isDebugEnabled = false;
        $handler = new ApiErrorHandler($isDebugEnabled);

        $serverError = new ServerError(510, 'something wrong from server side', 'some detail');
        $this->nextMock->method('handle')->willThrowException($serverError);

        $response = $handler->process($this->request, $this->nextMock);

        self::assertEquals(StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        $payload = json_decode($response->getBody(), true);
        self::assertEquals($payload['title'], 'Internal Server Error');
        self::assertEquals($payload['detail'], 'Something went wrong');
        self::assertEquals($payload['status'], StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
        self::assertEquals($payload['type'], 'https://httpstatus.es/500');
    }
}
