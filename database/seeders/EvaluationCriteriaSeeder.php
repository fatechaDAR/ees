<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EvaluationCriteria;

class EvaluationCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criterias = [
            ['name' => 'Kerja Sama (Cooperation)', 'weight' => 1],
            ['name' => 'Disiplin (Discipline)', 'weight' => 1],
            ['name' => 'Tanggung Jawab (Responsibility)', 'weight' => 1],
        ];

        foreach ($criterias as $criteria) {
            EvaluationCriteria::firstOrCreate(
                ['name' => $criteria['name']],
                ['weight' => $criteria['weight']]
            );
        }
    }
}
