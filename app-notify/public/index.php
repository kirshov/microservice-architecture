<?php

use App\Middleware\JsonBodyParserMiddleware;
use App\Repository\NotifyRepository;
use DI\Container;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require '../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();

/** @var ContainerInterface $container */
$container = include dirname(__DIR__) . '/src/config/container.php';

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->add(new JsonBodyParserMiddleware());

$app->post('/add',function (Request $request, Response $response): Response
{
	$params = (array)$request->getParsedBody();

	try {
		/** @var NotifyRepository $notifyRepository */
		$notifyRepository = $this->get('notifyRepository');
		$notifyRepository->add($params['userId'], $params['email'], $params['text']);

		$result = [
			'status' => 'success',
		];
	} catch (Throwable $throwable) {
		$result = [
			'status' => 'error',
			'error' => $throwable->getMessage().$throwable->getTraceAsString(),
		];
	}

	$response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));

	return $response;
});

$app->run();