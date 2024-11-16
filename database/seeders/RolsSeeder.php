<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rols = [
            [1, 'Administrador', '1', '0'],
            [2, 'Director', '1', '0'],
            [3, 'Alumno', '1', '0'],
            [4, 'Docente', '1', '0'],
            [5, 'Auxiliar', '1', '0'],
            [6, 'Secretaria', '1', '0'],
            [7, 'Apoderado', '1', '0'],
        ];

        foreach ($rols as $rol) {
            DB::table('rols')->insert([
                'rol_id' => $rol[0],
                'rol_descripcion' => $rol[1],
                'rol_estado' => $rol[2],
                'is_deleted' => $rol[3],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}
