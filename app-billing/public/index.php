<?php

use App\DTO\OperationDTO;
use App\DTO\QueueDTO;
use App\Middleware\AfterMiddleware;
use App\Middleware\CheckAuthMiddleware;
use App\Middleware\JsonBodyParserMiddleware;
use App\Repository\BillingRepository;
use App\Storages\UserStorage;
use App\UseCase\Handler;
use DI\Container;
use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;

require '../vendor/autoload.php';

$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$container = $app->getContainer();
$container->set('db', function(){
	return new PDO(
		'pgsql:host=' . $_ENV['POSTGRES_HOST'] . ';dbname=' . $_ENV['POSTGRES_DB'],
		$_ENV['POSTGRES_USER'],
		$_ENV['POSTGRES_PASSWORD']
	);
});
$container->set('billingRepository', function(ContainerInterface $container){
	return new BillingRepository($container->get('db'));
});

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$userId = null;

$app->add(new CheckAuthMiddleware());
$app->add(new JsonBodyParserMiddleware());
$app->add(new AfterMiddleware());

$app->get('/get', function (Request $request, Response $response): Response
{
	$result = [
		'balance' => $this->get('billingRepository')->getBalanceByUserId(UserStorage::getUserId()),
	];

	$response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));

	return $response;
});


$app->post('/create',function (Request $request, Response $response): Response
{
	try {
		$operation = new OperationDTO(
			App\Enum\OperationTypeEnum::INCOMING,
			UserStorage::getUserId(),
			0
		);

		$handler = new Handler($this->get('billingRepository'));
		$handler->handle($operation);

		$result = [
			'status' => 'success',
		];
	} catch (Throwable $throwable) {
		$result = [
			'status' => 'error',
			'error' => $throwable->getMessage(),
		];
	}

	$response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));

	return $response;
});

$app->post('/incoming', function (Request $request, Response $response): Response
{
	$params = (array)$request->getParsedBody();

	try {
		$operation = new OperationDTO(
			App\Enum\OperationTypeEnum::INCOMING,
			$params['userId'],
			$params['value'] ?? 0
		);

		$handler = new Handler($this->get('billingRepository'));
		$handler->handle($operation);

		$result = [
			'status' => 'success',
		];
	} catch (Throwable $throwable) {
		$result = [
			'status' => 'error',
			'error' => $throwable->getMessage(),
		];
	}

	$response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));

	return $response;
});

$app->post('/pay', function (Request $request, Response $response): Response
{
	$params = (array)$request->getParsedBody();

	try {
		$operation = new OperationDTO(
			App\Enum\OperationTypeEnum::OUTCOMING,
			UserStorage::getUserId(),
			$params['value'] ?? 0
		);

		$handler = new Handler($this->get('billingRepository'));
		$handler->handle($operation);

		$result = [
			'status' => 'success',
		];
	} catch (Throwable $throwable) {
		$result = [
			'status' => 'error',
			'error' => $throwable->getMessage(),
		];
	}

	$response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));

	return $response;
});

$app->run();