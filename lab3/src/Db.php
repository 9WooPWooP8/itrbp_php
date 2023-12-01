<?php
namespace Lab;

use SQLite3;

class Db extends SQLite3
{
	function __construct($file = __DIR__ . '/test.db')
	{
		$this->open($file);
	}
}
