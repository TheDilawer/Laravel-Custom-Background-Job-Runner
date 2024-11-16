<?php

use Illuminate\Support\Facades\Log;

/**
 * Runs a background job by calling the BackgroundJobRunner.
 *
 * @param string $className The fully-qualified class name.
 * @param string $methodName The method to execute.
 * @param array $parameters Parameters for the method.
 */
function runBackgroundJob(string $className, string $methodName, array $parameters = [], int $delaySeconds = 0)
{
    // Serialize parameters
    $params = escapeshellarg(json_encode($parameters));

    // Determine the PHP executable
    $phpBinary = PHP_BINARY;

    // Build the command
    $command = "{$phpBinary} artisan run-job {$className} {$methodName} {$params}";

    if ($delaySeconds > 0) {
        $command = "sleep {$delaySeconds} && {$command}";
    }

    // Run command in the background (OS-specific)
    if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
        // Windows
        $command = "timeout /t {$delaySeconds} & {$command}";
    } else {
        // Unix-based
        $command .= " > /dev/null 2>&1 &";
    }

    Log::info("Executing command: {$command}");

    // Execute the command
    try {
        exec($command);
        Log::info("Background job started", [
            'class' => $className,
            'method' => $methodName,
            'parameters' => $parameters,
        ]);
    } catch (Exception $e) {
        Log::error("Failed to start background job", [
            'class' => $className,
            'method' => $methodName,
            'parameters' => $parameters,
            'error' => $e->getMessage(),
        ]);
    }
}
