<?php

declare(strict_types=1);

namespace Hassan\Assesment\Core;

class Request
{
    protected $request = [];
    protected $get     = [];
    protected $post    = [];
    protected $files   = [];
    protected $input   = [];

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->get     = $_GET;
        $this->post    = $_POST;
        $this->files   = $_FILES;
        $this->input   = json_decode(file_get_contents('php://input'), true) ?: [];
    }

    /**
     * @return string|array|null
     */
    public function get(string $key = null)
    {
        return $key ? ($this->get[$key] ?? null) : $this->get;
    }

    /**
     * @return string|array|null
     */
    public function post(string $key = null)
    {
        return $key ? ($this->post[$key] ?? null) : $this->post;
    }

    /**
     * @return string|array|null
     */
    public function files(string $key = null)
    {
        return $key ? ($this->files[$key] ?? null) : $this->files;
    }

    /**
     * @return string|array|null
     */
    public function input(string $key = null)
    {
        return $key ? ($this->input[$key] ?? null) : $this->input;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->files, $this->request, $this->input);
    }
}
