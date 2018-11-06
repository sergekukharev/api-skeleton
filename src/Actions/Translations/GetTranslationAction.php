<?php


namespace SergeiKukhariev\ApiSkeleton\Actions\Translations;


use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SergeiKukhariev\ApiSkeleton\Errors\ClientError;
use Zend\Diactoros\Response\EmptyResponse;

class GetTranslationAction implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (null === $request->getAttribute('translationKey')) {
            throw new ClientError(
                StatusCodeInterface::STATUS_BAD_REQUEST,
                'Bad Request',
                'translationKey was not provided'
            );
        }

        return new EmptyResponse();
    }
}