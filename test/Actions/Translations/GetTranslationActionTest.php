<?php

namespace SergeiKukhariev\ApiSkeleton\Actions\Translations;

use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use SergeiKukhariev\ApiSkeleton\Errors\ClientError;
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
        $this->request = (new ServerRequest())->withAttribute('translationKey', 'testKey');
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

    //TODO return one of the hardcoded translation key values.
}
