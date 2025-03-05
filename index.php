<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell
 */

$uri = $_SERVER['REQUEST_URI'];

// Check if the request is for the public folder, if not redirect it to the public folder
if (!str_contains($uri, '/public')) {
    header("Location: /public{$uri}");
    exit;
}

// Include the public/index.php file
require __DIR__.'/public/index.php';
