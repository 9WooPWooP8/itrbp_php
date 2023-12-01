<?php

use ArticlesRepository;
use CommentsRepository;
use DB;

$db = new DB(__DIR__ . '/../test.db');

$comment_repository = new CommentsRepository($db);

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_uri) {
	case "posts/comment":
		switch ($request_method) {
			case 'post':
				$post_body = file_get_contents('php://input');
				$comment = new Comment(
					text: $post_body["text"],
					article_id: $result["article_id"],
					author_id: $result["author_id"],
				);

				$comment_repository->save($comment);

				echo "success";

				break;
		}
		break;
}
