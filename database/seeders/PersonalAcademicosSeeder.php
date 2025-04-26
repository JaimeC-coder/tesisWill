<?php

namespace Database\Seeders;

use App\Models\PersonalAcademico;
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
           $personal =  PersonalAcademico::create([
                'pa_id' => $personal_academico[0],
                'per_id' => $personal_academico[1],
                'pa_turno' => $personal_academico[2],
                'pa_condicion_laboral' => $personal_academico[3],
                'niv_id' => $personal_academico[4],
                'pa_especialidad' => $personal_academico[5],
                'rol_id' => $personal_academico[6],
                'pa_is_tutor' => $personal_academico[7],
                'is_deleted' => $personal_academico[8],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
            if ($personal->rol_id ==6 ) {
                $personal->persona->usuario->assignRole('Secretaria');
            }elseif ($personal->rol_id == 5) {
                $personal->persona->usuario->assignRole('Auxiliar');
            //  $user->assignRole('Docente');
            }elseif ($personal->rol_id == 4) {
                $personal->persona->usuario->assignRole('Docente');
            }elseif ($personal->rol_id == 2) {
                $personal->persona->usuario->assignRole('Director');
            }
        }
    }
}
