<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get(
        '/health',
        \SergeiKukhariev\ApiSkeleton\Actions\Health\GetHealthAction::class,
        'checkHealth'
    );

    $app->get(
        '/translations/{translationKey}',
        \SergeiKukhariev\ApiSkeleton\Actions\Translations\GetTranslationAction::class,
        'getTranslation'
    );
};
