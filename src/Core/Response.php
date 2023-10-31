<?php

declare(strict_types=1);

namespace Hassan\Assesment\Core;

class Response
{
    public static function json(array $data, int $status = 200): string
    {
        header('Content-Type: application/json');
        http_response_code($status);

        return json_encode($data);
    }

    public static function view(string $name, array $data = []): string
    {
        ob_start();
        extract($data);
        include SRC_PATH . '/Views/' . $name;
        $content = ob_get_clean();

        return $content;
    }

    public static function show404(): void
    {
        header('Not Found', true, 404);
        exit;
    }

    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        die();
    }
}
