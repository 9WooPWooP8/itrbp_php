<?php

namespace Lab\Exceptions;

use Exception;

class MoreThanOneLikeException extends Exception {
  public function errorMessage() {
		$errorMsg = 'User can like article only once';

    return $errorMsg;
  }
}

