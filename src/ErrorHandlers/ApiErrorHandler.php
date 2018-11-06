<?php


namespace SergeiKukhariev\ApiSkeleton\ErrorHandlers;


use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SergeiKukhariev\ApiSkeleton\Errors\ApiError;
use SergeiKukhariev\ApiSkeleton\Errors\ClientError;
use SergeiKukhariev\ApiSkeleton\Errors\ServerError;
use Zend\Diactoros\Response\JsonResponse;
use Zend\ProblemDetails\ProblemDetailsResponseFactory;

class ApiErrorHandler implements MiddlewareInterface
{
    private $isDebugEnabled;

    public function __construct(bool $isDebugEnabled = false)
    {
        $this->isDebugEnabled = $isDebugEnabled;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ClientError $e) {
            return new JsonResponse(
                $this->makeProblemDetailsPayload($e),
                $e->getStatusCode(),
                $this->getResponseHeaders()
            );
        } catch (ServerError $e) {
            return $this->makeServerErrorResponse($e);
        }
    }

    private function makeProblemDetailsPayload(ApiError $e): array
    {
        return [
            'title' => $e->getTitle(),
            'type' => sprintf('https://httpstatus.es/%d', $e->getStatusCode()),
            'status' => $e->getStatusCode(),
            'detail' => $e->getDetail(),
        ];
    }

    private function getResponseHeaders(): array
    {
        return [
            'Content-Type' => ProblemDetailsResponseFactory::CONTENT_TYPE_JSON
        ];
    }

    private function makeServerErrorResponse(ServerError $e): JsonResponse
    {
        return new JsonResponse(
            $this->isDebugEnabled ? $this->makeProblemDetailsPayload($e) : $this->makeStrippedPayload($e),
            $this->isDebugEnabled ? $e->getStatusCode() : StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
            $this->getResponseHeaders()
        );
    }

    private function makeStrippedPayload(ServerError $e)
    {
        return [
            'title' => 'Internal Server Error',
            'type' => 'https://httpstatus.es/500',
            'status' => StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
            'detail' => 'Something went wrong',
        ];
    }
}