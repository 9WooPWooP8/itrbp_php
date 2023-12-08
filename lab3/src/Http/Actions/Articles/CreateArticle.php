<?php

namespace Lab\Http\Actions\Articles;

use Exception;
use Lab\Entities\Article;
use Lab\Exceptions\HttpException;
use Lab\Http\Actions\ActionInterface;
use Lab\Http\ErrorResponse;
use Lab\Http\Request;
use Lab\Http\Response;
use Lab\Http\SuccessfulResponse;
use Lab\Interfaces\ArticlesRepositoryInterface;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
    )
    {
    }

    public function handle(Request $request): Response
	{
        $newArticleUuid = uniqid();

        try {
            $article = new Article(
                $newArticleUuid,
                $request->jsonBodyField('author_uuid'),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        if (strlen($article->author_id) != 13 or !preg_match("/^[a-zA-Z0-9]+$/",$article->author_id)){
            return new ErrorResponse('author uuid is invalid');
        }

        try {
            $this->articlesRepository->save($article);
        } catch (Exception $exception){
            return new ErrorResponse('error during saving');
        }

        return new SuccessfulResponse([
            'uuid' => (string)$newArticleUuid,
        ]);
    }
}