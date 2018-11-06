<?php

namespace SergeiKukhariev\ApiSkeleton\Factories;

class ContentTypeMiddlewareFactory
{
    public function __invoke()
    {
        return new \Middlewares\ContentType([
            'json' => [
                'extension' => ['json'],
                'mime-type' => ['application/json', 'text/json', 'application/x-json'],
                'charset' => true,
            ],
        ]);
    }
}