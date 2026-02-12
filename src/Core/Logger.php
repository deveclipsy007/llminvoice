<?php

declare(strict_types=1);

namespace App\Core;

class Logger
{
    /**
     * Log an audit event to the database.
     * Fail-safe: if DB write fails, log to error file instead.
     */
    public static function audit(
        string $action,
        string $entityType = '',
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): void {
        try {
            Database::insert('audit_logs', [
                'user_id'     => Session::userId(),
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'old_values'  => $oldValues ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
                'new_values'  => $newValues ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
                'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            self::error("Audit log failed: {$e->getMessage()}", [
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
            ]);
        }
    }

    public static function error(string $message, array $context = []): void
    {
        self::writeToFile('error.log', 'ERROR', $message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::writeToFile('app.log', 'INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::writeToFile('app.log', 'WARNING', $message, $context);
    }

    private static function writeToFile(string $filename, string $level, string $message, array $context): void
    {
        try {
            $logDir = App::basePath('storage/logs');
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0775, true);
            }

            $logFile = $logDir . DIRECTORY_SEPARATOR . $filename;
            $timestamp = date('Y-m-d H:i:s');
            $contextStr = $context ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
            $line = "[{$timestamp}] {$level}: {$message}{$contextStr}" . PHP_EOL;

            @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
        } catch (\Throwable) {
            // Last resort: silently fail
        }
    }
}
