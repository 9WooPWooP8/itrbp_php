<?php

namespace Lab\Exceptions;

use Exception;

class CommentNotFoundException extends Exception {
  public function errorMessage() {
		$errorMsg = 'Comment not found';

    return $errorMsg;
  }
}
