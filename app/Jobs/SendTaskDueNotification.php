<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Task;
use App\Mail\TaskDueNotification;
use Carbon\Carbon;

class SendTaskDueNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
    public function handle()
    {
        // Get all tasks that are due in the next 24 hours
        $tasks = Task::whereBetween('due_date', [now(), now()->addHours(24)])->get();

        // Send an email for each task
        foreach ($tasks as $task) {
            // Send the email to the user assigned to this task
            Mail::to($task->user->email)->send(new TaskDueNotification($task));
        }
    }
}
