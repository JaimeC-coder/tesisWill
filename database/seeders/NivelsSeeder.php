<?php

namespace Database\Seeders;

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
                DB::table('nivels')->insert([
                    'id' => $nivel[0],
                    'nombre' => $nivel[1]
                ]);
            }
    }
}
