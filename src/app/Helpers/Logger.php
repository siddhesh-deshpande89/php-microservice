<?php
namespace App\Helpers;

class Logger
{

    private static $logFile;

    /**
     * Debug Severity Log
     *
     * @param string $channel
     * @param string $message
     * @param array $context
     */
    public static function debug(string $channel, string $message, array $context = []): bool
    {
        return self::prepareLog($channel, 'DEBUG', $message, $context);
    }

    /**
     * Error Severity Log
     *
     * @param string $channel
     * @param string $message
     * @param array $context
     */
    public static function error(string $channel, string $message, array $context = []): bool
    {
        return self::prepareLog($channel, 'ERROR', $message, $context);
    }

    /**
     * Info Severity Log
     *
     * @param string $channel
     * @param string $message
     * @param array $context
     */
    public static function info(string $channel, string $message, array $context = []): bool
    {
        return self::prepareLog($channel, 'INFO', $message, $context);
    }
    
    /**
     * Warning Severity Log
     *
     * @param string $channel
     * @param string $message
     * @param array $context
     */
    public static function warning(string $channel, string $message, array $context = []): bool
    {
        return self::prepareLog($channel, 'WARNING', $message, $context);
    }

    /**
     * Prepare data for log
     *
     * @param string $channel
     * @param string $message
     * @param array $context
     */
    public function prepareLog(string $channel, string $severity, string $message, array $context = []): bool
    {
        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);

        return self::writeLog([
            'channel' => $channel,
            'message' => $message,
            'bt' => $bt,
            'severity' => $severity,
            'context' => $context
        ]);
    }

    /**
     * Create log file
     *
     * @param string $channel
     * @throws \Exception
     */
    protected static function createLogFile(string $channel)
    {
        $date = date('Y-m-d');
        $dir = dirname(__DIR__) . "/../storage/logs";
        self::$logFile = "$dir/{$channel}-{$date}.log";

        // Check if directory /logs exists
        if (! file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        // Create log file if it doesn't exist.
        if (! file_exists(self::$logFile)) {
            fopen(self::$logFile, 'w') or exit("Can't create {static::log_file}!");
        }

        // Check permissions of file.
        if (! is_writable(self::$logFile)) {
            // throw exception if not writable
            throw new \Exception("ERROR: Unable to write to file!", 1);
        }
    }

    /**
     * Write Log file
     *
     * @param array $params
     */
    protected function writeLog(array $params): bool
    {
        try {
            // Create Folder
            self::createLogFile($params['channel']);

            $time = date('Y-m-d H:i:s');
            $context = json_encode($params['context']);
            $caller = array_shift($params['bt']);
            $btLine = $caller['line'];
            $btPath = $caller['file'];

            $severityLog = is_null($params['severity']) ? "[N/A]" : "[{$params['severity']}]";
            $timeLog = is_null($time) ? "[N/A] " : "[{$time}] ";
            $messageLog = is_null($params['message']) ? "N/A" : "{$params['message']}";
            $contextLog = empty($params['context']) ? "" : "{$context}";
            $pathLog = is_null($btPath) ? "[N/A] " : "[{$btPath}] ";
            $lineLog = is_null($btLine) ? "[N/A] " : "[{$btLine}] ";

            $file = fopen(self::$logFile, 'a');
            fwrite($file, "{$severityLog} {$timeLog}: {$pathLog}{$lineLog} - {$messageLog} - [{$contextLog}] " . PHP_EOL);
            fclose($file);

            return true;
        } catch (\Exception $ex) {

            return false;
        }
    }
}