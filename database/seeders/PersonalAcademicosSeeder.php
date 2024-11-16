<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalAcademicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $personal_academicos = [
            [49, 6, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'EDUCACION FISICA', 4, '1', '0'],
            [50, 7, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'COMUNICACIÓN', 4, '1', '0'],
            [51, 8, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'CIENCIAS Y TECNOLOGIA', 4, '1', '0'],
            [52, 9, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'PERSONAL AMBIENTE', 4, '1', '0'],
            [53, 10, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'ARTE Y CULTURA', 4, '1', '0'],
            [54, 11, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'FORMACION RELIGIOSA', 4, '1', '0'],
            [55, 12, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'MATEMATICA', 4, '1', '0'],
            [56, 13, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'MATEMATICA', 2, '1', '0'],
            [57, 14, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'CONTABILIDAD', 6, '2', '0'],
            [58, 15, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'AUXILIAR', 5, '2', '0'],
            [59, 1, 'MAÑANA-TARDE', 'CONTRATADO', 1, 'Programacion', 5, '2', '0'],
        ];

        foreach ($personal_academicos as $personal_academico) {
            DB::table('personal_academicos')->insert([
                'id' => $personal_academico[0],
                'persona_id' => $personal_academico[1],
                'jornada' => $personal_academico[2],
                'tipo' => $personal_academico[3],
                'institucion_id' => $personal_academico[4],
                'especialidad' => $personal_academico[5],
                'grado_id' => $personal_academico[6],
                'created_at' => $personal_academico[7],
                'updated_at' => $personal_academico[8]
            ]);
        }
    }
}
