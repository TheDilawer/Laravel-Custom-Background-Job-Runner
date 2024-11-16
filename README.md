
# Laravel Custom Background Job Runner

## Overview
A custom system for executing PHP classes as background jobs in Laravel, independent of Laravel's built-in queue system. Supports delays, retries, and detailed logging.

---

## Features
- Executes PHP classes/methods in the background.
- Compatible with Unix and Windows systems.
- Supports job delays and retries.
- Logs job execution status (running, completed, failed) and errors.
- Enforces security with a whitelist of allowed classes.

---

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/TheDilawer/Laravel-Custom-Background-Job-Runner
   cd background-job-runner

2. Install dependencies:
   ```bash
   composer install
   
3. Configure the environment:
   ```bash
    cp .env.example .env
    php artisan key:generate

4. Update logging configuration in config/logging.php to include:
    ```php
    'background_errors' => [
    'driver' => 'single',
    'path' => storage_path('logs/background_jobs_errors.log'),
    'level' => 'error',
    ],
    ```

5. Dump autoload to register the helpers:
    ```bash
   composer dump-autoload

## Usage

### Running a Background Job

Use the `runBackgroundJob` helper to execute a background job:
 ```php
 runBackgroundJob(App\CustomJobs\DummyJob::class, 'sayHello', ['Laravel']);
 ```

### Adding a Delay

Run a background job after a delay:
 ```php
runBackgroundJob(App\CustomJobs\DummyJob::class, 'sayHello', ['Laravel'], 5);
 ```

## Configurations
- Retry Attempts: Set the retry limit in `.env`:
    ```bash
    BACKGROUND_JOB_RETRY_LIMIT=3

- Allowed Classes: Specify allowed classes in `config/background_jobs.php`:
     ```php
    return [
        'allowed_classes' => [
            \App\CustomJobs\DummyJob::class,
        ],
    ];
     ```
## Testing
1. Test the job execution by visiting `/test-job`:
     ```php
    Route::get('/test-job', function () {
    runBackgroundJob(App\CustomJobs\DummyJob::class, 'sayHello', ['Laravel']);
    });
     ```
2. Check logs:

   - `storage/logs/laravel.log`: General logs.
   - `storage/logs/background_jobs_errors.log`: Error logs.

   
