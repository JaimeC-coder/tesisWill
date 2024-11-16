<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
           [1, 1, 1, 'Willian Samuel Miranda Huaman', 'willmirandahuaman20@gmail.com', NULL, '$2y$10$tBX8Bw470Z.6k.b8tah3VeVmXOAahgsUjtiQ9k0FVk8oa6ZT4/6zi', '1', '0', NULL, '2023-09-17 05:00:00', '2022-11-17 05:00:00'],
           [2, 198, 1, 'STHEPHANIE CABRERA HONORIO', 'scabrerah@unitru.edu.pe', NULL, '$2y$10$Zf33.IktdVZEXB0PBiXcCezLZtHdAuyu7kvsMyC2n0/UR0Dlz3OIi', '1', '0', NULL, '2023-10-24 00:30:26', '2023-10-24 00:30:26'],
           [3, 158, 7, 'Apoderado', 'william_15mir@hotmail.com', NULL, '$2y$10$svDClz1upUAq9ysfYnvjoeK9w7heWViN5vzkjWZlU12EhtD44xZi6', '1', '0', NULL, '2023-10-29 09:22:47', '2023-10-29 09:22:47'],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'id' => $user[0],
                'per_id' => $user[1],
                'rol_id' => $user[2],
                'name' => $user[3],
                'email' => $user[4],
                'email_verified_at' =>NULL,
                'password' => $user[6],
                'estado' => $user[7],
                'is_deleted' => $user[8],
                'remember_token' => NULL,
                'created_at' => $user[10],
                'updated_at' => $user[11],
                'deleted_at' => NULL
            ]);
        }

    }
}
