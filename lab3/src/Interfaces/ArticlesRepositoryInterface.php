<?php

namespace Lab\Interfaces;

use Lab\Entities\Article;

interface ArticlesRepositoryInterface
{
	function get(string $uuid): Article;
	function save(Article $article);
}
