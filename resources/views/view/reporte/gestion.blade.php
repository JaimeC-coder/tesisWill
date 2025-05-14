@extends('adminlte::page')
@section('title', 'Reportes')

@section('content_header', 'Inicio')
@section('content_header_title', 'Reporte')
@section('content_header_subtitle', 'Reportes de Gestión')



@section('content')

    <div>
        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center ">
                    <div class="header-action">
                        <ol class="breadcrumb page-breadcrumb d-none">
                            <li class="breadcrumb-item"><a href="#">Ericsson</a></li>
                            <li class="breadcrumb-item active" aria-current="page">aula</li>
                        </ol>
                    </div>
                    <div>

                    </div>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <form action="{{ route('reporte.gestion') }}" method="GET" role="form"
                        enctype="multipart/form-data">
                        <div class="tab-pane active" id="aula-all">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="row col-md-4">
                                            <div class="col-md-10">
                                                <label class="col-form-label">Año </label>
                                                <select class="form-control show-tick ml-3" name="anio" id="anio">
                                                    <option value="0" selected disabled>-- Selecciona --</option>
                                                    @foreach ($anios as $anio)
                                                        <option value="{{ $anio->anio_id }}">{{ $anio->anio_descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-10">
                                                <label class="col-form-label">Nivel </label>
                                                <select class="form-control show-tick ml-3" name="nivel" id="nivel"
                                                    disabled>
                                                    <option value="0" selected disabled>-- Selecciona --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row col-md-4">
                                            <div class="col-md-10">
                                                <label class="col-form-label">Grado </label>
                                                <select class="form-control show-tick ml-3" name="grado" id="grado"
                                                    disabled>
                                                    <option value="0" selected disabled>-- Selecciona --</option>

                                                </select>
                                            </div>
                                            <div class="col-md-10">
                                                <label class="col-form-label">Secciones </label>

                                                <select class="form-control show-tick ml-3" name="seccion" id="seccion"
                                                    disabled>
                                                    <option value="0" selected disabled>-- Selecciona --</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="submit" value="Buscar" class="btn btn-primary btn-block"
                                                        disabled id="btnbuscar">

                                                </div>
                                                <div class="col-md-6">


                                                    <div class="dropdown" id="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            data-toggle="dropdown" aria-expanded="false" disabled
                                                            id="btnDatosprueba">
                                                            Opciones
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button class="dropdown-item" id="buttonrepor"
                                                                type="button">Generar PDF</button>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="listar-info" class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Reporte Por Curso</h3>
                                </div>
                                <div class="card-body col-md-12">
                                    <div class="table-responsive">
                                        <table id="table-notas" class="table table-bordered"
                                            style="width:100%;text-align: center;">
                                            <thead>
                                                <tr class="table-active text-dark">
                                                    <th class="dark">#</th>
                                                    <th class="dark">Curso</th>
                                                    <th class="dark">Alumnos Matriculados</th>
                                                    <th class="dark">Aprobados(%)</th>
                                                    <th class="dark">En proceso(%)</th>
                                                    <th class="dark">Desaprobados(%)</th>

                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if (isset($resultados) && $resultados != null)
                                                    @foreach ($resultados as $curso)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
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
                                                @elseif($resultados == null)
                                                    <tr>
                                                        <td colspan="7">
                                                            <img src="/assets/images/report-search.svg" alt="Filtrar"
                                                                width="20%">
                                                            <p class="mt-4">
                                                                ¡Esperando buscar!
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="7">
                                                            <img src="/assets/images/report-search.svg" alt="Filtrar"
                                                                width="20%">
                                                            <p class="mt-4">
                                                                ¡Esperando buscar!
                                                            </p>
                                                        </td>
                                                    </tr>

                                                @endif



                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Gráfico</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="div_grafico">
                            <canvas id="grafico"></canvas>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection


@section('js')
    <script src="{{ asset('js/reportes/gestion.js') }}"></script>

    <script>
        let button = document.getElementById('buttonrepor');
        button.addEventListener('click', function() {

            mostrarSwich();
            // Redirigir a la URL con los parámetros

        });
        //obtener los datos de la url
    </script>


    <script>
        function mostrarSwich() {
            let timerInterval;
            Swal.fire({
                title: "Generando PDF...!",
                html: "Procesando en <b></b> millisegundos.",
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                /* Read more about handling dismissals below */
                let urlParams = new URLSearchParams(window.location.search);
                let anio = urlParams.get('anio');
                let nivel = urlParams.get('nivel');
                let grado = urlParams.get('grado');
                let seccion = urlParams.get('seccion');
                window.open('/api/reporte/gestion/pdf?anio=' + anio + '&nivel=' + nivel + '&grado=' + grado +
                    '&seccion=' +
                    seccion, '_blank');
            });
        }
    </script>
@endsection
