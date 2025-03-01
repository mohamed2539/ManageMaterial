<?php

class Logger {
    private static $logPath = __DIR__ . '/../../logs/';
    private static $dateFormat = 'Y-m-d H:i:s';

    private static function write($level, $message, $context = []) {
        $date = date(self::$dateFormat);
        $logFile = self::$logPath . date('Y-m-d') . '.log';
        
        // تحويل السياق إلى نص
        $contextString = empty($context) ? '' : ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);
        
        // تنسيق رسالة السجل
        $logMessage = "[$date] [$level] $message$contextString" . PHP_EOL;
        
        // إنشاء مجلد السجلات إذا لم يكن موجوداً
        if (!is_dir(self::$logPath)) {
            mkdir(self::$logPath, 0777, true);
        }
        
        // كتابة السجل
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    public static function error($message, $context = []) {
        self::write('ERROR', $message, $context);
    }

    public static function info($message, $context = []) {
        self::write('INFO', $message, $context);
    }

    public static function warning($message, $context = []) {
        self::write('WARNING', $message, $context);
    }

    public static function debug($message, $context = []) {
        if ($_ENV['APP_DEBUG'] === 'true') {
            self::write('DEBUG', $message, $context);
        }
    }

    public static function activity($userId, $action, $details = []) {
        $context = [
            'user_id' => $userId,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'details' => $details
        ];
        self::write('ACTIVITY', $action, $context);
    }

    public static function getRecentLogs($lines = 100) {
        $logFile = self::$logPath . date('Y-m-d') . '.log';
        if (!file_exists($logFile)) {
            return [];
        }

        $logs = [];
        $file = new SplFileObject($logFile, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key();
        
        $start = max(0, $totalLines - $lines);
        $file->seek($start);
        
        while (!$file->eof()) {
            $line = $file->fgets();
            if (trim($line)) {
                $logs[] = $line;
            }
        }

        return $logs;
    }
} 