<?php

namespace Lab\Repositories;

use Lab\Entities\ArticleLike;
use Lab\Exceptions\ArticleNotFoundException;
use Lab\Exceptions\MoreThanOneLikeException;
use Lab\Interfaces\ArticlesLikesRepositoryInterface;
use Psr\Log\LoggerInterface;
use SQLite3;

// $container = require __DIR__ . '/../../bootstrap.php';
// $logger = $container->get(LoggerInterface::class);

class ArticlesLikesRepository implements ArticlesLikesRepositoryInterface
{
	public function __construct(private SQLite3 $db, private LoggerInterface $logger)
	{
	}
	public function getByPostUuid(string $uuid)
    {
		$statement = $this->db->prepare('SELECT * from articles_likes where article_uuid=:uuid');

		$statement->bindValue(':uuid', $uuid);

		$result = $statement->execute();


		if ($result === false) {
			throw new ArticleNotFoundException();
		}

		$result = $result->fetchArray(SQLITE3_ASSOC);

        $article_likes = [];

        foreach ($result as $article_like)
        {
            $article = new ArticleLike(
                id: $article_like["uuid"],
                article_id: $result["article_uuid"],
                user_id: $result["user_uuid"],
            );

            array_push($article_likes, $article);
        }


		return $article_likes;
        
    }

	public function getByPostAndUser(string $article_uuid, string $user_uuid)
    {
		$statement = $this->db->prepare('SELECT * from articles_likes where article_uuid=:article_uuid and user_uuid=:user_uuid');

		$statement->bindValue(':article_uuid', $article_uuid);
		$statement->bindValue(':user_uuid', $user_uuid);

		$result = $statement->execute();


		if ($result === false) {
			throw new ArticleNotFoundException();
		}

		$article_likes = [];
		while ($result_row = $result->fetchArray(SQLITE3_ASSOC)){


			$article = new ArticleLike(
				id: $result_row["uuid"],
				article_id: $result_row["article_uuid"],
				user_id: $result_row["user_uuid"],
			);

			array_push($article_likes, $article);

		}

		return $article_likes;
        
    }

	public function save(ArticleLike $articleLike)
	{
		$this->logger->warning(sprintf("saving article like %s", $articleLike->id));

		$result = $this->getByPostAndUser($articleLike->article_id, $articleLike->user_id);

		if ($result){
			throw new MoreThanOneLikeException();
		}

		$statement = $this->db->prepare(
			'INSERT INTO articles_likes (uuid, article_uuid, user_uuid)
			VALUES(:uuid, :article_uuid, :user_uuid)'
		);

		$statement->bindValue(':uuid', $articleLike->id);
		$statement->bindValue(':article_uuid',$articleLike->article_id);
		$statement->bindValue(':user_uuid', $articleLike->user_id);

		$statement->execute();
	}
}