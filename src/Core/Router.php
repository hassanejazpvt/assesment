<?php

declare(strict_types=1);

/**
 * This whole file is written by me, this might seem a bit complex as it's handling the routing logic.
 */

namespace Hassan\Assesment\Core;

use ArgumentCountError;
use Exception;

class Router
{
    private static $routes = [];

    public static function get(string $route, string $controller, string $method): void
    {
        array_push(self::$routes, new Route('GET', $route, $controller, $method));
    }

    public static function post(string $route, string $controller, string $method): void
    {
        array_push(self::$routes, new Route('POST', $route, $controller, $method));
    }

    public static function put(string $route, string $controller, string $method): void
    {
        array_push(self::$routes, new Route('PUT', $route, $controller, $method));
    }

    public static function patch(string $route, string $controller, string $method): void
    {
        array_push(self::$routes, new Route('PATCH', $route, $controller, $method));
    }

    public static function delete(string $route, string $controller, string $method): void
    {
        array_push(self::$routes, new Route('DELETE', $route, $controller, $method));
    }

    public static function list(): array
    {
        return self::$routes;
    }

    /**
     * @throws Error
     */
    public static function run(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $pathInfo      = @explode('/', trim(($_SERVER['PATH_INFO'] ?: ""), '/'));
        if (empty($pathInfo[0])) {
            $pathInfo[0] = '/';
        }

        $args          = [];
        $matchedRoutes = self::findMatchedRoutes($requestMethod, $pathInfo, $args);
        $matchedRoute  = end($matchedRoutes);

        $controllerName = $matchedRoute ? $matchedRoute->getController() : null;
        $methodName     = $matchedRoute ? $matchedRoute->getMethod() : null;

        if (empty($matchedRoutes) || ! $matchedRoute || ! class_exists($controllerName) || (class_exists($controllerName) && ! method_exists($controllerName, $methodName))) {
            throw new Error('Error 404 - Not Found', 404);
        }

        $controller = new $controllerName();
        $args       = isset($args[$matchedRoute->getOgRoute()]) ? $args[$matchedRoute->getOgRoute()] : [];
        $args       = array_map(function ($arg) {
            return is_numeric($arg) ? (int) $arg : (string) $arg;
        }, $args);
        try {
            echo $controller->{$methodName}(new Request(), ...$args);
        } catch (Error $e) {
            throw $e;
        } catch (Exception|ArgumentCountError $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    private static function findMatchedRoutes(string $requestMethod, array $pathInfo, array &$args = []): array
    {
        return array_filter(self::$routes, static function ($route) use ($requestMethod, $pathInfo, &$args) {
            $typeMatched      = trim(strtoupper($route->getType())) == trim(strtoupper($requestMethod));
            $routeExploded    = array_map('trim', explode('/', $route->getOgRoute()));
            $pathInfoExploded = array_map('trim', explode('/', implode('/', $pathInfo)));
            $routeMatched     = count($routeExploded) == count($pathInfoExploded);
            $currentRouteArgs = [];
            foreach ($pathInfoExploded as $index => $pathInfoExplodedPart) {
                if (! empty($routeExploded[$index]) && strpos($routeExploded[$index], '{') !== false) {
                    $args[$route->getOgRoute()][] = $currentRouteArgs[] = $pathInfoExplodedPart;
                    continue;
                }
                if (! isset($routeExploded[$index]) || $pathInfoExplodedPart != $routeExploded[$index]) {
                    $routeMatched = false;
                    break;
                }
            }
            $argsChecked = count($currentRouteArgs) == count($route->getArgs());

            return $typeMatched && $routeMatched && $argsChecked;
        });
    }
}
