<?php


interface ArticlesRepositoryInterface
{
	function get(string $uuid): Article;
	function save(Article $article);
}
