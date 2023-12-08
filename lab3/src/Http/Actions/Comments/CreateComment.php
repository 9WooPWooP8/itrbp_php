<?php

namespace Lab\Http\Actions\Comments;

use Lab\Exceptions\HttpException;
use Lab\Http\ErrorResponse;
use Lab\Http\Request;
use Lab\Http\Response;
use Lab\Http\SuccessfulResponse;
use Lab\Entities\Comment;
use Lab\Interfaces\CommentsRepositoryInterface;
use Lab\Http\Actions\ActionInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
    )
    {
    }

    public function handle(Request $request): Response
	{
        $newCommentId = uniqid();

        try {
            $post = new Comment(
                $newCommentId,
                $request->jsonBodyField('author_uuid'),
                $request->jsonBodyField('article_uuid'),
                $request->jsonBodyField('text')
            );
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->commentsRepository->save($post);

        return new SuccessfulResponse([
            'uuid' => (string)$newCommentId,
        ]);
    }
}