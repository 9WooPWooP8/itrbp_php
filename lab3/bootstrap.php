<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Lab\Interfaces\CommentsRepositoryInterface;
use Lab\Repositories\CommentsRepository;
use Lab\Container\DIContainer;
use Lab\Db;
use Lab\Entities\ArticleLike;
use Lab\Http\Actions\Comments\CreateComment;
use Lab\Interfaces\ArticlesLikesRepositoryInterface;
use Lab\Interfaces\ArticlesRepositoryInterface;
use Lab\Repositories\ArticlesLikesRepository;
use Lab\Repositories\ArticlesRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

$container = new DIContainer;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$logger = (new Logger('blog'));

if ($_SERVER['LOG_TO_FILES'] === 'yes') {
	$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/blog.log'))
		->pushHandler(new StreamHandler(
			__DIR__ . '/logs/blog.error.log', 
			level: Level::Error, 
			bubble: false
	));
}

if ($_SERVER['LOG_TO_CONSOLE'] === 'yes') {
	$logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(LoggerInterface::class, $logger);

$db = (new Db());

$container->bind(CommentsRepositoryInterface::class, CommentsRepository::class);
$container->bind(ArticlesRepositoryInterface::class, ArticlesRepository::class);
$container->bind(ArticlesLikesRepositoryInterface::class, ArticlesLikesRepository::class);

$container->bind(SQLite3::class, $db);


return $container;