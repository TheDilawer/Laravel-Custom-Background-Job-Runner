<?php

namespace App\Console\Commands;

use App\CustomJobs\BackgroundJobRunner;
use Illuminate\Console\Command;

class RunJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run-job {className} {methodName} {parameters}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute a background job.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $className = $this->argument('className');
        $methodName = $this->argument('methodName');
        $parameters = json_decode($this->argument('parameters'), true);

        // Run the job
        $retries = env('BACKGROUND_JOB_RETRY_LIMIT', 3);
        $result = BackgroundJobRunner::execute($className, $methodName, $parameters,$retries);

        if ($result !== false) {
            $this->info("Job executed successfully.");
        } else {
            $this->error("Job execution failed.");
        }
    }
}
