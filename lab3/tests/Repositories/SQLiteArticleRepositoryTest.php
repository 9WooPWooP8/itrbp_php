<?php

namespace Tests\Repositories;

use Lab\Db;
use Lab\Entities\Article;
use Lab\Exceptions\ArticleNotFoundException;
use Lab\Repositories\ArticlesRepository;
use PHPUnit\Framework\TestCase;
use SQLite3Result;
use SQLite3Stmt;
use Tests\DummyLogger;

use function PHPUnit\Framework\assertEquals;

final class SQLiteArticleRepositoryTest extends TestCase
{
	public function testSave()
	{
		$connectionStub = $this->createStub(Db::class);
		$statementMock = $this->createMock(SQLite3Stmt::class);

		// $statementMock->expects($this->once())->method('bindValue')->with(':text', '321');
		// $statementMock->expects($this->once())->method('bindValue')->with(':text', 'title');
		// $statementMock->expects($this->once())->method('bindValue')->with(':text', '123');
		// $statementMock->expects($this->once())->method('bindValue')->with(':text', 'text');


		$statementMock->expects($this->once())->method('execute')->with();

		$connectionStub->method('prepare')->WillReturn($statementMock);

		$repository = new ArticlesRepository($connectionStub, new DummyLogger);


		$article = new Article('123', '321', 'title', 'text');
		$repository->save($article);
	}

	public function testGetById()
	{
		$connectionStub = $this->createStub(Db::class);
		$statementMock = $this->createMock(SQLite3Stmt::class);

		$sqlResultMock = $this->createMock(SQLite3Result::class);

		// $statementMock->expects($this->once())->method('bindValue')->with(':uuid', '321');
		$statementMock->expects($this->once())->method('execute')->WillReturn($sqlResultMock);

		$sqlResultMock->expects($this->once())
			->method('fetchArray')->with(SQLITE3_ASSOC)->WillReturn(
				[
					'uuid' => '321',
					'text' => 'text',
					'title' => 'title',
					'author_uuid' => '123'
				]
			);


		$connectionStub->method('prepare')->WillReturn($statementMock);

		$repository = new ArticlesRepository($connectionStub, new DummyLogger);

		$article = new Article('321', '123', 'title', 'text');

		$result = $repository->get('321');

		assertEquals($article, $result);
	}

	public function testGetByIdThrowsExceptionIfNotFound()
	{
		$connectionStub = $this->createStub(Db::class);
		$statementStub = $this->createStub(SQLite3Stmt::class);

		$statementStub->method('execute')->willReturn(false);

		$connectionStub->method('prepare')->willReturn($statementStub);

		$repository = new ArticlesRepository($connectionStub, new DummyLogger);

		$this->expectException(ArticleNotFoundException::class);

		$uuid = "123123";
		$repository->get($uuid);
	}
}
