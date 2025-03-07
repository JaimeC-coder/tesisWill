@extends('adminlte::page')
@section('title', 'Grado y Sección')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Grado y Sección')
@section('content_buttom')
    <a href="{{ route('gradoSeccion.create') }}" class="btn btn-primary">Nueva Grado </a>

@endsection


@section('content')

    <div class="container">
        <div class="container">

            <div class="row p-2">
                <div class="table-responsive">
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nivel</th>
                                <th scope="col">Grado</th>
                                <th scope="col">Secciones</th>
                                <th scope="col">Curso</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Opciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($grados as $value => $info)
                                <tr>
                                    <th scope="row">{{ $value + 1 }}</th>
                                    <td>{{ $info->nivel->niv_descripcion }}</td>
                                    <td>{{ $info->gra_descripcion }}</td>
                                    <td>
                                        <button type="button" class="text-secondary btn btn-none" data-toggle="modal"
                                            data-target="#dynamicModal" data-whatever='@json($info->seccion)'
                                            data-title="Secciones de {{ $info->gra_descripcion }}"
                                            data-key="sec_descripcion">
                                            <i class="fa fa-eye"></i></button>
                                        {{ $info->seccion->where('is_deleted', '!=', 1)->count() }}
                                    </td>
                                    <td>
                                        <button type="button" class="text-secondary btn btn-none" data-toggle="modal"
                                            data-target="#dynamicModal" data-whatever='@json($info->curso)'
                                            data-title="Cursos asignados a {{ $info->gra_descripcion }}"
                                            data-key="cur_nombre">
                                            <i class="fa fa-eye"></i></button>
                                        {{ $info->curso->where('is_deleted', '!=', 1)->count() }}
                                    </td>

                                    <td>
                                        @if ($info->gra_estado == 1)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>

                                        <a href="{{ route('gradoSeccion.secciongrado', $info) }}"
                                            class="text-primary btn btn-none">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                        <a href="{{ route('gradoSeccion.edit', $info) }}" class="text-warning btn btn-none">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>




    <!-- Modal Reutilizable -->
    <div class="modal fade bd-example-modal-lg" id="dynamicModal" tabindex="-1" role="dialog"
        aria-labelledby="dynamicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dynamicModalLabel">Título dinámico</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row clearfix mt-2" id="modalDataUse">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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


    {{-- MODAL --}}
    <script>
        $(document).ready(function() {
            $('#dynamicModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var recipient = button.data('whatever');
                var title = button.data('title');
                var dataKey = button.data('key'); // Nuevo atributo para definir la clave de los datos

                var modalDataUse = document.getElementById('modalDataUse');
                console.log(recipient);
                modalDataUse.innerHTML = '';

                recipient.forEach((element, index) => {
                    let label = dataKey === 'sec_descripcion' ? 'Seccion' : 'Curso de ';
                    let label2 = dataKey === 'sec_descripcion' ? 'S' : 'C';
                    modalDataUse.innerHTML += `

                        <div class="  col-md-4 ">
                            <ul class="list-group" >
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                   <i class="fas fa-book"></i>
                                    ${label}  ${element[dataKey]}
                                </li>
                            </ul>
                        </div>





            `;
                });

                var modal = $(this);
                modal.find('.modal-title').text(title);
            });
        });
    </script>
@endsection
