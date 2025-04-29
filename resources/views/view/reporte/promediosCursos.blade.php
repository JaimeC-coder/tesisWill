<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Cursos</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 0.875rem;
        }

        .logo {
            width: 5rem;
            height: 7rem;
            object-fit: contain;
        }

        .sello {
            width: 10rem;
            object-fit: contain;
        }

        .table {
            border-collapse: collapse;
        }

        .title {
            font-weight: 600;
            font-weight: bold;
        }

        .bg-gray {
            background-color: #C0C0C0;
            font-weight: 600;
        }

        .table tr th,
        .table tr td {
            padding: 8px;
            border: 1px solid black;
        }

        table {
            font-size: 12px;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <h2>REPORTE POR CURSOS</h2>
    <table width="100%" style="margin-bottom: 1.5rem;">
        <tbody>
            <tr>
                <td align="center">
                    <img src="{{ public_path('escudoMinedu.png') }}" alt="escudo" class="logo" />
                </td>
                <td>
                    <table width="100%" border="1" class="table">
                        <tr>
                            <td class="bg-gray">DRE:</td>
                            <td>DRE SAN MIGUEL</td>
                            <td class="bg-gray">UGEL:</td>
                            <td>UGEL SAN MIGUEL</td>
                        </tr>
                        <tr>
                            <td class="bg-gray">Nivel:</td>
                            <td>{{ $headers['nivel'] }}</td>
                            <td class="bg-gray">Año Escolar:</td>
                            <td>{{ $headers['anio'] }}</td>
                        </tr>
                        <tr>
                            <td class="bg-gray">Institución Educativa:</td>
                            <td colspan="3">I.E. 82857 LIVES</td>
                        </tr>
                        <tr>
                            <td class="bg-gray">Grado:</td>
                            <td>{{ $headers['grado'] }}</td>
                            <td class="bg-gray">Sección:</td>
                            <td>{{ $headers['seccion'] }}</td>
                        </tr>
                    </table>
                </td>
                <td align="center">
                    <img src="{{ public_path('insig.png') }}" alt="cao" class="logo" />
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%" border="1" class="table" style="margin-bottom: 2rem;">
        <thead>
            <tr>
                <th class="bg-gray">#</th>
                <th class="bg-gray">Curso</th>
                <th class="bg-gray">Alumnos Matriculados</th>
                <th class="bg-gray">Aprobados(%)</th>
                <th class="bg-gray">En proceso(%)</th>
                <th class="bg-gray">Desaprobados(%)</th>


            </tr>
        </thead>
        <tbody>

            @foreach ($cursosList as $index => $curso)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $curso['curso_nombre'] }}</td>
                    <td>{{ $curso['total_notas'] }}</td>
                    <td class="text-success">
                        {{ $curso['porcentaje_aprobados'] }}%
                    </td>
                    <td class="text-warning">
                        {{ $curso['porcentaje_B'] }}%
                    </td>
                    <td class="text-danger">
                        {{ $curso['porcentaje_C'] }}%
                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>
    <div id="piechart" class="pie-chart"></div>

    {{-- make sure you are using http, and not https --}}
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>

    <script type="text/javascript">
        function init() {
            google.load("visualization", "1.1", {
                packages: ["corechart"],
                callback: 'drawCharts'
            });
        }

        function drawCharts() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Coding', 11],
                ['Eat', 1],
                ['Commute', 2],
                ['Looking for code Problems', 4],
                ['Sleep', 6]
            ]);
            var options = {
                title: 'My Daily Activities',
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
</body>

</html>
