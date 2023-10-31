<?php

declare(strict_types=1);

namespace Hassan\Assesment\Core;

class Validator
{
    protected $data   = [];
    protected $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $name, string $message = null): self
    {
        if (! isset($this->data[$name]) || empty($this->data[$name])) {
            $this->errors[$name][] = $message ?? "The $name field is required.";
        }

        return $this;
    }

    public function inList(string $name, array $list, string $message = null): self
    {
        if (! isset($this->data[$name]) || ! in_array($this->data[$name], $list)) {
            $this->errors[$name][] = $message ?? "The $name field is not in the list [" . implode(',', $list) . '].';
        }

        return $this;
    }

    /**
     * @param string|null $message
     */
    public function matchList(string $name, array $list, $message = null): self
    {
        if (isset($this->data[$name])) {
            foreach ($this->data[$name] as $listItem) {
                if (! in_array(preg_replace('/:.+$/', ':x', $listItem), $list)) {
                    $this->errors[$name][] = $message ?? "The $name field list does not match with the list [" . implode(',', $list) . '].';
                    break;
                }
            }
        } else {
            $this->errors[$name][] = $message ?? "The $name field list does not match with the list [" . implode(',', $list) . '].';
        }

        return $this;
    }

    public function email(string $name, string $message = null): self
    {
        if (! isset($this->data[$name]) || ! filter_var($this->data[$name], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$name][] = $message ?? "The $name must be a valid email address.";
        }

        return $this;
    }

    public function number(string $name, string $message = null): self
    {
        if (! isset($this->data[$name]) || ! filter_var($this->data[$name], FILTER_VALIDATE_INT)) {
            $this->errors[$name][] = $message ?? "The $name must be a number.";
        }

        return $this;
    }

    public function minLength(string $name, int $length, string $message = null): self
    {
        if (! isset($this->data[$name]) || strlen($this->data[$name]) < $length) {
            $this->errors[$name][] = $message ?? "The $name must be at least $length characters.";
        }

        return $this;
    }

    public function maxLength(string $name, int $length, string $message = null): self
    {
        if (! isset($this->data[$name]) || strlen($this->data[$name]) > $length) {
            $this->errors[$name][] = $message ?? "The $name may not be greater than $length characters.";
        }

        return $this;
    }

    public function min(string $name, int $value, string $message = null): self
    {
        if (! isset($this->data[$name]) || $this->data[$name] < $value) {
            $this->errors[$name][] = $message ?? "The $name must be at least $value.";
        }

        return $this;
    }

    public function max(string $name, int $value, string $message = null): self
    {
        if (! isset($this->data[$name]) || $this->data[$name] > $value) {
            $this->errors[$name][] = $message ?? "The $name may not be greater than $value.";
        }

        return $this;
    }

    public function pattern(string $name, string $pattern, string $message = null): self
    {
        if (! isset($this->data[$name]) || ! preg_match("/$pattern/", $this->data[$name])) {
            $this->errors[$name][] = $message ?? "The $name format is invalid.";
        }

        return $this;
    }

    /**
     * @throws Error
     *
     * @return void|bool
     */
    public function validate(bool $quiet = false)
    {
        if ($quiet) {
            return count($this->errors()) == 0;
        }

        if (count($this->errors())) {
            throw new Error('Validation failed.', 422, $this->errors());
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
