<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tests = [
            [
                'name' => 'Complete Blood Count (CBC)',
                'code' => 'CBC',
                'description' => 'Measures several components and features of your blood.',
                'is_active' => true,
            ],
            [
                'name' => 'Random Blood Sugar (RBS)',
                'code' => 'RBS',
                'description' => 'Measures the amount of glucose in the blood at any given time.',
                'is_active' => true,
            ],
            [
                'name' => 'Serum Creatinine',
                'code' => 'CREAT',
                'description' => 'Evaluates kidney function by measuring creatinine levels.',
                'is_active' => true,
            ],
            [
                'name' => 'Lipid Profile',
                'code' => 'LIPID',
                'description' => 'Measures cholesterol and triglyceride levels.',
                'is_active' => true,
            ],
            [
                'name' => 'Liver Function Test (LFT)',
                'code' => 'LFT',
                'description' => 'Checks the health of your liver by measuring specific enzymes and proteins.',
                'is_active' => true,
            ],
            [
                'name' => 'Urine R/M/E',
                'code' => 'URINE',
                'description' => 'Routine examination of urine sample for infection or other issues.',
                'is_active' => true,
            ],
            [
                'name' => 'X-Ray Chest PA View',
                'code' => 'XRAY-C',
                'description' => 'Imaging test of the chest to check lungs and heart.',
                'is_active' => true,
            ],
            [
                'name' => 'Ultrasonography (USG) of Whole Abdomen',
                'code' => 'USG-WA',
                'description' => 'Imaging test to examine organs inside the abdominal cavity.',
                'is_active' => true,
            ],
        ];

        // Comment: Insert tests safely using updateOrCreate to avoid duplicate entry errors
        foreach ($tests as $test) {
            DB::table('tests')->updateOrInsert(
                ['name' => $test['name']], // Unique check
                [
                    'code' => $test['code'],
                    'description' => $test['description'],
                    'is_active' => $test['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
