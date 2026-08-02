<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the list of symptoms along with their search keywords as arrays
        $symptoms = [
            [
                'category' => 'General',
                'name' => 'Fever',
                'options' => ['Intermittent', 'Continuous'],
                'sort_order' => 1,
            ],
            [
                'category' => 'Respiratory',
                'name' => 'Cough', 
                'options' => ['Acute', 'Chronic', 'Intermittent', 'Persistant', 'Productive', 'Croup', 'Nocturnal', 'Non-Productive'],
                'sort_order' => 2,
            ],
            [
                'category' => 'Respiratory',
                'name' => 'Runny Nose / Respiratory Distress',
                'options' => null,
                'sort_order' => 3,
            ],
            [
                'category' => 'Gastrointestinal',
                'name' => 'Loose Motion', // ক্লিন নাম
                'options' => ['Wateray', 'Blood', 'Mucoid'],
                'sort_order' => 4,
            ],
            [
                'category' => 'Gastrointestinal',
                'name' => 'Abdominal Pain / Constipation / Distention',
                'options' => null,
                'sort_order' => 5,
            ],
            [
                'category' => 'Gastrointestinal',
                'name' => 'Altered bowel habit',
                'options' => null,
                'sort_order' => 6,
            ],
            [
                'category' => 'General',
                'name' => 'Pallour / Poor Appetite / Nausea',
                'options' => null,
                'sort_order' => 7,
            ],
            [
                'category' => 'General',
                'name' => 'Vomiting / Thrush / Epiphora',
                'options' => null,
                'sort_order' => 8,
            ],
            [
                'category' => 'Urological',
                'name' => 'Painful Micturation / Frequency / Dribbling',
                'options' => null,
                'sort_order' => 9,
            ],
            [
                'category' => 'General',
                'name' => 'Painful Swelling',
                'options' => ['Localized', 'Generalized'],
                'sort_order' => 10,
            ],
            [
                'category' => 'Neurological',
                'name' => 'Developmental Delay / Convulsion',
                'options' => null,
                'sort_order' => 11,
            ],
            [
                'category' => 'ENT',
                'name' => 'Oral Ulcer / Sore Throat',
                'options' => null,
                'sort_order' => 12,
            ],
            [
                'category' => 'ENT',
                'name' => 'Nasal Block / Mouth Breathing / Epistaxis',
                'options' => null,
                'sort_order' => 13,
            ],
        ];

        foreach ($symptoms as $item) {
            DB::table('symptoms')->updateOrInsert(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'category' => $item['category'],
                    'options' => $item['options'] ? json_encode($item['options']) : null,
                    'sort_order' => $item['sort_order'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}