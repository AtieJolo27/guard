<?php
$public=realpath(__DIR__.'/../../public');
$path=realpath($public.'/'.rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
if ($path && str_starts_with($path,$public.DIRECTORY_SEPARATOR) && is_file($path) && pathinfo($path,PATHINFO_EXTENSION)!=='php') return false;
require __DIR__.'/../../vendor/autoload.php';
$app=require __DIR__.'/../../bootstrap/app.php';
$app->booted(fn()=>Illuminate\Support\Facades\Vite::useHotFile(__DIR__.'/../../tmp/playwright/disabled.hot'));
$app->handleRequest(Illuminate\Http\Request::capture());
