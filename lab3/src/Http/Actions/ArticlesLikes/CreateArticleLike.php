<?php

namespace Lab\Http\Actions\ArticlesLikes;

use Lab\Entities\ArticleLike;
use Lab\Exceptions\HttpException;
use Lab\Http\ErrorResponse;
use Lab\Http\Request;
use Lab\Http\Response;
use Lab\Http\SuccessfulResponse;
use Lab\Entities\Comment;
use Lab\Interfaces\CommentsRepositoryInterface;
use Lab\Http\Actions\ActionInterface;
use Lab\Interfaces\ArticlesLikesRepositoryInterface;

class CreateArticleLike implements ActionInterface
{
    public function __construct(
        private ArticlesLikesRepositoryInterface $articlesLikesRepository,
    )
    {
    }

    public function handle(Request $request): Response
	{
        $newLikeId = uniqid();

        try {
            $like = new ArticleLike(
                $newLikeId,
                $request->jsonBodyField('user_uuid'),
                $request->jsonBodyField('article_uuid'),
            );
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->articlesLikesRepository->save($like);

        return new SuccessfulResponse([
            'uuid' => (string)$newLikeId,
        ]);
    }
}