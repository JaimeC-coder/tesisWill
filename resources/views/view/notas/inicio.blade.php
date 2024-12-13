@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Curso')


@section('content')


    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <form method="GET" action="{{ route('nota.inicio') }}">
                        <div class="card-body">
                            <div class="row mb-4" style="display: flex;justify-content: flex-end;">
                                <label class="col-md-1 col-form-label">Año </label>
                                <div class="col-md-3">
                                    <select id="anio" name="anio" class="form-control show-tick">
                                        <option value="0" selected disabled>-- Selecciona --</option>
                                        @foreach ($anios as $anio)
                                            <option value="{{ $anio->anio_id }}">{{ $anio->anio_descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="col-form-label">Nivel</label>
                                    <select id="nivel" name="nivel" class="form-control show-tick">
                                        <option value="0" selected disabled>-- Selecciona --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label">Docente </label>
                                    <select id="docente" name="docente" class="form-control show-tick">
                                        <option selected disabled value="0"> No hay docentes registrados </option>

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label">Grado </label>
                                    <select id="grado" name="grado" class="form-control show-tick">
                                        <option value="0" selected disabled> No hay grados registrados </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label">Sección </label>
                                    <select id="seccion" name="seccion" class="form-control show-tick">
                                        <option selected disabled value="0"> No hay secciones registrados </option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label">Cursos </label>
                                    <select id="cursoId" name="cursoId" class="form-control show-tick">
                                        <option value="0" selected disabled>-- Selecciona --</option>
                                    </select>
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-2 mt-auto">
                                    <input type="submit" class="form-control btn btn-primary" value="Buscar">

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="table-responsive">
                        <table id="myTable" class="display" style="width:100%; text-align: center;">
                            @if ($tipoPeriodo !=null)

                            <thead>
                                <tr>
                                    <th class="d-none">#</th>
                                    <th>DNI</th>
                                    <th>Alumno</th>
                                    @foreach (range(1, $tipoPeriodo['cantidad']) as $index => $item)
                                        <th>{{ $tipoPeriodo['name'] }} {{ $item }}</th>
                                    @endforeach
                                    <th>Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Gsas as $index => $item)
                                    <tr>
                                        <td class="d-none">{{ $item['ags_id'] }}</td>
                                        <td>{{ $item['dni'] }}</td>
                                        <td>{{ $item['alumno'] }}</td>
                                        @foreach (range(1, $tipoPeriodo['cantidad']) as $index2 => $g)
                                            <td>
                                                @if (!empty($item['notas'][$g - 1]))
                                                    {{ $item['notas'][$g - 1]['notaValor'] }}
                                                @else
                                                    0
                                                    <button type="button" class="btn btn-icon btn-sm" title="Registrar"
                                                        onclick="mostrar_crear({{ json_encode($item) }}, {{ $g }})">
                                                        <i class="fa fa-edit text-secondary"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>
                                            <span
                                                class="{{ $item['promedio'] >= 10.5 ? 'badge badge-success' : 'badge badge-danger' }}">
                                                {{ $item['promedioValor'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @else
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>DNI</th>
                                    <th>Alumno</th>
                                    <th>Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>


                            @endif
                        </table>

                    </div>

                </div>
            </div>
        </div>

    </div>






@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                language: {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "Nada encontrado - lo siento",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "search": "Buscar:",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
    <script src="{{ asset('js/notas/form.js') }}"></script>
@endsection
