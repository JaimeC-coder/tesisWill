<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignarCursosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asignarCursos = [
            [1, 58, 1, 'Arte y Cultura', '0'],
            [2, 58, 1, 'Ciencias y Tecnología', '0'],
            [3, 58, 1, 'Comunicación', '0'],
            [4, 58, 1, 'Educación Física', '0'],
            [5, 58, 1, 'Formación Religiosa', '0'],
            [6, 58, 1, 'Matemáticas', '0'],
            [7, 58, 1, 'Personal Ambiente', '0'],
            [8, 52, 1, 'Arte y Cultura', '0'],
            [9, 52, 1, 'Ciencias y Tecnología', '0'],
            [10, 52, 1, 'Comunicación', '0'],
            [11, 52, 1, 'Educación Física', '0'],
            [12, 54, 1, 'Arte y Cultura', '0'],
            [13, 54, 1, 'Ciencias y Tecnología', '0'],
            [14, 53, 1, 'Arte y Cultura', '0'],
            [15, 53, 1, 'Ciencias y Tecnología', '0'],
            [16, 49, 1, 'Arte y Cultura', '0'],
            [17, 55, 1, 'Arte y Cultura', '0'],
            [18, 51, 1, 'Arte y Cultura', '0'],
            [19, 50, 1, 'Arte y Cultura', '0'],
            [20, 50, 1, 'Comunicación', '0'],
            [21, 51, 1, 'Comunicación', '0'],
            [22, 49, 1, 'Educación Física', '0'],
            [23, 52, 1, 'Formación Religiosa', '0'],
            [24, 52, 1, 'Matemáticas', '0'],
            [25, 52, 1, 'Personal Ambiente', '0'],
            [26, 54, 1, 'Comunicación', '0'],
            [27, 54, 1, 'Educación Física', '0'],
            [28, 54, 1, 'Formación Religiosa', '0'],
            [29, 54, 1, 'Matemáticas', '0'],
            [30, 54, 1, 'Personal Ambiente', '0'],
            [31, 53, 1, 'Comunicación', '0'],
            [32, 53, 1, 'Educación Física', '0'],
            [33, 53, 1, 'Formación Religiosa', '0'],
            [34, 53, 1, 'Matemáticas', '0'],
            [35, 53, 1, 'Personal Ambiente', '0'],
            [36, 49, 1, 'Ciencias y Tecnología', '0'],
            [37, 49, 1, 'Comunicación', '0'],
            [38, 49, 1, 'Formación Religiosa', '0'],
            [39, 49, 1, 'Matemáticas', '0'],
            [40, 49, 1, 'Personal Ambiente', '0'],
            [41, 55, 1, 'Ciencias y Tecnología', '0'],
            [42, 55, 1, 'Comunicación', '0'],
            [43, 55, 1, 'Educación Física', '0'],
            [44, 55, 1, 'Formación Religiosa', '0'],
            [45, 55, 1, 'Matemáticas', '0'],
            [46, 55, 1, 'Personal Ambiente', '0'],
            [47, 51, 1, 'Ciencias y Tecnología', '0'],
            [48, 51, 1, 'Educación Física', '0'],
            [49, 51, 1, 'Formación Religiosa', '0'],
            [50, 51, 1, 'Matemáticas', '0'],
            [51, 51, 1, 'Personal Ambiente', '0'],
            [52, 50, 1, 'Ciencias y Tecnología', '0'],
            [53, 50, 1, 'Educación Física', '0'],
            [54, 50, 1, 'Formación Religiosa', '0'],
            [55, 50, 1, 'Matemáticas', '0'],
            [56, 50, 1, 'Personal Ambiente', '0'],
            [57, 52, 1, 'Educación Religiosa', '0'],
            [58, 52, 1, 'Personal Social', '0'],
            [59, 54, 1, 'Educación Religiosa', '0'],
            [60, 54, 1, 'Personal Social', '0'],
            [61, 53, 1, 'Educación Religiosa', '0'],
            [62, 53, 1, 'Personal Social', '0'],
            [63, 49, 1, 'Educación Religiosa', '0'],
            [64, 49, 1, 'Personal Social', '0'],
            [65, 55, 1, 'Educación Religiosa', '0'],
            [66, 55, 1, 'Personal Social', '0'],
            [67, 51, 1, 'Educación Religiosa', '0'],
            [68, 51, 1, 'Personal Social', '0'],
            [69, 50, 1, 'Educación Religiosa', '0'],
            [70, 50, 1, 'Personal Social', '0'],
            [71, 52, 1, 'COMPUTACIÓN', '0'],
            [72, 52, 1, 'Tutoria', '0'],
            [73, 54, 1, 'COMPUTACIÓN', '0'],
            [74, 54, 1, 'Tutoria', '0'],
            [75, 53, 1, 'COMPUTACIÓN', '0'],
            [76, 53, 1, 'Tutoria', '0'],
            [77, 49, 1, 'COMPUTACIÓN', '0'],
            [78, 49, 1, 'Tutoria', '0'],
            [79, 55, 1, 'COMPUTACIÓN', '0'],
            [80, 55, 1, 'Tutoria', '0'],
            [81, 51, 1, 'COMPUTACIÓN', '0'],
            [82, 51, 1, 'Tutoria', '0'],
            [83, 50, 1, 'COMPUTACIÓN', '0'],
            [84, 50, 1, 'Tutoria', '0'],
        ];

        foreach ($asignarCursos as $asignarCurso) {
            DB::table('asignar_cursos')->insert([
                'id' => $asignarCurso[0],
                'curso_id' => $asignarCurso[1],
                'seccion_id' => $asignarCurso[2],
                'asignatura' => $asignarCurso[3],
                'estado' => $asignarCurso[4],
            ]);
        }
    }
}
