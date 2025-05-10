<?php

namespace Database\Seeders;

use App\Models\Grado;
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
            Grado::create([
                'gra_id' => $grado[0],
                'gra_descripcion' => $grado[1],
                'niv_id' => $grado[2],
                'gra_estado' => $grado[3],
                'gra_is_delete' => $grado[4],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}
