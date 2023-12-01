<?php

namespace Lab\Exceptions;

use Exception;

class ArticleNotFoundException extends Exception {
  public function errorMessage() {
		$errorMsg = 'Article not found';

    return $errorMsg;
  }
}
