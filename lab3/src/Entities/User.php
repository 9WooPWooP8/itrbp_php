<?php
namespace Lab\Entities;

class User
{
	public function __construct(
		public string $id,
		public string $name,
		public string $surname,
	) {
	}
}

