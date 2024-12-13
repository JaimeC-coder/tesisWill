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
                    <form method="GET" action="{{ route('asignarGrado.inicio') }}">
                        <div class="row">
                            <label class="col-md-1 col-form-label">Nivel </label>
                            <div class="col-md-3">
                                <select id="inputState" class="form-control" name="niv_id">
                                    <option value="0" selected disabled>-- Selecciona --</option>
                                    @foreach ($nivels as $item)
                                        <option value="{{ $item->niv_id }}">{{ $item->niv_descripcion }}</option>
                                    @endforeach
                                </select>
                                </select>
                            </div>
                            <div class="col-md-1"></div>
                            <label class="col-md-1 col-form-label">Curso </label>
                            <div class="col-md-4">
                                <select id="curso" name="cursoId" class="form-control show-tick">
                                    <option value="0" selected disabled>-- Selecciona --</option>
                                    <option value="-1" selected>Todos </option>
                                </select>
                            </div>
                            <div class="col-lg col-md-4 col-sm-6" style="display: flex;align-items: center;">
                                <input type="submit" class="form-control btn btn-primary" value="Buscar">
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
                        <table class="table" id="myTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Docente</th>
                                    @foreach ($data_grados as $index => $grado)
                                        <th>{{ $grado['gra_descripcion'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asignacion_curso as $index => $item)
                                    <tr data-pa-id="{{ $item['pa_id'] }}" data-curso="{{ $item['curso'] }}"
                                        data-asignaciones="{{ json_encode($item['asignaciones']) }}">
                                        <td>
                                            <button type="button" class="btn btn-icon btn-sm"
                                                onclick="asignacionMasiva(this)">
                                                <i class="fa fa-save text-info"></i>
                                            </button>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-sm" title="Limpiar"
                                                onclick="eliminacionMasiva(this)">
                                                <i class="fa fa-trash text-danger"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <b>{{ $item['nombres'] }} {{ $item['apellidos'] }}</b>
                                            <hr>
                                            <span>{{ $item['curso'] }}</span>
                                        </td>
                                        @foreach ($item['grados'] as $index2 => $grado)
                                            <td>
                                                @foreach ($item['secciones'][$index2] as $index3 => $ss)
                                                    <div class="form-check">
                                                        <input class="seccion-checkbox" type="checkbox"
                                                            id="checkbox-{{ $item['pa_id'] }}-{{ $grado[$index3] }}"
                                                            data-docente-id="{{ $item['pa_id'] }}"
                                                            data-curso-nombre="{{ $item['curso'] }}"
                                                            data-seccion-nombre="{{ $ss }}"
                                                            value="{{ $grado[$index3] }}"
                                                            onclick="addCheckBoxAsignacion(this)"
                                                            {{ in_array($grado[$index3], $item['asignaciones']) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="checkbox-{{ $item['pa_id'] }}-{{ $grado[$index3] }}">
                                                            {{ $ss }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
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
    <script src="{{ asset('js/asignarGrado/form.js') }}"></script>
@endsection
