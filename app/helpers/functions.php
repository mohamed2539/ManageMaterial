<?php

if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('base_path')) {
    function base_path($path = '') {
        return __DIR__ . '/../../' . $path;
    }
}

if (!function_exists('public_path')) {
    function public_path($path = '') {
        return base_path('public/' . $path);
    }
}

if (!function_exists('config_path')) {
    function config_path($path = '') {
        return base_path('config/' . $path);
    }
}

if (!function_exists('storage_path')) {
    function storage_path($path = '') {
        return base_path('storage/' . $path);
    }
}

if (!function_exists('dd')) {
    function dd(...$vars) {
        foreach ($vars as $v) {
            var_dump($v);
        }
        die(1);
    }
} 