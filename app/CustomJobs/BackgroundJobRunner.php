<?php

namespace App\CustomJobs;

use Exception;
use Illuminate\Support\Facades\Log;

class BackgroundJobRunner
{
    public static function execute($className, $methodName, $parameters = [], $retries = 3)
    {
        try {
            // Validate class and method
            if (!class_exists($className)) {
                throw new Exception("Class $className does not exist.");
            }

            if (!method_exists($className, $methodName)) {
                throw new Exception("Method $methodName does not exist in class $className.");
            }

            // Log job start
            Log::info("Starting background job", [
                'class' => $className,
                'method' => $methodName,
                'parameters' => $parameters,
            ]);

            // Instantiate the class and call the method
            $classInstance = app($className);
            $result = call_user_func_array([$classInstance, $methodName], $parameters);

            // Log success
            Log::info("Background job completed successfully", [
                'class' => $className,
                'method' => $methodName,
                'parameters' => $parameters,
                'result' => $result,
            ]);

            return $result;
        } catch (Exception $e) {
            // Log error to a separate file
            Log::channel('background_errors')->error("Background job failed", [
                'class' => $className,
                'method' => $methodName,
                'parameters' => $parameters,
                'error' => $e->getMessage(),
            ]);

            // Retry logic
            if ($retries > 0) {
                Log::warning("Retrying background job", [
                    'class' => $className,
                    'method' => $methodName,
                    'parameters' => $parameters,
                    'remaining_retries' => $retries - 1,
                ]);

                return self::execute($className, $methodName, $parameters, $retries - 1);
            }

            return false;
        }
    }
}
