<?php
namespace Lab;

use SQLite3;

class Db extends SQLite3
{
	function __construct()
	{
		$this->open(__DIR__ . '/../test.db');
	}
}
