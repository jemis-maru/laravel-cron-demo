<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CleanupLogs;

class CleanupLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch a job to clean up log files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching log cleanup job to queue...');
        
        // Dispatch the job to the queue
        CleanupLogs::dispatch();
        
        $this->info('Log cleanup job dispatched successfully!');
        
        return Command::SUCCESS;
    }
}
