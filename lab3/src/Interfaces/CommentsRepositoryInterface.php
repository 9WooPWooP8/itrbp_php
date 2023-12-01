<?php

namespace Lab\Interfaces;

use Lab\Entities\Comment;

interface CommentsRepositoryInterface
{
	function get(string $uuid): Comment;
	function save(Comment $comment);
}
