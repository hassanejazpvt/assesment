<?php

declare(strict_types=1);

use Hassan\Assesment\Core\Response;

function asset(string $assetPath): string
{
    return rtrim(env('APP_URL'), '/') . "/public/{$assetPath}";
}

function url(string $url = ''): string
{
    return env('APP_URL') . $url;
}

function env(string $key, string $default = null)
{
    return $_ENV[$key] ?: $default;
}

function only(array $array, array $keys): array
{
    return array_intersect_key($array, array_flip($keys));
}

function except(array $array, array $keys): array
{
    return array_diff_key($array, array_flip($keys));
}

function view(string $name, array $data = []): string
{
    return Response::view($name, $data);
}

function show404(): void
{
    Response::show404();
}

function redirect(string $url): void
{
    Response::redirect($url);
}
