<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CleanupLogs implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (file_exists($logPath)) {
            // Clear the log file content
            file_put_contents($logPath, '');
            Log::info('Log file has been cleaned up at ' . now());
        }
    }
}
