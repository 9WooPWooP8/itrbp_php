<?php


interface CommentsRepositoryInterface
{
	function get(string $uuid): Comment;
	function save(Comment $comment);
}
