<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Title;

class TitlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            [
                'name' => 'Newcomer',
                'min_complaints' => 1,
                'max_complaints' => 3,
                'color' => 'blue',
                'description' => 'Just starting to voice concerns'
            ],
            [
                'name' => 'Active Contributor',
                'min_complaints' => 4,
                'max_complaints' => 10,
                'color' => 'green',
                'description' => 'Regularly providing feedback'
            ],
            [
                'name' => 'Veteran Complainer',
                'min_complaints' => 11,
                'max_complaints' => null,
                'color' => 'purple',
                'description' => 'Experienced in identifying issues'
            ]
        ];

        foreach ($titles as $title) {
            Title::create($title);
        }
    }
}
