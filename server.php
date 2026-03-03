<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * This file is used as a router script for PHP's built-in web server.
 * It ensures ALL requests that don't match actual static files in /public
 * get routed through public/index.php (Laravel's front controller).
 *
 * Without this, PHP's built-in server returns 404 for URLs containing
 * file-like extensions (e.g., .png, .jpg) even when they should be
 * handled by Laravel routes.
 *
 * Usage: php -S 0.0.0.0:8080 server.php
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// If the requested URI points to an actual file in /public, serve it directly.
// This handles CSS, JS, images, and other static assets.
if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    $path = __DIR__ . '/public' . $uri;
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    // Set appropriate MIME type for common assets
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
    ];

    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
    }

    return false;
}

// Otherwise, route everything through Laravel's front controller.
require_once __DIR__ . '/public/index.php';
