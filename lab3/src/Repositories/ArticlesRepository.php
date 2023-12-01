<?php

namespace Lab\Repositories;

use Lab\Entities\Article;
use Lab\Interfaces\ArticlesRepositoryInterface;
use Lab\Db;
use Lab\Exceptions\ArticleNotFoundException;

class ArticlesRepository implements ArticlesRepositoryInterface
{
	public DB $db;

	public function __construct(DB $db = null)
	{
		$this->db = $db;
	}
	public function get(string $uuid): Article
	{
		$statement = $this->db->prepare('SELECT * from user where uuid=:uuid');

		$result = $statement->execute([
			':uuid' => $uuid
		]);


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
		$statement = $this->db->prepare(
			'INSERT INTO articles (uuid, title, text, author_uuid)
			VALUES(:uuid, :title, :text, :author_uuid) 
			ON CONFLICT(uuid) 
			DO UPDATE SET 
			title=excluded.title, text=excluded.text, author_uuid=excluded.author_uuid;'
		);

		$statement->execute([
			':uuid' => $article->id,
			':title' => $article->title,
			':text' => $article->text,
			':author_uuid' => $article->author_id,
		]);
	}
}
