<?php

class Cache {
    private static $instance = null;
    private $driver;
    private $prefix = 'cache_';
    private $defaultTTL = 3600; // ساعة واحدة

    private function __construct() {
        $this->driver = $_ENV['CACHE_DRIVER'] ?? 'file';
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key) {
        $cacheKey = $this->prefix . $key;
        
        switch ($this->driver) {
            case 'redis':
                return $this->getFromRedis($cacheKey);
            case 'memcached':
                return $this->getFromMemcached($cacheKey);
            default:
                return $this->getFromFile($cacheKey);
        }
    }

    public function set($key, $value, $ttl = null) {
        $cacheKey = $this->prefix . $key;
        $ttl = $ttl ?? $this->defaultTTL;
        
        switch ($this->driver) {
            case 'redis':
                return $this->setInRedis($cacheKey, $value, $ttl);
            case 'memcached':
                return $this->setInMemcached($cacheKey, $value, $ttl);
            default:
                return $this->setInFile($cacheKey, $value, $ttl);
        }
    }

    public function delete($key) {
        $cacheKey = $this->prefix . $key;
        
        switch ($this->driver) {
            case 'redis':
                return $this->deleteFromRedis($cacheKey);
            case 'memcached':
                return $this->deleteFromMemcached($cacheKey);
            default:
                return $this->deleteFromFile($cacheKey);
        }
    }

    public function clear() {
        switch ($this->driver) {
            case 'redis':
                return $this->clearRedis();
            case 'memcached':
                return $this->clearMemcached();
            default:
                return $this->clearFiles();
        }
    }

    private function getFromFile($key) {
        $file = $this->getCacheFilePath($key);
        if (!file_exists($file)) {
            return null;
        }

        $content = file_get_contents($file);
        $data = json_decode($content, true);

        if (!$data || (isset($data['expiry']) && $data['expiry'] < time())) {
            @unlink($file);
            return null;
        }

        return $data['value'];
    }

    private function setInFile($key, $value, $ttl) {
        $file = $this->getCacheFilePath($key);
        $data = [
            'value' => $value,
            'expiry' => time() + $ttl
        ];

        return file_put_contents($file, json_encode($data), LOCK_EX) !== false;
    }

    private function deleteFromFile($key) {
        $file = $this->getCacheFilePath($key);
        if (file_exists($file)) {
            return @unlink($file);
        }
        return true;
    }

    private function clearFiles() {
        $cacheDir = $this->getCacheDirectory();
        $files = glob($cacheDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
        return true;
    }

    private function getCacheFilePath($key) {
        return $this->getCacheDirectory() . '/' . md5($key);
    }

    private function getCacheDirectory() {
        $dir = __DIR__ . '/../../storage/cache';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }
} 