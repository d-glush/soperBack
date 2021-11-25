<?php

namespace LoggerService;

class LoggerService {
    private CONST ALERT_MESSAGE = '!!!!!!';
    private CONST MESSAGES_SEPARATOR = '====================';

    static private string $logFilePath = DEFAULT_LOG_PATH;

    static public function log(string $filePath, string $content = '', bool $isAlert = false): bool
    {
        if ($filePath === '') {
            $filePath = LoggerService::$logFilePath;
        }
        $absoluteFilePath = $_SERVER['DOCUMENT_ROOT'] . PROJECT_DIR . $filePath;
        return file_put_contents(
            $absoluteFilePath,
            ($isAlert?LoggerService::ALERT_MESSAGE:'    ') .
            $content . PHP_EOL,
            FILE_APPEND);
    }

    static public function logStart(): bool
    {
        $sessId = session_id();
        return LoggerService::log(
            LoggerService::$logFilePath,
            PHP_EOL .
                PHP_EOL .
                LoggerService::MESSAGES_SEPARATOR . PHP_EOL .
                LoggerService::MESSAGES_SEPARATOR . PHP_EOL .
                (new \DateTime())->format('Y-m-d H:i:s:v') .
                " STARTED PHPSESSID = $sessId" . PHP_EOL,
            false,
            true
        );
    }

    static public function logEnd(): bool
    {
        $sessId = session_id();
        return LoggerService::log(
            LoggerService::$logFilePath,
            PHP_EOL .
                (new \DateTime())->format('Y-m-d H:i:s:v') .
                " ENDED PHPSESSID = $sessId" . PHP_EOL .
                LoggerService::MESSAGES_SEPARATOR . PHP_EOL .
                LoggerService::MESSAGES_SEPARATOR . PHP_EOL .
                PHP_EOL,
            false,
            true
        );
    }
}