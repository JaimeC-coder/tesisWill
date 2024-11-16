<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AulasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $aulas = [
            [3, 'Oficinas Adminitrativas', 'Oficina', 10, 'Pabellon 1 - Primer Piso', '1', '1', '2'],
            [4, 'A-012222', 'Aula de Clases', 40, 'Pabellon 1 - Primer Piso', '1', '1', '1'],
            [5, 'A-02', 'Aula de Clases', 40, 'Pabellon 1 - Primer Piso', '1', '1', '1'],
            [6, 'A-03', 'Aula de Clases', 40, 'Pabellon 1 - Primer Piso', '1', '1', '1'],
            [7, 'A-04', 'Aula de Clases', 40, 'Pabellon 1 - Segundo Piso', '1', '1', '1'],
            [8, 'A-05', 'Aula de Clases', 40, 'Pabellon 1 - Segundo Piso', '1', '1', '1'],
            [9, 'A-06', 'Aula de Clases', 40, 'Pabellon 1 - Segundo Piso', '1', '1', '1'],
            [10, 'A-07', 'Aula de Clases', 40, 'Pabellon 1 - Segundo Piso', '1', '1', '1'],
            [11, 'A-08', 'Aula de Clases', 40, 'Pabellon 1 - Segundo Piso', '1', '1', '1'],
            [12, 'A-09', 'Aula de Clases', 40, 'Pabellon 1 - Tercer Piso', '1', '1', '1'],
            [13, 'A-10', 'Aula de Clases', 40, 'Pabellon 1 - Tercer Piso', '1', '1', '1'],
            [14, 'A-11', 'Aula de Clases', 40, 'Pabellon 1 - Tercer Piso', '1', '1', '1'],
            [15, 'A-12', 'Aula de Clases', 40, 'Pabellon 1 - Tercer Piso', '1', '1', '1'],
            [16, 'A-13', 'Aula de Clases', 40, 'Pabellon 1 - Tercer Piso', '1', '1', '1'],
            [17, 'Cocina', 'Extra', 5, 'Pabellon 2 - Primer Piso', '1', '1', '2'],
            [18, 'Salon Multi Usos', 'Extra', 15, 'Pabellon 2 - Primer Piso', '1', '1', '2'],
            [19, 'A-14', 'Aula de Clases', 40, 'Pabellon 2 - Primer Piso', '1', '1', '1'],
            [20, 'Deposito', 'Extra', 10, 'Pabellon 2 - Segundo Piso', '1', '1', '2'],
            [21, 'Laboratorio', 'Aula de Clases', 40, 'Pabellon 2 - Segundo Piso', '1', '1', '1'],
            [22, 'A-15', 'Aula de Clases', 40, 'Pabellon 2 - Segundo Piso', '1', '1', '1'],
            [23, 'AIP - PRIMARIA', 'Aula de Clases', 40, 'Pabellon 2 - Tercer Piso', '1', '1', '2'],
            [24, ' AIP - SECUNDARIA', 'Aula de Clases', 40, 'Pabellon 2 - Tercer Piso', '1', '1', '2'],
            [25, 'Deposito', 'Extra', 10, 'Pabellon 2 - Tercer Piso', '1', '1', '2'],
            [26, 'A-16', 'Aula de Clases', 40, 'Pabellon 3 - Primer Piso', '1', '1', '1'],
            [27, 'A-17', 'Aula de Clases', 40, 'Pabellon 3 - Primer Piso', '1', '1', '1'],
            [28, 'A-18', 'Aula de Clases', 40, 'Pabellon 3 - Primer Piso', '1', '1', '1'],
            [29, 'A-19', 'Aula de Clases', 40, 'Pabellon 3 - Segundo Piso', '1', '1', '1'],
            [30, 'A-20', 'Aula de Clases', 40, 'Pabellon 3 - Segundo Piso', '1', '1', '1'],
            [31, 'A-21', 'Aula de Clases', 40, 'Pabellon 3 - Segundo Piso', '1', '1', '1'],
            [32, 'A-22', 'Aula de Clases', 40, 'Pabellon 3 - Tercer Piso', '1', '1', '1'],
            [33, 'A-23', 'Aula de Clases', 40, 'Pabellon 3 - Tercer Piso', '1', '1', '1'],
            [34, 'A-24', 'Aula de Clases', 40, 'Pabellon 3 - Tercer Piso', '1', '1', '1'],
            [35, 'Aula 1° Grado', 'Aula de Clases', 20, 'Pabellon 1', '1', '0', '1'],
            [36, 'Aula 2° Grado', 'Aula de Clases', 20, 'Pabellon 1', '1', '0', '1'],
            [37, 'Aula 3° Grado', 'Aula de Clases', 20, 'Pabellon 2', '1', '0', '1'],
            [38, 'Aula 4° Grado', 'Aula de Clases', 20, 'Pabellon 2', '1', '0', '1'],
            [39, 'Aula 5° Grado A', 'Aula de Clases', 20, 'Pabellon 2', '1', '0', '1'],
            [40, 'Aula 5° Grado B', 'Aula de Clases', 20, 'Pabellon 2', '1', '0', '1'],
            [41, 'Aula 6° Grado', 'Aula de Clases', 20, 'Pabellon 2', '1', '0', '1'],
            [42, 'Deposito', 'Extra', 10, 'Pabellon 1', '1', '0', '2'],
            [43, 'Cocina', 'Extra', 10, 'Pabellon 1', '1', '0', '2'],
            [44, 'Salon Multi Usos', 'Extra', 100, 'Pabellon 2', '1', '0', '2'],
            [45, 'Oficinas Administrativas', 'Oficina', 5, 'Pabellon 2', '1', '0', '2'],
            [46, 'Centro de Computo', 'Aula de Clases', 20, 'Pabellon 2', '1', '0', '2']
        ];

        foreach ($aulas as $aula) {
            DB::table('aulas')->insert([
                'ala_id' => $aula[0],
                'ala_descripcion' => $aula[1],
                'ala_tipo' => $aula[2],
                'ala_aforo' => $aula[3],
                'ala_ubicacion' => $aula[4],
                'ala_estado' => $aula[5],
                'ala_is_delete' => $aula[6],
                'ala_en_uso' => $aula[7],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}
