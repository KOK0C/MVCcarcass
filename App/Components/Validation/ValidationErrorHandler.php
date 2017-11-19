<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 19.11.2017
 * Time: 10:20
 */

namespace IhorRadchenko\App\Components\Validation;

class ValidationErrorHandler
{
    private $errors = [];

    public function addError(string $key, string $error)
    {
        $this->errors[$key][] = $error;
    }

    public function hasErrors(): bool
    {
        return (! empty($this->errors));
    }

    public function get(): array
    {
        return $this->errors;
    }
}