<?php

namespace Novuso\Test\Common\Doubles\Application\Logging;

use Novuso\Common\Application\Logging\Logger;

class InMemoryLogger implements Logger
{
    private $logs = [];
    private $levels = [];

    public function logs($level = null)
    {
        if ($level !== null) {
            if (!isset($this->levels[$level])) {
                return [];
            }

            return $this->levels[$level];
        }

        return $this->logs;
    }

    public function emergency($message, array $context = [])
    {
        $this->log('emergency', $message, $context);
    }

    public function alert($message, array $context = [])
    {
        $this->log('alert', $message, $context);
    }

    public function critical($message, array $context = [])
    {
        $this->log('critical', $message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->log('error', $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->log('warning', $message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->log('notice', $message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->log('info', $message, $context);
    }

    public function debug($message, array $context = [])
    {
        $this->log('debug', $message, $context);
    }

    private function log($level, $message, array $context)
    {
        $log = [
            'level'   => $level,
            'message' => $message,
            'context' => $context
        ];
        $this->logs[] = $log;
        $this->levels[$log['level']] = $log;
    }
}
