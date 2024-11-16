<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            [1, 'AMAZONAS'],
            [2, 'ANCASH'],
            [3, 'APURIMAC'],
            [4, 'AREQUIPA'],
            [5, 'AYACUCHO'],
            [6, 'CAJAMARCA'],
            [7, 'CALLAO'],
            [8, 'CUSCO'],
            [9, 'HUANCAVELICA'],
            [10, 'HUANUCO'],
            [11, 'ICA'],
            [12, 'JUNIN'],
            [13, 'LA LIBERTAD'],
            [14, 'LAMBAYEQUE'],
            [15, 'LIMA'],
            [16, 'LORETO'],
            [17, 'MADRE DE DIOS'],
            [18, 'MOQUEGUA'],
            [19, 'PASCO'],
            [20, 'PIURA'],
            [21, 'PUNO'],
            [22, 'SAN MARTIN'],
            [23, 'TACNA'],
            [24, 'TUMBES'],
            [25, 'UCAYALI'],
        ];

        foreach ($departamentos as $departamento) {
            DB::table('departamentos')->insert([
                'id' => $departamento[0],
                'nombre' => $departamento[1]
            ]);
        }
    }
}
