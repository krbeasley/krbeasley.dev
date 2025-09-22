<?php

namespace App\Logging;

class Logger
{
    private string $channelType;  // the type of channel
    private string $channelPath;  // the path of the channel

    /** Create a new Logger object
     *
     * @param string $channel
     * @throws LoggerException
     */
    private function __construct(string $channel) {
        // load the available channels
        $channelInfo = config('logging.channels');
        $channels = array_keys($channelInfo);

        if (!in_array($channel, $channels)) {
            throw new LoggerException("Channel: {$channel} does not exist");
        } else {
            $this->channelType = $channelInfo[$channel]['type'];
            $this->channelPath = dirname(__DIR__, 2) . "/" . $channelInfo[$channel]['path'];
        }
    }

    public static function channel(string $channel) : Logger|false
    {
        try {
            return new Logger($channel);
        } catch (LoggerException|\Exception $e) {
            Logger::loggerErrorFallback($e);
            return false;
        }
    }

    public function log(string $message, array $context = [], $level = "info") : void
    {
        if ($this->channelType === 'file') {
            $logFile = fopen($this->channelPath, "a+");
            // Get the timestamp for the log file
            $timestamp = date('Y-m-d H:i:s');

            // Write the log message
            $level = strtoupper($level);
            fwrite($logFile, "[$timestamp] local.$level $message ");

            // Attach the context to the log if it was included
            if (!empty($context)) {
                fwrite($logFile, json_encode($context));
            }
            fwrite($logFile, "\n");

            // Finally close the file when done writing.
            fclose($logFile);
        } else {
            // Todo: Handling for log streams not local files.
        }
    }

    private static function loggerErrorFallback(string $errMessage) : void
    {
        dd("ERROR FALLBACK $errMessage");
    }
}