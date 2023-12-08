<?php

namespace Lab\Http\Actions\Articles;

use Lab\Exceptions\HttpException;
use Lab\Http\ErrorResponse;
use Lab\Http\Request;
use Lab\Http\Response;
use Lab\Http\SuccessfulResponse;
use Lab\Http\Actions\ActionInterface;
use Lab\Interfaces\ArticlesRepositoryInterface;

class DeleteArticle implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
    )
    {
    }

    public function handle(Request $request): Response
	{
        try {
            $articleId = $request->query('uuid');
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->articlesRepository->delete($articleId);

        return new SuccessfulResponse();
    }
}