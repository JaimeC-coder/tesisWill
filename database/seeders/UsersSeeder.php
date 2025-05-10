<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personas = [
            [206, '12345670', 'Usuario ', 'Pruebas 1', NULL, 'usuarioprueba@gmail.com', 'F', '1984-12-18', 'C', NULL, 'PERÚ', 6, 194, 1844, NULL, '0'],
            [207, '12345600', 'Usuario2', 'MERINO CARPIO', NULL, 'usuariopruebaadmin@unitru.edu.pe', 'M', '1999-10-10', 'S', NULL, 'PERÚ', 13, 118, 1174, NULL, '0'],
            [208, '12345000', 'Usuario3', 'CASTILLO SILVA', NULL, 'william_15mir@hotmail.com', 'F', '1980-10-10', 'S', NULL, 'PERÚ', 13, 118, 1174, NULL, '0'],
        ];



        foreach ($personas as $persona) {
            $personafor = Persona::create([
                'per_id' => $persona[0],
                'per_dni' => $persona[1],
                'per_nombres' => $persona[2],
                'per_apellidos' => $persona[3],
                'per_nombre_completo' => $persona[2] . ' ' . $persona[3],
                'per_email' => $persona[5],
                'per_sexo' => $persona[6],
                'per_fecha_nacimiento' => $persona[7],
                'per_estado_civil' => $persona[8],
                'per_celular' => $persona[9],
                'per_pais' => $persona[10],
                'per_departamento' => $persona[11],
                'per_provincia' => $persona[12],
                'per_distrito' => $persona[13],
                'per_direccion' => $persona[14],
                'is_deleted' => $persona[15],
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => NULL
            ]);

            if ($persona[5] != NULL) {
                $user = User::create([
                    'name' => $persona[2] . ' ' . $persona[3],
                    'per_id' => $persona[0],
                    'email' => $persona[5],
                    'estado' => $persona[15],
                    'password' => Hash::make('12345678'),
                    'rol_id' => 3,
                    'is_deleted' => $persona[15],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => NULL
                ]);
                $user->assignRole('Administrador');
            } else {
                $user = User::create([
                    'name' => $persona[2] . ' ' . $persona[3],
                    'per_id' => $persona[0],
                    'email' => $persona[1] . '_falta_' . '@gmail.com',
                    'estado' => $persona[15],
                    'password' => Hash::make('12345678'),
                    'rol_id' => 3,
                    'is_deleted' => $persona[15],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => NULL
                ]);
                $user->assignRole('Alumno');
            }
        }
    }
}


