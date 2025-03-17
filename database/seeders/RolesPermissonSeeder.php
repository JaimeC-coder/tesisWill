<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use  Spatie\Permission\Models\Role;
use  Spatie\Permission\Models\Permission;

class RolesPermissonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //roles para el area de abastecimineto :
        //Super Usuarios
        $role = Role::create(['name' => 'Administrador', 'tipo' => 'administrativo']);
        $role1 = Role::create(['name' => 'Director', 'tipo' => 'administrativo']);
        $role2 = Role::create(['name' => 'Docente', 'tipo' => 'administrativo']);
        $role3 = Role::create(['name' => 'Auxiliar', 'tipo' => 'administrativo']);
        $role4 = Role::create(['name' => 'Secretaria', 'tipo' => 'administrativo']);

        $role5 = Role::create(['name' => 'Alumno', 'tipo' => 'usuario']);
        $role6 = Role::create(['name' => 'Apoderado', 'tipo' => 'usuario']);

        //     Académico
        //*     Inicio
        Permission::create(
            [
                'name' => 'panel.home',
                'description' => 'Acceso al panel de principal',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1, $role2, $role3, $role4, $role5, $role6]);
        //*     Matriculas
        Permission::create(
            [
                'name' => 'panel.matriculas',
                'description' => 'Acceso al  modulo de matriculas',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        // *    Horario
        Permission::create(
            [
                'name' => 'panel.horario',
                'description' => 'Acceso al  modulo de horario',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1, $role2, $role4, $role5,   $role6]);
        // *    Horario
        Permission::create(
            [
                'name' => 'panel.horario.registro',
                'description' => 'Acceso al  modulo de horario',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1, $role2, $role4]);
        // *    Notas
        Permission::create(
            [
                'name' => 'panel.notas',
                'description' => 'Acceso al  modulo de notas',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1, $role2, $role4]);

        //    Reportes
        //*     Generales
        Permission::create(
            [
                'name' => 'reportes.generales',
                'description' => 'Acceso al  modulo de reportes generales',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1, $role2,  $role4]);
        //*     Gestión
        Permission::create(
            [
                'name' => 'reportes.gestion',
                'description' => 'Acceso al modulo de reportes de gestion',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1, $role2,   $role4]);
        //*     Por Alumno
        Permission::create(
            [
                'name' => 'reportes.alumno',
                'description' => 'Acceso al modulo de reportes por alumno',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1, $role2, $role3, $role4, $role5, $role6]);
        //    Admin
        //    Actividades Adminitrativas
        //     Ambientes
        Permission::create(
            [
                'name' => 'actividades.ambientes',
                'description' => 'Acceso al modulo de Ambientes',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //     Personal Académico
        Permission::create(
            [
                'name' => 'actividades.personal',
                'description' => 'Acceso al modulo de Personal Académico',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //     Año Escolar
        Permission::create(
            [
                'name' => 'actividades.anio',
                'description' => 'Acceso al modulo de Año Escolar',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //     Periodo Académico
        Permission::create(
            [
                'name' => 'actividades.periodo',
                'description' => 'Acceso al modulo de Periodo Académico',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //     Cursos
        Permission::create(
            [
                'name' => 'actividades.cursos',
                'description' => 'Acceso al modulo de Cursos',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //     Grados y Secciones
        Permission::create(
            [
                'name' => 'actividades.grados',
                'description' => 'Acceso al modulo de Grados y Secciones',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //     Asignar de Cursos
        Permission::create(
            [
                'name' => 'actividades.asignarCurso',
                'description' => 'Acceso al modulo de Asignar de Cursos',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //     Asignar Grados
        Permission::create(
            [
                'name' => 'actividades.asignarGrados',
                'description' => 'Acceso al modulo de Asignar Grados',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //     Alumnos
        Permission::create(
            [
                'name' => 'actividades.alumnos',
                'description' => 'Acceso al modulo de Alumnos',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
        //    SEGURIDAD
        //     Roles
        Permission::create(
            [
                'name' => 'actividades.roles',
                'description' => 'Acceso al modulo de Alumnos',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1]);
        //     Usuarios
        Permission::create(
            [
                'name' => 'actividades.usuarios',
                'description' => 'Acceso al modulo de Alumnos',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1]);
        //     Institucion Educativa
        Permission::create(
            [
                'name' => 'actividades.institucion',
                'description' => 'Acceso al modulo de Alumnos',
                'tipo' => 'panel'
            ]
        )->syncRoles([$role, $role1,  $role4]);
    }
}
