<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [1, 'Registro de Notas Anuales'],
            [2, 'Registro de Notas Bimestrales'],
            [3, 'Registro de Notas Trimestrales'],
            [4, 'Registro de Notas Semestrales'],

        ];

        foreach ($tipos as $tipo) {
            DB::table('tipos')->insert([
                'tp_id' => $tipo[0],
                'tp_tipo' => $tipo[1],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}