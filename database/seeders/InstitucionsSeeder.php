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
                'ie_id' => $institucion[0],
                'ie_codigo_modular' => $institucion[1],
                'ie_anexo' => $institucion[2],
                'ie_nivel' => $institucion[3],
                'ie_nombre' => $institucion[4],
                'ie_gestion' => $institucion[5],
                'ie_departamento' => $institucion[6],
                'ie_provincia' => $institucion[7],
                'ie_distrito' => $institucion[8],
                'ie_direccion' => $institucion[9],
                'ie_dre' => $institucion[10],
                'ie_ugel' => $institucion[11],
                'ie_genero' => $institucion[12],
                'ie_turno' => $institucion[13],
                'ie_dias_laborales' => $institucion[14],
                'ie_director' => $institucion[15],
                'ie_telefono' => $institucion[16],
                'ie_email' => $institucion[17],
                'ie_web' => $institucion[18],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }

        }
}
