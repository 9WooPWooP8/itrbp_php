<?php
class Product
{
	private $feedbacks;
	private $price;
	private $name;
	private $description;

	function get_feedbacks()
	{
		return $this->feedbacks;
	}

	function set_feedbacks($feedbacks)
	{
		$this->feedbacks = $feedbacks;
	}
	function get_price()
	{
		return $this->price;
	}

	function set_price($price)
	{
		$this->price = $price;
	}
	function get_name()
	{
		return $this->name;
	}

	function set_name($name)
	{
		$this->name = $name;
	}
	function get_description()
	{
		return $this->description;
	}

	function set_description($description)
	{
		$this->description = $description;
	}
}
