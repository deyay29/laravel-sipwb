<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        // Tamu diarahkan ke satu-satunya halaman login (admin).
        $middleware->redirectGuestsTo('/login');

        // Situs dijalankan di belakang reverse proxy (Nginx / Cloudflare),
        // sehingga Laravel tidak otomatis tahu kalau request aslinya HTTPS.
        // Tanpa ini, semua link yang dibuat Laravel memakai http:// dan
        // pengunjung terlempar keluar dari HTTPS.
        //
        // Hanya jaringan privat & loopback yang dipercaya. Header proxy dari
        // koneksi internet tidak bisa meniru nilai ini, jadi tidak membuka
        // celah spoofing X-Forwarded-For.
        $middleware->trustProxies(
            at: [
                '127.0.0.1',
                '::1',
                '10.0.0.0/8',
                '172.16.0.0/12',
                '192.168.0.0/16',
            ],
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_AWS_ELB,
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
