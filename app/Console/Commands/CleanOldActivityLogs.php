<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use Carbon\Carbon;

class CleanOldActivityLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activitylog:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete activity logs older than 12 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = ActivityLog::where('created_at', '<', Carbon::now()->subHours(12))->delete();

        $this->info("Deleted {$deleted} old activity logs.");

        return 0;
    }
}
