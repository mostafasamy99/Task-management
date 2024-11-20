<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Jobs\SendTaskDueNotification;
use Carbon\Carbon;

class NotifyDueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify-due-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users about tasks due within the next 24 hours.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Dispatch the job
        SendTaskDueNotification::dispatch();

        $this->info('Task notification job dispatched.');
    }
}
