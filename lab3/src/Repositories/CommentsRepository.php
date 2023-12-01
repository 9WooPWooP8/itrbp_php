<?php

namespace Lab\Repositories;

use Lab\Interfaces\CommentsRepositoryInterface;
use Lab\Entities\Comment;
use Lab\DB;
use Lab\Exceptions\CommentNotFoundException;

class CommentsRepository implements CommentsRepositoryInterface
{
	public DB $db;

	public function __construct(DB $db = null)
	{
		$this->db = $db;
	}
	public function get(string $uuid): Comment
	{
		$statement = $this->db->prepare('SELECT * from comments where uuid=:uuid');

		$result = $statement->execute([
			':uuid' => $uuid
		]);


		if ($result === false) {
			throw new CommentNotFoundException();
		}

		$result = $result->fetchArray(SQLITE3_ASSOC);

		$comment = new Comment(
			text: $result["text"],
			id: $result["uuid"],
			article_id: $result["article_uuid"],
			author_id: $result["author_uuid"],
		);

		return $comment;
	}

	public function save(Comment $comment)
	{
		$statement = $this->db->prepare(
			'INSERT INTO comments (uuid, article_uuid, text, author_uuid)
			VALUES (:uuid, :article_uuid, :text, :author_uuid) 
			ON CONFLICT(uuid) 
			DO UPDATE SET 
			title=excluded.article_uuid, text=excluded.text, author_id=excluded.author_uuid;'
		);

		$statement->execute([
			':uuid' => $comment->id,
			':article_uuid' => $comment->article_id,
			':text' => $comment->text,
			':author_uuid' => $comment->author_id,
		]);
	}
}
