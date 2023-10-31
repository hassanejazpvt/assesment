<?php

declare(strict_types=1);

use Hassan\Assesment\Controllers\CaptchaController;
use Hassan\Assesment\Controllers\FormsController;
use Hassan\Assesment\Core\Router;

Router::get('/', FormsController::class, 'index');
Router::get('forms/{id}', FormsController::class, 'view');
Router::post('forms/{id}/submit', FormsController::class, 'submit');
Router::get('forms/{id}/delete', FormsController::class, 'delete');
Router::get('forms', FormsController::class, 'index');
Router::post('api/forms', FormsController::class, 'store');
Router::get('captcha', CaptchaController::class, 'generate');
