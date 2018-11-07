<?php

namespace SergeiKukhariev\ApiSkeleton\Actions\Translations;

use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use SergeiKukhariev\ApiSkeleton\Errors\ClientError;
use SergeiKukhariev\ApiSkeleton\Errors\NotFoundError;
use Zend\Diactoros\ServerRequest;

class GetTranslationActionTest extends TestCase
{
    /** @var GetTranslationAction */
    private $action;
    /** @var ServerRequest */
    private $request;

    public function setUp()
    {
        $this->action = new GetTranslationAction();
        $this->request = (new ServerRequest())->withAttribute('translationKey', 'testTranslation');
    }

    public function testIsRequestHandler()
    {
        self::assertInstanceOf(RequestHandlerInterface::class, $this->action);
    }

    public function testRequiresTranslationKey()
    {
        self::expectException(ClientError::class);

        $this->action->handle($this->request->withoutAttribute('translationKey'));
    }

    public function testReturnsAllPossibleTranslations()
    {
        $translationsDb = require __DIR__ . '/../../../data/translations.php';

        $response = $this->action->handle($this->request);

        self::assertEquals($translationsDb['testTranslation'], json_decode($response->getBody(), true));
    }

    public function testReturnsNotFoundIfKeyIsMissing()
    {
        self::expectException(NotFoundError::class);

        $this->action->handle($this->request->withAttribute('translationKey', 'unknown-key'));
    }
}
