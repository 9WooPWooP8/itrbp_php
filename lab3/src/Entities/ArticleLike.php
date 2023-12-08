<?php
namespace Lab\Entities;

class ArticleLike
{
	public function __construct(
		public string $id,
		public string $user_id,
		public string $article_id,
	) {
	}
}
