<?php

namespace Lab\Repositories;

use Lab\Entities\Article;
use Lab\Interfaces\ArticlesRepositoryInterface;
use Lab\Db;
use Lab\Exceptions\ArticleNotFoundException;
use Psr\Log\LoggerInterface;
use SQLite3;

class ArticlesRepository implements ArticlesRepositoryInterface
{
	public function __construct(private SQLite3 $db, private LoggerInterface $logger)
	{
	}
	public function get(string $uuid): Article
	{
		$statement = $this->db->prepare('SELECT * from user where uuid=:uuid');

		$statement->bindValue(':uuid', $uuid);

		$result = $statement->execute();


		if ($result === false) {
			throw new ArticleNotFoundException();
		}

		$result = $result->fetchArray(SQLITE3_ASSOC);

		$article = new Article(
			text: $result["text"],
			id: $result["uuid"],
			title: $result["title"],
			author_id: $result["author_uuid"],
		);

		return $article;
	}

	public function save(Article $article)
	{
		$this->logger->warning(sprintf("saving article %s", $article->id));

		$statement = $this->db->prepare(
			'INSERT INTO articles (uuid, title, text, author_uuid)
			VALUES(:uuid, :title, :text, :author_uuid) 
			ON CONFLICT(uuid) 
			DO UPDATE SET 
			title=excluded.title, text=excluded.text, author_uuid=excluded.author_uuid;'
		);

		$statement->bindValue(':uuid', $article->id);
		$statement->bindValue(':title', $article->title);
		$statement->bindValue(':text', $article->text);
		$statement->bindValue(':author_uuid', $article->author_id);

		$statement->execute();
	}


	public function delete(string $uuid)
	{
		$statement = $this->db->prepare('DELETE from articles where uuid=:uuid');

		$statement->bindValue(':uuid', $uuid);

		$result = $statement->execute();

		if ($result === false) {
			throw new ArticleNotFoundException();
		}
	}
}
