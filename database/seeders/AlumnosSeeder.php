<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumnosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumnos = [
            [1, 2, 1, NULL, NULL, '1', '0'],
            [2, 4, 2, NULL, NULL, '1', '0'],
            [3, 16, 3, NULL, NULL, '1', '0'],
            [4, 18, 4, NULL, NULL, '1', '0'],
            [5, 20, 5, NULL, NULL, '1', '0'],
            [6, 23, 6, NULL, NULL, '1', '0'],
            [7, 25, 7, NULL, NULL, '1', '0'],
            [8, 27, 8, NULL, NULL, '1', '0'],
            [9, 29, 9, NULL, NULL, '1', '0'],
            [10, 31, 10, NULL, NULL, '1', '0'],
            [11, 33, 11, NULL, NULL, '1', '0'],
            [12, 38, 79, NULL, NULL, '1', '0'],
            [13, 41, 12, NULL, 'FICHA DE MATRICULA - GRISELY VICTORIA ALCANTARA ESPINOZA.pdf', '1', '0'],
            [14, 43, 13, 'LIBRETA DE NOTAS - THIAGO DOMINICIANO ALCANTARA MALCA.pdf', NULL, '1', '0'],
            [15, 45, 14, NULL, NULL, '1', '0'],
            [16, 47, 15, NULL, NULL, '1', '0'],
            [17, 49, 16, NULL, NULL, '1', '0'],
            [18, 51, 17, NULL, NULL, '1', '0'],
            [19, 53, 18, NULL, NULL, '1', '0'],
            [20, 55, 19, NULL, NULL, '1', '0'],
            [21, 57, 20, NULL, NULL, '1', '0'],
            [22, 59, 21, NULL, NULL, '1', '0'],
            [23, 61, 22, NULL, NULL, '1', '0'],
            [24, 63, 23, 'LIBRETA DE NOTAS - DEYSI JACKELINE MOLINA ALVA.pdf', NULL, '1', '0'],
            [25, 65, 25, 'LIBRETA DE NOTAS - GREYS YHAJAIRA MONDRAGON MENDOZA.pdf', NULL, '1', '0'],
            [26, 66, 24, 'LIBRETA DE NOTAS - KHALEEXI CRISTEL SALAZAR LOZANO.pdf', NULL, '1', '0'],
            [27, 69, 26, NULL, NULL, '1', '0'],
            [28, 71, 27, NULL, NULL, '1', '0'],
            [29, 73, 28, NULL, NULL, '1', '0'],
            [30, 75, 29, NULL, NULL, '1', '0'],
            [31, 77, 30, NULL, NULL, '1', '0'],
            [32, 79, 31, NULL, NULL, '1', '0'],
            [33, 81, 32, NULL, NULL, '1', '0'],
            [34, 83, 33, NULL, NULL, '1', '0'],
            [35, 85, 34, NULL, NULL, '1', '0'],
            [36, 87, 35, NULL, NULL, '1', '0'],
            [37, 89, 36, NULL, NULL, '1', '0'],
            [38, 91, 37, NULL, NULL, '1', '0'],
            [39, 93, 38, NULL, NULL, '1', '0'],
            [40, 95, 39, NULL, NULL, '1', '0'],
            [41, 97, 40, NULL, NULL, '1', '0'],
            [42, 99, 41, NULL, NULL, '1', '0'],
            [43, 101, 42, NULL, NULL, '1', '0'],
            [44, 103, 43, 'LIBRETA DE NOTAS - KELY MARDELY PADILLA MONDRAGON.pdf', NULL, '1', '0'],
            [45, 105, 44, NULL, NULL, '1', '0'],
            [46, 107, 45, NULL, NULL, '1', '0'],
            [47, 109, 46, NULL, NULL, '1', '0'],
            [48, 111, 47, NULL, NULL, '1', '0'],
            [49, 113, 48, NULL, NULL, '1', '0'],
            [50, 115, 49, NULL, NULL, '1', '0'],
            [51, 117, 50, NULL, NULL, '1', '0'],
            [52, 119, 51, NULL, NULL, '1', '0'],
            [53, 121, 52, NULL, 'FICHA DE MATRICULA - MAYRA ELIZABETH HUAMAN MENDOZA.pdf', '1', '0'],
            [54, 123, 53, 'LIBRETA DE NOTAS - ESNEIDER HUANGAL QUIROZ.pdf', 'FICHA DE MATRICULA - ESNEIDER HUANGAL QUIROZ.pdf', '1', '0'],
            [55, 125, 54, NULL, NULL, '1', '0'],
            [56, 127, 55, NULL, NULL, '1', '0'],
            [57, 129, 56, NULL, NULL, '1', '0'],
            [58, 131, 57, NULL, NULL, '1', '0'],
            [59, 133, 58, NULL, NULL, '1', '0'],
            [60, 135, 59, NULL, NULL, '1', '0'],
            [61, 137, 60, NULL, NULL, '1', '0'],
            [62, 139, 61, NULL, NULL, '1', '0'],
            [63, 141, 62, NULL, NULL, '1', '0'],
            [64, 143, 63, 'LIBRETA DE NOTAS - ARNOL FABIAN SALAZAR ESPINOZA.pdf', 'FICHA DE MATRICULA - ARNOL FABIAN SALAZAR ESPINOZA.pdf', '1', '0'],
            [65, 145, 64, NULL, NULL, '1', '0'],
            [66, 147, 65, NULL, NULL, '1', '0'],
            [67, 149, 66, NULL, NULL, '1', '0'],
            [68, 151, 67, 'LIBRETA DE NOTAS - WARNER XOEL VASQUEZ CHUQUIJAJAS.pdf', NULL, '1', '0'],
            [69, 153, 68, NULL, NULL, '1', '0'],
            [70, 155, 69, NULL, NULL, '1', '0'],
            [71, 157, 70, 'LIBRETA DE NOTAS - NICOLAS JHANPIER ESPINOZA CASTAÑEDA.pdf', 'FICHA DE MATRICULA - NICOLAS JHANPIER ESPINOZA CASTAÑEDA.pdf', '1', '0'],
            [72, 159, 71, 'LIBRETA DE NOTAS - DILMER EHUNER GALVEZ CUBAS.pdf', 'FICHA DE MATRICULA - DILMER EHUNER GALVEZ CUBAS.pdf', '1', '0'],
            [73, 161, 72, NULL, NULL, '1', '0'],
            [74, 163, 73, NULL, NULL, '1', '0'],
            [75, 165, 74, 'LIBRETA DE NOTAS - YAMER DAVID MENDOZA PEREZ.pdf', NULL, '1', '0'],
            [76, 167, 75, NULL, NULL, '1', '0'],
            [77, 169, 76, NULL, NULL, '1', '0'],
            [78, 171, 77, 'LIBRETA DE NOTAS - SHEYDER FERNANDO VENTURA CUBAS.pdf', 'FICHA DE MATRICULA - SHEYDER FERNANDO VENTURA CUBAS.pdf', '1', '0'],
            [79, 173, 78, 'LIBRETA DE NOTAS - EVER EFRAIN ZELADA VENTURA.pdf', 'FICHA DE MATRICULA - EVER EFRAIN ZELADA VENTURA.pdf', '1', '0'],
            [80, 178, 80, NULL, NULL, '1', '0'],
            [81, 180, 81, NULL, NULL, '1', '0'],
            [82, 182, 82, NULL, NULL, '1', '0'],
            [83, 184, 83, 'LIBRETA DE NOTAS - LUIS ALBERTO ALBARRAN JARA.pdf', 'FICHA DE MATRICULA - LUIS ALBERTO ALBARRAN JARA.pdf', '1', '0'],
            [84, 186, 84, NULL, NULL, '1', '0'],
            [85, 188, 85, 'LIBRETA DE NOTAS - ALEX SILVA ENEQUE.pdf', 'FICHA DE MATRICULA - ALEX SILVA ENEQUE.pdf', '1', '0'],
            [86, 190, 86, NULL, NULL, '1', '0'],
            [87, 192, 87, NULL, NULL, '1', '0'],
            [88, 194, 88, NULL, NULL, '1', '0'],
            [89, 196, 89, NULL, NULL, '1', '0'],
            [90, 199, 90, 'LIBRETA DE NOTAS - DAVID FERNANDO ZÉNIZ RAMOS.pdf', NULL, '1', '0'],
            [91, 201, 91, NULL, NULL, '1', '0'],
            [92, 203, 92, 'LIBRETA DE NOTAS - CARLOS PIERO JUNIOR MERINO CARPIO.pdf', NULL, '1', '0']
        ];

        foreach ($alumnos as $alumno) {
            DB::table('alumnos')->insert([
                'alu_id' => $alumno[0],
                'per_id' => $alumno[1],
                'apo_id' => $alumno[2],
                'name_libreta_notas' => $alumno[3],
                'name_ficha_matricula' => $alumno[4],
                'alu_estado' => $alumno[5],
                'is_deleted' => $alumno[6],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
                
            ]);
        }
    }
}
