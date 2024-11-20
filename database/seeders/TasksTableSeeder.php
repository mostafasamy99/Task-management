<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            [
                'title' => 'New Task One',
                'description' => 'Task description One',
                'due_date' => '2024-11-20',
                'status' => 'in_progress',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'New Task Two',
                'description' => 'Task description Two',
                'due_date' => '2024-11-22',
                'status' => 'pending',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'New Task Three',
                'description' => 'Task description Three',
                'due_date' => '2024-12-01',
                'status' => 'pending',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
