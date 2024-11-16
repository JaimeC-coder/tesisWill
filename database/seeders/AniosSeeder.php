<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AniosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anios = [
            [1, '2022', '2022-03-01', '2022-12-31', '45 min', '15 min', 50, '2', '0', '0'],
            [2, '2023', '2023-03-01', '2023-12-31', '45 min', '15 min', 50, '2', '1', '0'],
        ];

        foreach ($anios as $anio) {
            DB::table('anios')->insert([
                'id' => $anio[0],
                'anio' => $anio[1],
                'fecha_inicio' => $anio[2],
                'fecha_fin' => $anio[3],
                'hora_clase' => $anio[4],
                'hora_recreo' => $anio[5],
                'tiempo_receso' => $anio[6],
                'estado' => $anio[7],
                'activo' => $anio[8],
                'eliminado' => $anio[9]
            ]);
        }










    }
}
