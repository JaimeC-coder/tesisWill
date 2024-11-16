<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeccionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seccions = [
            [50, 'A', 51, 35, 13, 1, 5, '0'],
            [51, 'A', 49, 36, 14, 1, 3, '0'],
            [52, 'A', 53, 37, 15, 1, 11, '0'],
            [53, 'A', 50, 38, 16, 1, 9, '0'],
            [54, 'A', 54, 39, 17, 1, 8, '0'],
            [55, 'B', 55, 40, 17, 1, 7, '0'],
            [56, 'A', 52, 41, 18, 1, 5, '0'],
          ];

        foreach ($seccions as $seccion) {
            DB::table('seccions')->insert([
                'id' => $seccion[0],
                'nombre' => $seccion[1],
                'grado_id' => $seccion[2],
                'aula_id' => $seccion[3],
                'personal_academico_id' => $seccion[4],
                'institucion_id' => $seccion[5],
                'nivel_id' => $seccion[6],
                'estado' => $seccion[7]
            ]);
        }
    }
}
