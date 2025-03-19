<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //crear procedimientos almacenados

        DB::statement("
        CREATE PROCEDURE `sp_coursesWithAvgsAndNotes`(
            IN `year` INT,
            IN `level` INT,
            IN `grade` INT,
            IN `section` INT
        )
        BEGIN
            SELECT
                co.cur_id,
                co.curso,
                COUNT(*) AS total_alumnos,
                SUM(IF(co.estado = 'A', 1, 0)) AS aprobados,
                SUM(IF(co.estado = 'D', 1, 0)) AS desaprobados,
                ROUND((SUM(IF(estado = 'A', 1, 0)) / COUNT(DISTINCT co.alu_id) * 100), 1) AS porcentaje_aprobados,
                ROUND((SUM(IF(estado = 'D', 1, 0)) / COUNT(DISTINCT co.alu_id) * 100), 1) AS porcentaje_desaprobados,
                IFNULL(SUM(co.suma_notas), 0) AS suma_total,
                ROUND(
                    IFNULL(
                        SUM(
                            CASE
                                WHEN n.nt_nota = 'AD' THEN 4
                                WHEN n.nt_nota = 'A' THEN 3
                                WHEN n.nt_nota = 'B' THEN 2
                                WHEN n.nt_nota = 'C' THEN 1
                                ELSE 0
                            END
                        ) / COUNT(co.cur_id), 0
                    ),
                    1
                ) AS promedio_notas
            FROM (
                SELECT
                    c.cur_id,
                    c.cur_nombre AS curso,
                    a.alu_id,
                    pe.per_nombres,
                    CASE
                        WHEN SUM(
                            CASE
                                WHEN n.nt_nota = 'AD' THEN 4
                                WHEN n.nt_nota = 'A' THEN 3
                                WHEN n.nt_nota = 'B' THEN 2
                                WHEN n.nt_nota = 'C' THEN 1
                                ELSE 0
                            END
                        ) / p.per_tp_notas >= 3 THEN 'A'
                        ELSE 'D'
                    END AS estado,
                    p.per_tp_notas AS nroPeriods,
                    IFNULL(SUM(
                        CASE
                            WHEN n.nt_nota = 'AD' THEN 4
                            WHEN n.nt_nota = 'A' THEN 3
                            WHEN n.nt_nota = 'B' THEN 2
                            WHEN n.nt_nota = 'C' THEN 1
                            ELSE 0
                        END
                    ), 0) AS suma_notas
                FROM
                    matriculas m
                    INNER JOIN alumnos a ON a.alu_id = m.alu_id
                    INNER JOIN personas pe ON a.per_id = pe.per_id
                    INNER JOIN gsas g ON m.ags_id = g.ags_id
                    INNER JOIN periodos p ON m.per_id = p.per_id
                    INNER JOIN cursos c ON g.niv_id = c.niv_id AND g.gra_id = c.gra_id
                    LEFT JOIN notas n ON m.alu_id = n.alu_id AND n.curso_id = c.cur_id
                WHERE
                    g.niv_id = level
                    AND g.gra_id = grade
                    AND p.anio_id = year
                    AND a.is_deleted = 0
                    AND p.is_deleted = 0
                    AND g.is_deleted = 0
                    AND pe.is_deleted = 0
                    AND m.is_deleted = 0
                GROUP BY
                    c.cur_id, a.alu_id, p.per_tp_notas
            ) AS co
            GROUP BY co.cur_id
            ORDER BY co.cur_id;
        END
        ");

    }
}
