<?php

namespace SergeiKukhariev\ApiSkeleton\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class FilterByFieldMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var JsonResponse $response */
        $response = $handler->handle($request);

        $filters = $request->getAttribute('filters', []);

        $data = $response->getPayload();

        foreach ($filters as $collectionName => $subfilters) {
            if (!isset($data[$collectionName])) {
                continue;
            }

            foreach ($subfilters as $filterName => $filterValue) {
                $result = [];
                $result[$collectionName] = [];

                foreach ($data[$collectionName] as $item) {
                    if (!isset($item[$filterName])) {
                        continue;
                    }

                    if ($item[$filterName] === $filterValue) {
                        $result[$collectionName][] = $item;
                    }

                    $data = $result;
                }
            }
        }

        return $response->withPayload($data);
    }
}
