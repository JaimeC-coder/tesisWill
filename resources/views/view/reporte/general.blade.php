@extends('adminlte::page')
@section('title', 'Reportes')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Reporte General')



@section('content')

    <div class="container">
        <div class="container">
            <div class="tab-pane active" id="aula-all">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <label class="col-md-1 col-form-label">Año </label>
                            <div class="col-md-3">
                                <select id="anio" class="form-control show-tick">
                                    <option value="0" selected disabled>-- Selecciona --</option>
                                    @foreach ($anios as $anio)
                                        <option value="{{ $anio->anio_id }}"> {{ $anio->anio_descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1"></div>
                            <label class="col-md-1 col-form-label">Nivel </label>
                            <div class="col-md-3">
                                <select id="nivel" class="form-control show-tick">
                                    <option value="0" selected disabled>-- Selecciona --</option>
                                    @foreach ($niveles as $nivel)
                                        <option value="{{ $nivel->niv_id }}"> {{ $nivel->niv_descripcion }}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-lg-2 col-md-4 col-sm-6" style="display: flex;align-items: center;">
                                <button class="btn btn-sm btn-primary btn-block" id="btnReporte" >Cargar</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Reporte de Matriculas</h5>
                                <div id="div_grafico1">
                                    <span class="list-group-item">Total de alumnos por grado</span>
                                    <canvas id="grafico1"></canvas>
                                </div>
                                <br>
                                <div id="div_grafico3">
                                    <span class="list-group-item">Total de alumnos por genero</span>
                                    <canvas id="grafico3"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Reporte de Personal Academico</h5>

                                <div id="div_grafico4">
                                    <span class="list-group-item">Total de Docentes por cursos</span>
                                    <canvas id="grafico4"></canvas>
                                </div>
                                <br>
                                <div id="div_grafico2">
                                    <span class="list-group-item">Total de Personal por cargos</span>
                                    <canvas id="grafico2"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">+ Reportes</h5>
                            <div id="div_grafico5">
                                <span class="list-group-item">Total de vacantes por grado</span>
                                <canvas id="grafico5"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script src="{{ asset('js/reportes/general.js') }}"></script>
@endsection
