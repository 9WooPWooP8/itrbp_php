<?php
class Article
{
	public function __construct(
		string $id = "",
		string $author_id = "",
		string $title = "",
		string $text = ""
	) {
		$this->id = $id;
		$this->author_id = $author_id;
		$this->title = $title;
		$this->text = $text;
	}
	public string $id;
	public string $author_id;
	public string $title;
	public string $text;
}
