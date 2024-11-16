<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodos = [
            [1, 2, '2023-01-01', '2023-12-31', '2023-12-31', 3, '1', '0']
        ];

        foreach ($periodos as $periodo) {
            DB::table('periodos')->insert([
                'per_id' => $periodo[0],
                'anio_id' => $periodo[1],
                'per_inicio_matriculas' => $periodo[2],
                'per_final_matricula' => $periodo[3],
                'per_limite_cierre' => $periodo[4],
                'per_tp_notas' => $periodo[5],
                'per_estado' => $periodo[6],
                'is_deleted' => $periodo[7],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}
