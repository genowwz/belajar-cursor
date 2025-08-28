<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Pending',
                'color' => 'yellow',
                'description' => 'Complaint is waiting to be reviewed'
            ],
            [
                'name' => 'In Progress',
                'color' => 'blue',
                'description' => 'Complaint is being worked on'
            ],
            [
                'name' => 'Resolved',
                'color' => 'green',
                'description' => 'Complaint has been resolved'
            ]
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
