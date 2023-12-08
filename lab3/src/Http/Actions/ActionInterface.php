<?php

namespace Lab\Http\Actions;

use Lab\Http\Request;
use Lab\Http\Response;

interface ActionInterface
{
	public function handle(Request $request): Response;
}