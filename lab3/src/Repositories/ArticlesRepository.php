<?php
include_once "../database.php";
use Article;
use ArticlesRepositoryInterface;


class ArticlesRepository implements ArticlesRepositoryInterface
{
	public DB $db;

	public function __construct(DB $db = null)
	{
		$this->db = $db;
	}
	public function get(string $uuid): Article
	{
		$result = $this->db->query("SELECT * from user where uuid=$uuid");

		$result->fetchArray();

		$article = new Article(
			text: $result["text"],
			id: $result["id"],
			title: $result["title"],
			author_id: $result["author_id"],
		);

		return $article;
	}

	public function save(Article $article)
	{
		$query = "
			INSERT INTO articles (uuid, title, text, author_uuid)
			VALUES(\"$article->id\", \"$article->title\", \"$article->text\", \"$article->author_id\") 
			ON CONFLICT(uuid) 
			DO UPDATE SET 
			title=excluded.title, text=excluded.text, author_uuid=excluded.author_uuid;";
		print_r($query);
		$result = $this->db->exec($query);

		return $article;
	}
}


$article = new Article(
	text: "fasdf",
	id: 1,
	title: "flaksdjflk",
	author_id: 1,
);

$repository = new ArticlesRepository($db);
$repository->save($article);
