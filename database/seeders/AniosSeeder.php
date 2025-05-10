<?php

namespace Database\Seeders;

use App\Models\Anio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AniosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $anios = [
            [1, '2022', '2022-03-01', '2022-12-31', '45 min', '15 min', 50, '2', '0', '0'],
            [2, '2023', '2023-03-01', '2023-12-31', '45 min', '15 min', 50, '2', '1', '0'],
        ];

        foreach ($anios as $anio) {
            Anio::create([
                'anio_id' => $anio[0],
                'anio_descripcion' => $anio[1],
                'anio_fechaInicio' => $anio[2],
                'anio_fechaFin' => $anio[3],
                'anio_duracionHoraAcademica' => $anio[4],
                'anio_duracionHoraLibre' => $anio[5],
                'anio_cantidadPersonal' => $anio[6],
                'anio_tallerSeleccionable' => $anio[7],
                'anio_estado' => $anio[8],
                'is_deleted' => $anio[9],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }










    }
}
