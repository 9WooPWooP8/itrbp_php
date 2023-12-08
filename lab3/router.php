<?php

use Lab\Exceptions\HttpException;
use Lab\Http\Actions\Articles\DeleteArticle;
use Lab\Http\Actions\ArticlesLikes\CreateArticleLike;
use Lab\Http\Actions\Comments\CreateComment;
use Lab\Http\Actions\Articles\CreateArticle;
use Lab\Http\ErrorResponse;
use Lab\Http\Request;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';
$logger = $container->get(LoggerInterface::class);

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));


try {
	$path = $request->path();
} catch (HttpException) {
	$logger->warning($error->getMessage());
	(new ErrorResponse)->send();
	return;
}

try {
	$method = $request->method();
} catch (HttpException) {
	$logger->warning($error->getMessage());
	(new ErrorResponse)->send();
	return;
}

$routes = [
	'POST' => [
		'/posts/comment' => CreateComment::class,
		'/posts/likes' => CreateArticleLike::class,
		'/posts' => CreateArticle::class
	],
	'DELETE' => [
		'/posts' => DeleteArticle::class
	],
];

if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
	$message = "Route not found: $method $path";
	$logger->notice($message);
	(new ErrorResponse($message))->send();
	return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
	$response = $action->handle($request);
} catch (Exception $error) {
	$logger->error($error->getMessage(), ['exception' => $error]);
	(new ErrorResponse)->send();
}

$response->send();