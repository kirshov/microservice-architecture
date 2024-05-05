<?php
use App\DTO\UserLogin;
use App\DTO\UserSignin;
use App\Repository\UserRepository;
use App\UseCase\Auth;
use App\UseCase\Login;
use App\UseCase\Logout;
use App\UseCase\Registration;
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
$container->set('userRepository', function(ContainerInterface $container){
	return new UserRepository($container->get('db'));
});

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
	return $response;
});

$app->post('/signin', function (Request $request, Response $response): Response
{
	$params = (array)$request->getParsedBody();

	$user = new UserSignin(
		email: $params['email'],
		name: $params['name'],
		password: $params['password']
	);

	try {
		$handler = new Registration($this->get('userRepository'));
		$id = $handler->run($user);

		if (null === $id) {
			throw new RuntimeException('Не удалось создать пользователя');
		}

		$result = [
			'status' => 'success',
			'email' => $params['email'] ?? null,
			'id' => $id,
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

$app->post('/login', function (Request $request, Response $response): Response
{
	$params = (array)$request->getParsedBody();

	$user = new UserLogin(
		email: $params['email'],
		password: $params['password']
	);

	try {
		$handler = new Login($this->get('userRepository'));
		$handler->run($user);

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

$app->get('/auth', function (Request $request, Response $response): Response
{
	try {
		$data = (new Auth())->run();

		if (null !== $data) {
			http_response_code(200);
			header('response: authorized');

			foreach ($data as $key => $value) {
				header($key . ': ' . $value);
			}
		}
	} catch (Throwable $throwable) {
		http_response_code(401);
	}

	return $response;
});

$app->get('/logout', function (Request $request, Response $response): Response
{
	(new Logout())->run();

	return $response;
});

$app->run();