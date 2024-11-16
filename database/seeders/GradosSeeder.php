<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grados = [
            [13, 'Primero', 1, '1', '0'],
            [14, 'Segundo', 1, '1', '0'],
            [15, 'Tercero', 1, '1', '0'],
            [16, 'Cuarto', 1, '1', '0'],
            [17, 'Quinto', 1, '1', '0'],
            [18, 'Sexto', 1, '1', '0'],
        ];

        foreach ($grados as $grado) {
            DB::table('grados')->insert([
                'id' => $grado[0],
                'nombre' => $grado[1],
                'nivel_id' => $grado[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
