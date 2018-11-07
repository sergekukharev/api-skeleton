<?php

namespace SergeiKukhariev\ApiSkeleton\Middlewares;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\ServerRequest;

class FilterByFieldMiddlewareTest extends TestCase
{
    /** @var FilterByFieldMiddleware */
    private $middleware;
    /** @var RequestHandlerInterface | MockObject */
    private $nextMock;

    const nextResponse = [
        'items' => [
            [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'city' => 'Berlin'
            ],
            [
                'firstName' => 'Robert',
                'lastName' => 'Doe',
                'city' => 'London'
            ],
            [
                'firstName' => 'John',
                'lastName' => 'Snow',
                'city' => 'Winterfell'
            ],
        ],
    ];

    public function setUp()
    {
        $this->middleware = new FilterByFieldMiddleware();
        $this->nextMock = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $this->nextMock->method('handle')->willReturn(
            new JsonResponse(
                self::nextResponse
            )
        );
    }

    public function testIsMiddleware()
    {
        self::assertInstanceOf(MiddlewareInterface::class, $this->middleware);
    }

    public function testDoesNothingWithResponseIfNoFiltersAreProvided()
    {
        $request = new ServerRequest();

        /** @var JsonResponse $response */
        $response = $this->middleware->process($request, $this->nextMock);

        self::assertEquals(self::nextResponse, $response->getPayload());
    }

    public function testIgnoresFiltersIfFieldsAreNotInPayload()
    {
        $filter = [
            'items' => [
                'unknownFilterField' => 'some-value',
            ],
        ];

        $request = (new ServerRequest())->withAttribute('filters', $filter);

        /** @var JsonResponse $response */
        $response = $this->middleware->process($request, $this->nextMock);

        self::assertEquals(self::nextResponse, $response->getPayload());
    }

    public function testFiltersCollectionsByExactFilterValue()
    {
        $filter = [
            'items' => [
                'firstName' => 'John',
            ],
        ];

        $request = (new ServerRequest())->withAttribute('filters', $filter);

        /** @var JsonResponse $response */
        $response = $this->middleware->process($request, $this->nextMock);

        $expectedResponse = [
            'items' => [
                [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'city' => 'Berlin'
                ],
                [
                    'firstName' => 'John',
                    'lastName' => 'Snow',
                    'city' => 'Winterfell'
                ],
            ],
        ];

        self::assertEquals($expectedResponse, $response->getPayload());
    }

    public function testAllowsMultipleFilters()
    {
        $filter = [
            'items' => [
                'firstName' => 'John',
                'city' => 'Winterfell',
            ],
        ];

        $request = (new ServerRequest())->withAttribute('filters', $filter);

        /** @var JsonResponse $response */
        $response = $this->middleware->process($request, $this->nextMock);

        $expectedResponse = [
            'items' => [
                [
                    'firstName' => 'John',
                    'lastName' => 'Snow',
                    'city' => 'Winterfell'
                ],
            ],
        ];

        self::assertEquals($expectedResponse, $response->getPayload());
    }

    public function testReturnsEmptyCollectionsIfNothingLeft()
    {
        $filter = [
            'items' => [
                'firstName' => 'Richard',
            ],
        ];

        $request = (new ServerRequest())->withAttribute('filters', $filter);

        /** @var JsonResponse $response */
        $response = $this->middleware->process($request, $this->nextMock);

        $expectedResponse = [
            'items' => [],
        ];

        self::assertEquals($expectedResponse, $response->getPayload());
    }
}
