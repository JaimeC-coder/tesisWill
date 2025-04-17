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
                    <form action="{{ route('reporte.gestion') }}" method="GET" role="form" enctype="multipart/form-data">
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
                                                <select class="form-control show-tick ml-3" name="nivel" id="nivel" disabled>
                                                    <option value="0" selected disabled>-- Selecciona --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row col-md-4">
                                            <div class="col-md-10">
                                                <label class="col-form-label">Grado </label>
                                                <select class="form-control show-tick ml-3" name="grado" id="grado" disabled>
                                                    <option value="0" selected disabled>-- Selecciona --</option>

                                                </select>
                                            </div>
                                            <div class="col-md-10">
                                                <label class="col-form-label">Secciones </label>

                                                <select class="form-control show-tick ml-3" name="seccion" id="seccion" disabled>
                                                    <option value="0" selected disabled>-- Selecciona --</option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="submit" value="Buscar" class="btn btn-primary btn-block" disabled id="btnbuscar">

                                                </div>
                                                <div class="col-md-6">


                                                    <div class="dropdown" id="dropdown">
                                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" disabled>
                                                          Opciones
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item"
                                                            :href="`/report/courses/pdf?año=${params.año}&nivel=${params.nivel}&grado=${params.grado}&seccion=${params.seccion}`"
                                                            target="_blank">Generar PDF</a>
                                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#exampleModal" @click="showChartInModal">Ver
                                                            Gráfico</a>
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
                                                    <th class="dark">Desaprobados(%)</th>
                                                    <th class="dark">Promedio General</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if (isset($result) && $result != null)
                                                    @foreach ($result as $curso)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $curso->curso }}</td>
                                                            <td>{{ $curso->total_alumnos }}</td>
                                                            <td
                                                                class="{{ $curso->porcentaje_aprobados < 50 ? 'text-danger' : '' }}">
                                                                {{ $curso->porcentaje_aprobados }}%
                                                            </td>
                                                            <td
                                                                class="{{ $curso->porcentaje_desaprobados < 50 ? 'text-danger' : '' }}">
                                                                {{ $curso->porcentaje_desaprobados }}%
                                                            </td>
                                                            {{-- <td class="{{ $curso->promedio_notas < 11 ? 'text-danger' : '' }}">
                                                                {{ $curso->promedio_notas }}
                                                            </td> --}}
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="5">
                                                            PROMEDIO GENERAL DEL GRADO:
                                                        </td>
                                                        {{-- <td class="font-weight-bold">
                                                            {{ $promedio_general }}
                                                        </td> --}}
                                                    </tr>
                                                @elseif($result == null)
                                                    <tr>
                                                        <td colspan="6">
                                                            <img src="/assets/images/report-search.svg" alt="Filtrar"
                                                                width="20%">
                                                            <p class="mt-4">
                                                                ¡Esperando buscar!
                                                            </p>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="6">
                                                            <img src="/assets/images/report-search.svg" alt="Filtrar"
                                                                width="20%">
                                                            <p class="mt-4">
                                                                ¡Esperando buscar!
                                                            </p>
                                                        </td>
                                                    </tr>

                                                @endif



                                                {{-- <tr v-if="cursosList.length === 0 && isFirstMounted">
                                                <td colspan="6">
                                                    <img src="/assets/images/report-search.svg" alt="Filtrar" width="20%">
                                                    <p class="mt-4">
                                                        ¡Esperando buscar!
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr v-else-if="cursosList.length === 0 && !isFirstMounted">
                                                <td colspan="6">
                                                    <img src="/assets/images/no-results.svg" alt="Filtrar" width="20%">
                                                    <p class="mt-4">
                                                        No se han encontrado resultados.
                                                    </p>
                                                </td>
                                            </tr> --}}
                                                {{-- <template v-else>
                                                <tr v-for="(item, index) in cursosList" :key="item.cur_id">
                                                    <td>{{ index + 1 }}</td>
                                                    <td>{{ item.curso }}</td>
                                                    <td>{{ item.total_alumnos }}</td>
                                                    <td
                                                        :class="{ 'text-danger': parseFloat(item.porcentaje_aprobados) < 50 }">
                                                        {{ item.porcentaje_aprobados }}%</td>
                                                    <td>
                                                        {{ item.porcentaje_desaprobados }}%
                                                    </td>
                                                    <td :class="{ 'text-danger': parseFloat(item.promedio_notas) < 11 }">{{
                                                        item.promedio_notas }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        PROMEDIO GENERAL DEL GRADO:
                                                    </td>
                                                    <td class="font-weight-bold">
                                                        {{ calcularPromedioNotas }}
                                                    </td>
                                                </tr>
                                            </template> --}}
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
@endsection
