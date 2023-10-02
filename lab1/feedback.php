<?php

class Feedback
{
	private $text;
	private $owner;
	private $rating;
	function get_text()
	{
		return $this->text;
	}

	function set_text($text)
	{
		$this->text = $text;
	}
	function get_owner()
	{
		return $this->owner;
	}

	function set_owner($owner)
	{
		$this->owner = $owner;
	}
	function get_rating()
	{
		return $this->rating;
	}

	function set_rating($rating)
	{
		$this->rating = $rating;
	}
}
