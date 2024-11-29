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
                    <form method="GET" action="{{ route('asignarCurso.inicio') }}">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="inputCity">Nivel</label>
                            </div>
                            <div class="form-group col-md-8">
                                <select id="inputState" class="form-control" name="niv_id">
                                    @foreach ($nivel as $item)
                                        <option value="{{ $item->niv_id }}">{{ $item->niv_descripcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-1">
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
                                    <th class="d-none">#</th>
                                    <th></th>
                                    <th class="d-none">DNI</th>
                                    <th>DOCENTE</th>
                                    @foreach ($cursos as $curso)
                                        <th>{{ $curso->cur_abreviatura }}</th>
                                    @endforeach

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($docentes as $item)
                                    <tr>
                                        <td class="d-none">{{ $item['pa_id'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-icon btn-sm" title="Registrar"
                                                onclick="grabarAsignacion({{ $item['pa_id'] }})">
                                                <i class="fa fa-save text-info"></i>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-sm" title="Limpiar"
                                                onclick="eliminarAsignacion({{ $item['pa_id'] }})">
                                                <i class="fa fa-trash text-danger"></i>
                                            </button>
                                        </td>
                                        <td class="d-none">{{ $item['dni'] }}</td>
                                        <td>{{ $item['nombres'] }} {{ $item['apellidos'] }}</td>

                                        @foreach ($cursos as $curso)
                                            <td>
                                                <input type="checkbox" @if (in_array($curso['cur_nombre'], $item['checked'])) checked @endif
                                                    onclick="guardandoCursos({{ $item['pa_id'] }}, {{ $curso['id'] }})">
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
{{$docentes}}
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

    {{-- MODAL --}}
    <script>
        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            var title = button.data('title') // Extract info from data-* attributes
            var modalDataUse = document.getElementById('modalDataUse');

            modalDataUse.innerHTML = '';
            recipient.forEach((element, index) => {
                modalDataUse.innerHTML += `
                <div class="col-lg-4 col-md-6">
                    <div class="card"
                        style="color: black; background: linear-gradient(rgba(125, 183, 213, 0.5) 0%, rgba(175, 192, 223, 0.5) 100%);">

                        <div class="card-body text-center">
                            <h5>C${index+1}</h5>
                            <br>
                            <p class="m-2">${element.cap_descripcion}</p>
                        </div>
                    </div>
                </div>
                `;
            });




            var modal = $(this)
            modal.find('.modal-title').text('Capacidades de ' + title)
            modal.find('.modal-body input').val(recipient)
        })
    </script>
@endsection
