<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Recurso não encontrado',
                'error' => class_basename($e->getModel()) . ' não encontrado'
            ], 404);
        });

        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'Endpoint não encontrado',
                'error' => 'A rota solicitada não existe'
            ], 404);
        });

        $exceptions->render(function (\InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Dados inválidos',
                'error' => $e->getMessage()
            ], 400);
        });

        $exceptions->render(function (\Throwable $e) {
            if (config('app.debug')) return null;

            return response()->json([
                'message' => 'Erro interno do servidor',
                'error' => 'Ocorreu um erro inesperado'
            ], 500);
        });
    })->create();
