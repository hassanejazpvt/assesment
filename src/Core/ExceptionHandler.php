<?php

namespace Hassan\Assesment\Core;

class ExceptionHandler
{
    private $exception;

    public function __construct(Error $e)
    {
        $this->exception = $e;
    }

    public function __invoke()
    {
        if ($this->exception->getCode() == 404) {
            header('Not Found', true, 404);
        } elseif ($this->exception->getCode() == 422) {
            echo Response::json([
                'status'  => 0,
                'message' => $this->exception->getMessage(),
                'errors'  => $this->exception->getErrors(),
                'trace'   => $this->exception->getTraceAsString(),
            ], 422);
        } else {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH']) {
                echo Response::json([
                    'status'  => 0,
                    'message' => $this->exception->getMessage(),
                    'errors'  => $this->exception->getErrors(),
                    'trace'   => $this->exception->getTraceAsString(),
                ], 500);
            } else {
                echo view('errors/exception.php', ['e' => $this->exception]);
            }
        }
    }
}
