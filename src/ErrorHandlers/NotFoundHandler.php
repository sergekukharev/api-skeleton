<?php

namespace SergeiKukhariev\ApiSkeleton\ErrorHandlers;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\TextResponse;

class NotFoundHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new TextResponse(
            sprintf('Cannot %s %s', $request->getMethod(), $request->getUri()), StatusCodeInterface::STATUS_NOT_FOUND
        );
    }
}