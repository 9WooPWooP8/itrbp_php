<?php
include_once "../database.php";
include_once "../Interfaces/ArticlesRepositoryInterface.php";
include_once "../Article.php";


class CommentsRepository implements CommentsRepositoryInterface
{
	public DB $db;

	public function __construct(DB $db = null)
	{
		$this->db = $db;
	}
	public function get(string $uuid): Comment
	{
		$result = $this->db->query("SELECT * from comments where uuid=$uuid");

		$result->fetchArray();

		$comment = new Comment(
			text: $result["text"],
			id: $result["id"],
			article_id: $result["article_id"],
			author_id: $result["author_id"],
		);

		return $comment;
	}

	public function save(Comment $comment)
	{
		$result = $this->db->exec("
			INSERT INTO comments (uuid, article_uuid, text, author_uuid)
			VALUES($comment->id, $comment->article_id, $comment->text, $comment->author_id) 
			ON CONFLICT(uuid) 
			DO UPDATE SET 
			title=excluded.article_id, text=excluded.text, author_id=excluded.author_id;");

		return $comment;
	}
}


$comment = new Comment(
	text: "fasdf",
	id: $result["id"],
	article_id: $result["article_id"],
	author_id: $result["author_id"],
);
