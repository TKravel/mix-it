<?php

use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/vendor/autoload.php';


$app = AppFactory::create();

$app->setBasePath('/mix-it');
$app->addBodyParsingMiddleware();
require __DIR__ . '/config/routes.php';

// Create Twig
$twig = Twig::create(__DIR__ . '/templates', ['cache' => false, 'debug' => true]);

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

$twig->addExtension(new \Twig\Extension\DebugExtension());

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->run();