<?php

namespace Database\Seeders;

use App\Models\AsignarCurso;
use App\Models\AsignarGrado;
use App\Models\Curso;
use App\Models\NotaCapacidad;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesPermissonSeeder::class,
            RolsSeeder::class,
            PersonasSeeder::class,
            UsersSeeder::class,
            AniosSeeder::class,
            AulasSeeder::class,
            PeriodosSeeder::class,
            DepartamentosSeeder::class,
            NivelsSeeder::class,
            PersonalAcademicosSeeder::class,
            GradosSeeder::class,
            ProvinciasSeeder::class,
            SeccionsSeeder::class,
            ApoderadosSeeder::class,
            AlumnosSeeder::class,
            AsignarCursosSeeder::class,
            AsignarGradosSeeder::class,
            AsistenciasSeeder::class,
            CursosSeeder::class,
            DistritosSeeder::class,
            GsasSeeder::class,
            HorariosSeeder::class,
            InstitucionsSeeder::class,
            MatriculasSeeder::class,
            NotasSeeder::class,
            NotaCapacidadsSeeder::class,
            SeguimientosSeeder::class,
            TiposSeeder::class,
            CapacidadsSeeder::class,
            SPSeeder::class,
        ]);
    }
}
