<?php

namespace Logger;

class Logger {
    private CONST ALERT_MESSAGE = '!!!!!!';

    private string $logFilePath = '';

    public function __construct(string $logFilePath)
    {
        $this->logFilePath = $logFilePath;
    }

    public function log(string $content, bool $isAlert = false, bool $isLogTime = false): bool
    {
        return file_put_contents($this->logFilePath,
            ($isAlert?Logger::ALERT_MESSAGE:'    ') .
            ($isLogTime?(new \DateTime())->format('Y-m-d H:i:s'):'') .
            $content . PHP_EOL);
    }
}