<?php
/**
 * @var ContainerInterface $container
 */
use App\Console\NotifySender;
use App\Repository\NotifyRepository;
use Psr\Container\ContainerInterface;

$container->set('db', function(){
	return new PDO(
		'pgsql:host=' . $_ENV['POSTGRES_HOST'] . ';dbname=' . $_ENV['POSTGRES_DB'],
		$_ENV['POSTGRES_USER'],
		$_ENV['POSTGRES_PASSWORD']
	);
});

$container->set('notifyRepository', function(ContainerInterface $container){
	return new NotifyRepository($container->get('db'));
});

$container->set('notifySender', function(ContainerInterface $container){
	return new NotifySender($container->get('notifyRepository'));
});

return $container;