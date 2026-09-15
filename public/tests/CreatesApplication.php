<?php
namespace Tests;
trait CreatesApplication { public function createApplication() { putenv('APP_CONFIG_CACHE=bootstrap/cache/config-testing.php'); $_ENV['APP_CONFIG_CACHE']='bootstrap/cache/config-testing.php'; $_SERVER['APP_CONFIG_CACHE']='bootstrap/cache/config-testing.php'; $app = require __DIR__.'/../bootstrap/app.php'; $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap(); return $app; } }
