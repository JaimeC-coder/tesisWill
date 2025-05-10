<?php

namespace Database\Seeders;

use App\Models\AsignarGrado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignarGradosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asignarGrados = [
            [295, 52, 1, 13, 'A', '1'],
            [296, 52, 1, 18, 'A', '0'],
            [297, 54, 1, 17, 'A', '0'],
            [298, 53, 1, 15, 'A', '0'],
            [299, 49, 1, 14, 'A', '0'],
            [300, 55, 1, 17, 'B', '0'],
            [301, 51, 1, 13, 'A', '0'],
            [302, 50, 1, 16, 'A', '0']
        ];

        foreach ($asignarGrados as $asignarGrado) {
             AsignarGrado::create([
                'asig_id' => $asignarGrado[0],
                'pa_id' => $asignarGrado[1],
                'niv_id' => $asignarGrado[2],
                'gra_id' => $asignarGrado[3],
                'seccion' => $asignarGrado[4],
                'asig_is_deleted' => $asignarGrado[5],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}
