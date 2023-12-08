<?php

namespace Tests\Http\Actions\Articles;

use Exception;
use Lab\Entities\Article;
use Lab\Entities\User;
use Lab\Exceptions\ArticleNotFoundException;
use Lab\Http\Actions\Articles\CreateArticle;
use Lab\Http\ErrorResponse;
use Lab\Http\Request;
use Lab\Http\SuccessfulResponse;
use Lab\Interfaces\ArticlesRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateArticleTest extends TestCase
{
    private function articlesRepository(array $articles, array $users): ArticlesRepositoryInterface
    {
        return new class($articles, $users) implements ArticlesRepositoryInterface
        {
            public function __construct(
                private array $articles,
                private array $users
            ) {
            }

            public function save(Article $article): void
            {
                $user_found = false;
                foreach ($this->users as $user) {
                    if ($user->id == $article->author_id) {
                        $user_found = true;
                        break;
                    }
                }

                if (!$user_found) {
                    throw new Exception();
                }

                array_push($this->articles, $article);
            }

            public function get(string $uuid): Article
            {
                throw new ArticleNotFoundException("Not found");
            }

            public function delete(string $uuid)
            {
                throw new Exception;
            }
        };
    }
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disable
     * @throws JsonException
     */
    public function testReturnsErrorResponseIfNotAllFieldsProvided(): void
    {
        $request = new Request([], [], '{
            "author_uuid": "1111111111111",
            "title": "title",
        }');
        $articleRepository = $this->articlesRepository([], [new User('1111111111111', 'name', 'surname')]);

        $action = new CreateArticle($articleRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        // $this->expectOutputString('{"succuess":false,"reason":"No such query param in the request: username"}');

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disable
     * @throws JsonException
     */
    public function testReturnsErrorResponseIfNoUserWithUuid(): void
    {
        $request = new Request([], [], '{
            "author_uuid": "1111111111112",
            "title": "title",
            "text": "text"
        }');

        $articleRepository = $this->articlesRepository([], [new User('1111111111111', 'name', 'surname')]);

        $action = new CreateArticle($articleRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        // $this->expectOutputString('{"succuess":false,"reason":"No such query param in the request: username"}');

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disable
     * @throws JsonException
     */
    public function testReturnsErrorResponseIfInvlalidUuid(): void
    {
        $request = new Request([], [], '{
            "author_uuid": "11111111111__",
            "title": "title",
            "text": "text"
        }');

        $articleRepository = $this->articlesRepository([], [new User('1111111111111', 'name', 'surname')]);

        $action = new CreateArticle($articleRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"succuess":false,"reason":"author uuid is invalid"}');

        $response->send();
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disable
     * @throws JsonException
     */
    public function testReturnsSuccessfulResponse(): void
    {
        $request = new Request([], [], '{
            "author_uuid": "1111111111111",
            "title": "title",
            "text": "text"
        }');

        $articleRepository = $this->articlesRepository([], [new User('1111111111111', 'name', 'surname')]);

        $action = new CreateArticle($articleRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        // $this->expectOutputString('{"succuess":true,"data":{"username":"ivan","name":"Ivan Ivanov"}}');

        $response->send();
    }
}
