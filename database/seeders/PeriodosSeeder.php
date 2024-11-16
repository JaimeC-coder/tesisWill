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
                'id' => $periodo[0],
                'anio_id' => $periodo[1],
                'fecha_inicio' => $periodo[2],
                'fecha_fin' => $periodo[3],
                'fecha_fin_matricula' => $periodo[4],
                'estado' => $periodo[5],
                'created_at' => $periodo[6],
                'updated_at' => $periodo[7]
            ]);
        }
    }
}
