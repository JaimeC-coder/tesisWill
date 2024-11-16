<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institucions = [
            [1, '0444240-0', '0', 'PRIMARIA', '82857 Lives', 'PÚBLICA', '6', '194', '1844', 'AV. SAN MIGUEL S/N', 'DRE SAN MIGUEL', 'UGEL SAN MIGUEL', 'MIXTO', 'MAÑANA - TARDE', 'LUNES - VIERNES', 'PAUCAR IGNACIO, YMELDA AFRODICIA', '456789', NULL, NULL]
        ];

        foreach ($institucions as $institucion) {
            DB::table('institucions')->insert([
                'id' => $institucion[0],
                'ruc' => $institucion[1],
                'codigo_modular' => $institucion[2],
                'nivel' => $institucion[3],
                'nombre' => $institucion[4],
                'gestion' => $institucion[5],
                'departamento_id' => $institucion[6],
                'provincia_id' => $institucion[7],
                'distrito_id' => $institucion[8],
                'direccion' => $institucion[9],
                'dre' => $institucion[10],
                'ugel' => $institucion[11],
                'genero' => $institucion[12],
                'jornada' => $institucion[13],
                'dias' => $institucion[14],
                'director' => $institucion[15],
                'telefono' => $institucion[16],
                'created_at' => $institucion[17],
                'updated_at' => $institucion[18]
            ]);
        }

        }
}
