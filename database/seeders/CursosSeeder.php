<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursos = [
            [1, 'Comunicación', 'COM', 5, 13, 1, NULL, '1', '0'],
            [2, 'Comunicación', 'COM', 5, 14, 1, NULL, '1', '0'],
            [3, 'Comunicación', 'COM', 5, 15, 1, NULL, '1', '0'],
            [4, 'Comunicación', 'COM', 9, 16, 1, NULL, '1', '0'],
            [5, 'Comunicación', 'COM', 5, 17, 1, NULL, '1', '0'],
            [6, 'Comunicación', 'COM', 5, 18, 1, NULL, '1', '0'],
            [7, 'Matemáticas', 'MAT', 5, 13, 1, NULL, '1', '0'],
            [8, 'Matemáticas', 'MAT', 5, 14, 1, NULL, '1', '0'],
            [9, 'Matemáticas', 'MAT', 5, 15, 1, NULL, '1', '0'],
            [10, 'Matemáticas', 'MAT', 5, 16, 1, NULL, '1', '0'],
            [11, 'Matemáticas', 'MAT', 5, 17, 1, NULL, '1', '0'],
            [12, 'Matemáticas', 'MAT', 5, 18, 1, NULL, '1', '0'],
            [13, 'Ciencias y Tecnología', 'CYT', 4, 13, 1, NULL, '1', '0'],
            [14, 'Ciencias y Tecnología', 'CYT', 4, 14, 1, NULL, '1', '0'],
            [15, 'Ciencias y Tecnología', 'CYT', 4, 15, 1, NULL, '1', '0'],
            [16, 'Ciencias y Tecnología', 'CYT', 4, 16, 1, NULL, '1', '0'],
            [17, 'Ciencias y Tecnología', 'CYT', 4, 17, 1, NULL, '1', '0'],
            [18, 'Ciencias y Tecnología', 'CYT', 4, 18, 1, NULL, '1', '0'],
            [19, 'Personal Social', 'PS', 4, 13, 1, NULL, '1', '0'],
            [20, 'Personal Social', 'PS', 4, 14, 1, NULL, '1', '0'],
            [21, 'Personal Social', 'PS', 4, 15, 1, NULL, '1', '0'],
            [22, 'Personal Social', 'PS', 4, 16, 1, NULL, '1', '0'],
            [23, 'Personal Social', 'PS', 4, 17, 1, NULL, '1', '0'],
            [24, 'Personal Social', 'PS', 4, 18, 1, NULL, '1', '0'],
            [26, 'Educación Religiosa', 'ER', 1, 13, 1, NULL, '1', '0'],
            [27, 'Educación Religiosa', 'ER', 1, 14, 1, NULL, '1', '0'],
            [28, 'Educación Religiosa', 'ER', 1, 15, 1, NULL, '1', '0'],
            [29, 'Educación Religiosa', 'ER', 1, 16, 1, NULL, '1', '0'],
            [30, 'Educación Religiosa', 'ER', 1, 17, 1, NULL, '1', '0'],
            [31, 'Educación Religiosa', 'ER', 1, 18, 1, NULL, '1', '0'],
            [32, 'Arte y Cultura', 'AC', 3, 13, 1, NULL, '1', '0'],
            [33, 'Arte y Cultura', 'AC', 3, 14, 1, NULL, '1', '0'],
            [34, 'Arte y Cultura', 'AC', 3, 15, 1, NULL, '1', '0'],
            [35, 'Arte y Cultura', 'AC', 3, 16, 1, NULL, '1', '0'],
            [36, 'Arte y Cultura', 'AC', 3, 17, 1, NULL, '1', '0'],
            [37, 'Arte y Cultura', 'AC', 3, 18, 1, NULL, '1', '0'],
            [38, 'Educación Física', 'EF', 3, 13, 1, NULL, '1', '0'],
            [39, 'Educación Física', 'EF', 3, 14, 1, NULL, '1', '0'],
            [40, 'Educación Física', 'EF', 3, 15, 1, NULL, '1', '0'],
            [41, 'Educación Física', 'EF', 3, 16, 1, NULL, '1', '0'],
            [42, 'Educación Física', 'EF', 3, 17, 1, NULL, '1', '0'],
            [43, 'Educación Física', 'EF', 3, 18, 1, NULL, '1', '0'],
            [44, 'Tutoria', 'Tut', 2, 13, 1, NULL, '1', '0'],
            [45, 'Tutoria', 'Tut', 2, 14, 1, NULL, '1', '0'],
            [46, 'Tutoria', 'Tut', 2, 15, 1, NULL, '1', '0'],
            [47, 'Tutoria', 'Tut', 2, 16, 1, NULL, '1', '0'],
            [48, 'Tutoria', 'Tut', 2, 17, 1, NULL, '1', '0'],
            [49, 'Tutoria', 'Tut', 2, 18, 1, NULL, '1', '0'],
            [50, 'COMPUTACIÓN', 'COMP', 2, 13, 1, NULL, '1', '0'],
            [51, 'COMPUTACIÓN', 'COMP', 2, 14, 1, NULL, '1', '0'],
            [52, 'COMPUTACIÓN', 'COMP', 2, 15, 1, NULL, '1', '0'],
            [53, 'COMPUTACIÓN', 'COMP', 2, 16, 1, NULL, '1', '0'],
            [54, 'COMPUTACIÓN', 'COMP', 2, 17, 1, NULL, '1', '0'],
            [55, 'COMPUTACIÓN', 'COMP', 2, 18, 1, NULL, '1', '0'],
        ];

        foreach ($cursos as $curso) {
            DB::table('cursos')->insert([
                'cur_id' => $curso[0],
                'cur_nombre' => $curso[1],
                'cur_abreviatura' => $curso[2],
                'cur_horas' => $curso[3],
                'gra_id' => $curso[4],
                'niv_id' => $curso[5],
                'per_id' => $curso[6],
                'cur_estado' => $curso[7],
                'is_deleted' => $curso[8],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}
