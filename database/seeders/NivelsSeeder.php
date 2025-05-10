<?php

namespace Database\Seeders;

use App\Models\Nivel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            $nivels = [
                [1, 'PRIMARIA']
            ];

            foreach ($nivels as $nivel) {
                 Nivel::create([
                    'niv_id' => $nivel[0],
                    'niv_descripcion' => $nivel[1]
                ]);
            }
    }
}
