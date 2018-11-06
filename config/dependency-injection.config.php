<?php

use \SergeiKukhariev\ApiSkeleton\Factories as Factories;

return [
    'dependencies' => [
        'factories' => [
            \Middlewares\ContentType::class => Factories\ContentTypeMiddlewareFactory::class,
            \SergeiKukhariev\ApiSkeleton\ErrorHandlers\ApiErrorHandler::class => Factories\ApiErrorHandlerFactory::class,
        ],
    ],
];