<?php

namespace Lab\Repositories;

use Lab\Interfaces\CommentsRepositoryInterface;
use Lab\Entities\Comment;
use Lab\Db;
use Lab\Exceptions\CommentNotFoundException;
use Psr\Log\LoggerInterface;
use SQLite3;

class CommentsRepository implements CommentsRepositoryInterface
{
	public function __construct(private SQLite3 $db, private LoggerInterface $logger)
	{
	}
	public function get(string $uuid): Comment
	{
		$statement = $this->db->prepare('SELECT * from comments where uuid=:uuid');

		$statement->bindValue(':uuid', $uuid);

		$result = $statement->execute();


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
		$this->logger->warning(sprintf("saving comment %s", $comment->id));

		$statement = $this->db->prepare(
			'INSERT INTO comments (uuid, article_uuid, text, author_uuid)
			VALUES (:uuid, :article_uuid, :text, :author_uuid) 
			ON CONFLICT(uuid) 
			DO UPDATE SET 
			article_uuid=excluded.article_uuid, text=excluded.text, author_uuid=excluded.author_uuid;'
		);

		$statement->bindValue(':uuid', $comment->id);
		$statement->bindValue(':article_uuid',$comment->article_id);
		$statement->bindValue(':text', $comment->text);
		$statement->bindValue(':author_uuid', $comment->author_id);

		$statement->execute();
	}
}
