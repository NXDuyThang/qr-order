<?php
// Forward Vercel requests to normal index.php

if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL'])) {
    $storagePath = '/tmp/storage';
    $directories = [
        $storagePath . '/app/public',
        $storagePath . '/app/livewire-tmp',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/framework/testing',
        $storagePath . '/framework/views',
        $storagePath . '/logs',
    ];

    foreach ($directories as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}

require __DIR__ . '/../public/index.php';
