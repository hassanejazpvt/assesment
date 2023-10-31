<?php

declare(strict_types=1);

namespace Hassan\Assesment\Core;

use Exception;

class Error extends Exception
{
    protected $errors = [];

    public function __construct(string $message, int $code, array $errors = [])
    {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
