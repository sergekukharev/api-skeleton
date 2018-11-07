<?php


namespace SergeiKukhariev\ApiSkeleton\Actions\Translations;


use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SergeiKukhariev\ApiSkeleton\Errors\ClientError;
use SergeiKukhariev\ApiSkeleton\Errors\NotFoundError;
use Zend\Diactoros\Response\JsonResponse;

class GetTranslationAction implements RequestHandlerInterface
{
    /** @var array */
    private $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../../../data/translations.php';
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (null === $request->getAttribute('translationKey')) {
            throw new ClientError(
                StatusCodeInterface::STATUS_BAD_REQUEST,
                'Bad Request',
                'translationKey was not provided'
            );
        }

        if (!array_key_exists($request->getAttribute('translationKey'), $this->db)) {

            throw new NotFoundError('Not Found', 'Provided translation key was not found');
        }

        return new JsonResponse($this->db[$request->getAttribute('translationKey')]);
    }
}
