<?php

namespace Lab\Interfaces;

use Lab\Entities\ArticleLike;

interface ArticlesLikesRepositoryInterface
{
	function getByPostUuid(string $uuid);
	function save(ArticleLike $articleLike);
}

