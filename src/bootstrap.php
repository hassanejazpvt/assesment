<?php

declare(strict_types=1);

namespace Hassan\Assesment;

use Hassan\Assesment\Core\DB;
use Hassan\Assesment\Core\Error;
use Hassan\Assesment\Core\Router;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;

class Bootstrap
{
    public function boot(): void
    {
        session_start();
        $this->loadCoreLibs();
        $this->initDatabase();
        $this->loadRoutes();
        $this->loadModels();
        $this->loadControllers();
    }

    public function run(): void
    {
        try {
            $this->boot();
            Router::run();
        } catch (Error $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new Error($e->getMessage(), $e->getCode());
        }
    }

    private function loadCoreLibs(): void
    {
        foreach (glob(SRC_PATH . '/Core/*.php') as $coreLib) {
            require $coreLib;
        }
    }

    private function initDatabase(): void
    {
        DB::connect();
    }

    private function loadRoutes(): void
    {
        require SRC_PATH . '/routes.php';
    }

    private function loadControllers(): void
    {
        $iti = new RecursiveDirectoryIterator(SRC_PATH . '/Controllers');
        foreach (new RecursiveIteratorIterator($iti) as $controller) {
            if ($controller->isDir()) {
                continue;
            }
            require $controller->getPathName();
        }
    }

    private function loadModels(): void
    {
        $iti = new RecursiveDirectoryIterator(SRC_PATH . '/Models');
        foreach (new RecursiveIteratorIterator($iti) as $controller) {
            if ($controller->isDir()) {
                continue;
            }
            require $controller->getPathName();
        }
    }
}
