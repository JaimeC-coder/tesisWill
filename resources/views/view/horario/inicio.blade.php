@extends('adminlte::page')
@section('title', 'Horarios')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Registrar Horario')



@section('content')

    <div class="container">
        <div class="container">


            <div class="tab-pane active" id="horario-all">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4">
                            <label class="col-md-1 col-form-label">Año </label>
                            <div class="col-md-3">
                                <input type="hidden" name="xd" id="xd" value="{{ $user }}">
                                <select id="anio" name="anio" class="form-control show-tick" disabled>
                                    <option value="0" selected disabled>-- Selecciona --</option>
                                    @foreach ($anios as $anio)
                                        <option value="{{ $anio->anio_id }}">{{ $anio->anio_descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1"></div>
                            <label class="col-md-1 col-form-label">Nivel </label>
                            <div class="col-md-3">
                                <select id="nivel" name="nivel" class="form-control show-tick" disabled>
                                    <option value="0" selected disabled>-- Selecciona --</option>

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-1 col-form-label">Grado </label>
                            <div class="col-md-3">
                                <select id="grado" name="grado" class="form-control show-tick" disabled>
                                    <option value="0" selected disabled> No hay grados registrados </option>

                                </select>
                            </div>
                            <div class="col-md-1"></div>
                            <label class="col-md-1 col-form-label">Sección </label>
                            <div class="col-md">
                                <select id="seccion" name="seccion" class="form-control show-tick" disabled>
                                    <option selected disabled value="0"> No hay secciones registrados </option>

                                </select>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-lg-2 col-md-4 col-sm-6" style="display: flex;align-items: center;">
                                <button id="btnHorario" class="btn btn-sm btn-primary btn-block" disabled>Buscar</button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card " id="mostrar-info" hidden>
                    <div class="card-body">
                        <div class="row mb-4">


                            @can('panel.horario.registro')
                                <div class="col-md-3 p-4">


                                    <form class="card p-3">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-6 col-sm-12">
                                                <div class="form-group"><label>Curso</label>
                                                    <select id="cur_id" class="form-control show-tick">
                                                        <option value="0" selected disabled>-- Selecciona --</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-6 col-sm-12">
                                                <div class="form-group"><label>Fecha</label>
                                                    <select id="SelectDia" class="form-control show-tick">
                                                        <option value="0" selected disabled>-- Selecciona --</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-6 col-sm-12">
                                                <div class="form-group"><label>Hora Inicio</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control timepicker" id="hora_inicio"
                                                            autofocus required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-6 col-sm-12">
                                                <div class="form-group"><label>Hora Fin</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control timepicker" id="hora_fin"
                                                            autofocus required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-6 col-sm-12">
                                                <div class="form-group"><label>Color</label>
                                                    <div class="input-group mb-3">
                                                        <select id="color" class="form-control show-tick" required>
                                                            <option value="0" selected disabled>-- Selecciona --</option>
                                                            <option value="red">Rojo</option>
                                                            <option value="blue">Azul</option>
                                                            <option value="green">Verde</option>
                                                            <option value="purple">Morado</option>
                                                            <option value="orange">Naranja</option>
                                                            <option value="turquiose">Turquesa</option>
                                                            <option value="brown">Marrón</option>
                                                            <option value="black">Negro</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-6 col-sm-12"
                                                style="display: flex;align-items: center;justify-content: center;">
                                                <button type="" class="btn btn-primary"
                                                    id="btnregister">Registrar</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9">
                                    <div id='calendar'></div>

                                </div>
                            @else
                                <div class="col-md-12">
                                    <div id='calendar'></div>

                                </div>
                            @endcan

                            {{-- si no tiene el permiso que la cladse cambie  --}}



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <script src="{{ asset('js/horario/horario.js') }}"></script>
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

            $('.timepicker').timepicker({
                timeFormat: 'h:mm p',
                interval: 30,
                minTime: '7',
                maxTime: '1:00pm',
                defaultTime: '11',
                startTime: '10:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });
    </script>

@endsection
