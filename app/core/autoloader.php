<?php
// Autoloader for PSR-4
spl_autoload_register(function ($class) {
    // Define namespaces and directories
    $prefixes = [
        'app\\controllers\\' => __DIR__ . '/../controllers/', // All lowercase
        'app\\models\\' => __DIR__ . '/../models/',           // All lowercase
        'app\\core\\' => __DIR__ . '/',                      // All lowercase
        'app\\config\\' => __DIR__ . '/../config/'            // All lowercase
    ];

    // Check if the class matches any of the defined namespaces
    foreach ($prefixes as $namespace => $base_dir) {
        $len = strlen($namespace);
        if (strncmp($namespace, $class, $len) === 0) {
            // Remove the namespace prefix
            $relative_class = substr($class, $len);

            // Replace namespace separators with directory separators
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // Include the file if it exists
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
});