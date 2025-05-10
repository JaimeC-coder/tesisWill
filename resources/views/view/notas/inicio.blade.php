@extends('adminlte::page')
@section('title', 'Notas')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Notas')


@section('content')


    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="">
                    <form method="GET" action="{{ route('nota.inicio') }}">
                        <div class="card-body">
                            <input type="hidden" name="xd" id="xd" value="{{ $user }}">
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
                        <table class="display table table-bordered" style="width:100%; text-align: center;">
                            @if ($tipoPeriodo != null)
                                <thead class="thead-dark">
                                    <tr>
                                        <th>DNI</th>
                                        <th>Alumno</th>
                                        <th>Capacidades</th>
                                        @foreach (range(1, $tipoPeriodo['tp_id']) as $index => $item)
                                            <th>{{$tipoPeriodo['ab']}} {{ $item }}</th>
                                        @endforeach
                                        <th>Promedio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($Gsas != null)
                                        @foreach ($Gsas as $index => $item)
                                            @foreach ($item['notas'] as $capacidadKey => $capacidadData)
                                                <tr>
                                                    @if ($loop->first)
                                                        <td rowspan="{{ count($item['notas']) }}">{{ $item['dni'] }}</td>
                                                        <td rowspan="{{ count($item['notas']) }}">{{ $item['alumno'] }}</td>
                                                    @endif
                                                    <td>{{ $capacidades[$capacidadKey] ?? $capacidadData['descripcion'] }}
                                                    </td>
                                                    @foreach (range(1, $tipoPeriodo['tp_id']) as $periodoIndex)

                                                        @php
                                                            $periodoKeyName = 'P' . $periodoIndex;
                                                            $notaData = $capacidadData['periodos'][$periodoKeyName] ?? [
                                                                'nota' => null,
                                                                'idNotaPadre' => -1,
                                                            ];
                                                            $nota = $notaData['nota'] ?? null;
                                                            $notaId = $notaData['idNotaPadre'] ?? -1;
                                                        @endphp
                                                        @if ($nota !== null)
                                                            <td>{{ $nota }}</td>
                                                        @else
                                                            <td>
                                                                <button type="button" class="btn btn-icon btn-sm"
                                                                    title="Registrar" data-toggle="modal" data-target="#myModal"
                                                                    data-alumno="{{ $item['idAlumno'] }}"
                                                                    data-capacidad="{{ $capacidadKey }}"
                                                                    data-sga="{{ $item['ags_id'] }}"
                                                                    data-periodo="{{ $item['periodoID'] }}"
                                                                    data-notaId="{{ $notaId }}"
                                                                    data-bimestre="{{ $periodoIndex }}">
                                                                    <i class="fa fa-edit text-secondary"></i>
                                                                </button>
                                                            </td>
                                                        @endif
                                                    @endforeach
                                                    <td>{{ $capacidadData['promedio'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="{{$tipoPeriodo['cantidad']+4}}">No hay datos disponibles para este periodo</td>
                                        </tr>
                                    @endif
                                </tbody>
                            @endif
                        </table>


                    </div>

                </div>
            </div>
        </div>

    </div>




    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form id="formNota">
                    <div class="modal-body">
                        <input type="text" name="" id="idAlumno" value="" hidden>
                        <input type="text" name="" id="idCapacidad" value="" hidden>
                        <input type="text" name="" id="idPeriodo" value="" hidden>
                        <input type="text" name="" id="idNota" value="" hidden>
                        <input type="text" name="" id="sgaId" value="" hidden>
                        <input type="text" name="" id="periodoId" value="" hidden>
                        <input type="text" name="" id="bimestre" value="" hidden>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Nota:
                                <span id="error" style="color: red;" hidden>
                                    <span class="   ">(error) Seleccione una nota </span>
                                </span>

                            </label>
                            <select class="form-control" name="" id="selectNota" required>
                                <option value="0">Selecteccione una opcion</option>
                                <option value="AD">AD</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>



                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="btnActualizar">Registrar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>







@endsection


@section('js')

    <script src="{{ asset('js/notas/form.js') }}"></script>


    <script>
        $('#myModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var alumno = button.data('alumno') // Extract info from data-* attributes
            var capacidad = button.data('capacidad') // Extract info from data-* attributes
            var periodo = button.data('periodo') // Extract info from data-* attributes
            var notaId = button.data('notaid') // Extract info from data-* attributes
            var sga = button.data('sga') // Extract info from data-* attributes
            var periodoId = button.data('periodoId') // Extract info from data-* attributes
            var bimestre = button.data('bimestre') // Extract info from data-* attributes
            console.log(bimestre);

            var modal = $(this)
            modal.find('.modal-title').text('Registrar Nota')
            modal.find('.modal-body #idAlumno').val(alumno);
            modal.find('.modal-body #idCapacidad').val(capacidad);
            modal.find('.modal-body #idPeriodo').val(periodo);
            modal.find('.modal-body #idNota').val(notaId);
            modal.find('.modal-body #sgaId').val(sga);
            modal.find('.modal-body #periodoId').val(periodoId);
            modal.find('.modal-body #bimestre').val(bimestre);



        })
    </script>


@endsection
