<?php

namespace Database\Seeders;

use App\Models\Matricula;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatriculasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $matriculas = [
            [3, 1, 3, 716, '2023-10-11', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
            [4, 1, 4, 717, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [5, 1, 5, 718, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [6, 1, 6, 719, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [7, 1, 1, 720, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [8, 1, 7, 721, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [9, 1, 8, 722, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [10, 1, 9, 723, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [11, 1, 10, 724, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [12, 1, 11, 725, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [13, 1, 2, 726, '2023-10-11', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [14, 1, 13, 727, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [15, 1, 14, 728, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [16, 1, 15, 729, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [17, 1, 16, 730, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [18, 1, 17, 731, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [19, 1, 18, 732, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [20, 1, 19, 733, '2023-10-11', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
            [21, 1, 20, 734, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [22, 1, 21, 735, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [23, 1, 22, 736, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [24, 1, 23, 737, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [25, 1, 24, 738, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [26, 1, 25, 739, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [27, 1, 26, 740, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [28, 1, 27, 741, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [29, 1, 28, 742, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [30, 1, 29, 743, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [31, 1, 30, 744, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [32, 1, 31, 745, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [33, 1, 32, 746, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [34, 1, 33, 747, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [35, 1, 34, 748, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [36, 1, 35, 749, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [37, 1, 36, 750, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [38, 1, 37, 751, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [39, 1, 38, 752, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [40, 1, 39, 753, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [41, 1, 40, 754, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [42, 1, 41, 755, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [43, 1, 42, 756, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [44, 1, 43, 757, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [45, 1, 44, 758, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [46, 1, 45, 759, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [47, 1, 46, 760, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [48, 1, 47, 761, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [49, 1, 48, 762, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [50, 1, 49, 763, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [51, 1, 50, 764, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [52, 1, 51, 765, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [53, 1, 52, 766, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [54, 1, 53, 767, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [55, 1, 54, 768, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [56, 1, 55, 769, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [57, 1, 56, 770, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [58, 1, 57, 771, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [59, 1, 58, 772, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [60, 1, 59, 773, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [61, 1, 60, 774, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [62, 1, 61, 775, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [63, 1, 62, 776, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [64, 1, 63, 777, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [65, 1, 64, 778, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [66, 1, 65, 779, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [67, 1, 66, 780, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [68, 1, 67, 781, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [69, 1, 68, 782, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [70, 1, 69, 783, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [71, 1, 70, 784, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [72, 1, 71, 785, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [73, 1, 72, 786, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [74, 1, 73, 787, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [75, 1, 74, 788, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [76, 1, 75, 789, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [77, 1, 76, 790, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [78, 1, 77, 791, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [79, 1, 78, 792, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [80, 1, 79, 793, '2023-10-11', 'Promovido', 'Misma IE', NULL, NULL, '1', '0'],
            [82, 1, 81, 795, '2023-10-21', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
            [83, 1, 83, 796, '2023-10-21', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [84, 1, 82, 797, '2023-10-21', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [85, 1, 80, 798, '2023-10-21', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
            [86, 1, 84, 799, '2023-10-21', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
            [87, 1, 85, 800, '2023-10-21', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
            [88, 1, 86, 801, '2023-10-21', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
            [89, 1, 87, 802, '2023-10-21', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
            [90, 1, 88, 803, '2023-10-21', 'Ingresante', 'Misma IE', 'NULL', 'NULL', '1', '0'],
            [91, 1, 90, 804, '2023-10-23', 'Ingresante', 'Otra IE', NULL, NULL, '1', '0'],
            [92, 1, 92, 805, '2023-11-03', 'Ingresante', 'Misma IE', NULL, NULL, '1', '0'],
        ];

        foreach ($matriculas as $matricula) {
            Matricula::create([
                'mat_id' => $matricula[0],
                'per_id' => $matricula[1],
                'alu_id' => $matricula[2],
                'ags_id' => $matricula[3],
                'mat_fecha' => $matricula[4],
                'mat_situacion' => $matricula[5],
                'mat_tipo_procedencia' => $matricula[6],
                'mat_colegio_procedencia' => $matricula[7],
                'mat_observacion' => $matricula[8],
                'mat_estado' => $matricula[9],
                'is_deleted' => $matricula[10],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);
        }
    }
}
