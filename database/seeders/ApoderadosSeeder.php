<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApoderadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apoderados = [

            [1, 3, 'Madre', '2', '0'],
            [2, 5, 'TUTOR', '2', '0'],
            [3, 17, 'MADRE', '1', '0'],
            [4, 19, 'PADRE', '1', '0'],
            [5, 21, 'MADRE', '1', '0'],
            [6, 24, 'MADRE', '2', '0'],
            [7, 26, 'PADRE', '2', '0'],
            [8, 28, 'MADRE', '1', '0'],
            [9, 30, 'MADRE', '1', '0'],
            [10, 32, 'PADRE', '1', '0'],
            [11, 34, 'Madre', '1', '0'],
            [12, 42, 'Madre', '1', '0'],
            [13, 44, 'Padre', '1', '0'],
            [14, 46, 'PADRE', '1', '0'],
            [15, 48, 'PADRE', '1', '0'],
            [16, 50, 'PADRE', '1', '0'],
            [17, 52, 'MADRE', '1', '0'],
            [18, 54, 'MADRE', '1', '0'],
            [19, 56, 'Padre', '2', '0'],
            [20, 58, 'MADRE', '1', '0'],
            [21, 60, 'MADRE', '1', '0'],
            [22, 62, 'MADRE', '1', '0'],
            [23, 64, 'MADRE', '1', '0'],
            [24, 67, 'MADRE', '1', '0'],
            [25, 68, 'Madre', '1', '0'],
            [26, 70, 'PADRE', '1', '0'],
            [27, 72, 'PADRE', '1', '0'],
            [28, 74, 'MADRE', '1', '0'],
            [29, 76, 'PADRE', '1', '0'],
            [30, 78, 'MADRE', '1', '0'],
            [31, 80, 'MADRE', '1', '0'],
            [32, 82, 'PADRE', '1', '0'],
            [33, 84, 'PADRE', '1', '0'],
            [34, 86, 'MADRE', '1', '0'],
            [35, 88, 'PADRE', '1', '0'],
            [36, 90, 'PADRE', '1', '0'],
            [37, 92, 'MADRE', '1', '0'],
            [38, 94, 'Padre', '1', '0'],
            [39, 96, 'TUTOR', '1', '0'],
            [40, 98, 'PADRE', '1', '0'],
            [41, 100, 'PADRE', '1', '0'],
            [42, 102, 'MADRE', '1', '0'],
            [43, 104, 'MADRE', '1', '0'],
            [44, 106, 'PADRE', '1', '0'],
            [45, 108, 'PADRE', '1', '0'],
            [46, 110, 'MADRE', '1', '0'],
            [47, 112, 'MADRE', '1', '0'],
            [48, 114, 'MADRE', '1', '0'],
            [49, 116, 'MADRE', '1', '0'],
            [50, 118, 'MADRE', '1', '0'],
            [51, 120, 'MADRE', '1', '0'],
            [52, 122, 'PADRE', '1', '0'],
            [53, 124, 'PADRE', '1', '0'],
            [54, 126, 'MADRE', '1', '0'],
            [55, 128, 'MADRE', '1', '0'],
            [56, 130, 'MADRE', '1', '0'],
            [57, 132, 'MADRE', '1', '0'],
            [58, 134, 'MADRE', '1', '0'],
            [59, 136, 'MADRE', '1', '0'],
            [60, 138, 'MADRE', '1', '0'],
            [61, 140, 'MADRE', '1', '0'],
            [62, 142, 'PADRE', '1', '0'],
            [63, 144, 'PADRE', '1', '0'],
            [64, 146, 'MADRE', '1', '0'],
            [65, 148, 'MADRE', '1', '0'],
            [66, 150, 'MADRE', '1', '0'],
            [67, 152, 'MADRE', '1', '0'],
            [68, 154, 'PADRE', '1', '0'],
            [69, 156, 'MADRE', '1', '0'],
            [70, 158, 'PADRE', '1', '0'],
            [71, 160, 'PADRE', '1', '0'],
            [72, 162, 'PADRE', '1', '0'],
            [73, 164, 'PADRE', '2', '0'],
            [74, 166, 'PADRE', '1', '0'],
            [75, 168, 'PADRE', '1', '0'],
            [76, 170, 'PADRE', '1', '0'],
            [77, 172, 'MADRE', '1', '0'],
            [78, 174, 'MADRE', '1', '0'],
            [79, 178, 'MADRE', '1', '0'],
            [80, 179, 'MADRE', '1', '0'],
            [81, 181, 'MADRE', '1', '0'],
            [82, 183, 'TUTOR', '2', '0'],
            [83, 185, 'MADRE', '2', '0'],
            [84, 187, 'MADRE', '2', '0'],
            [85, 189, 'MADRE', '2', '0'],
            [86, 191, 'MADRE', '2', '0'],
            [87, 193, 'PADRE', '2', '0'],
            [88, 195, 'PADRE', '2', '0'],
            [89, 197, 'PADRE', '2', '0'],
            [90, 200, 'MADRE', '1', '0'],
            [91, 202, 'MADRE', '1', '0'],
            [92, 204, 'TUTOR', '1', '0'],
        ];

        foreach ($apoderados as $apoderado) {
            DB::table('apoderados')->insert([
                'apo_id' => $apoderado[0],
                'per_id' => $apoderado[1],
                'apo_parentesco' => $apoderado[2],
                'apo_vive_con_estudiante' => $apoderado[3],
                'is_deleted' => $apoderado[4],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}
