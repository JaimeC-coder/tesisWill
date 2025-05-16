@extends('adminlte::page')
@section('title', 'Curso')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Curso')
@section('content_buttom')
    <a href="{{ route('curso.create') }}" class="btn btn-primary">Nuevo Curso</a>
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
                                <th scope="col">Curso</th>
                                <th scope="col">Numero de capacidades</th>
                                <th scope="col">Cantidad de horas</th>
                                <th scope="col">Grado</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Opciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cursos as $value => $info)
                                <tr>
                                    <th scope="row">{{ $value + 1 }}</th>
                                    <td>{{ $info->cur_nombre }}</td>
                                    <td>
                                        {{ $info->capacidad->where('cap_is_deleted', '!=', 1)->count() }}
                                    </td>
                                    <td>{{ $info->cur_horas }}</td>

                                    <td>{{ $info->grado->gra_descripcion }}</td>

                                    <td>
                                        @if ($info->cur_estado == 1)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="text-secondary btn btn-none" data-toggle="modal"
                                            data-target="#exampleModal" data-whatever="{{ $info->capacidad }}"
                                            data-title="{{ $info->cur_nombre }}">
                                            <i class="fa fa-eye"></i></button>

                                        <a href="{{ route('curso.edit', $info) }}" class="text-warning btn btn-none">
                                            <i class="fas fa-edit"></i>

                                        </a>
                                        <form action="{{ route('curso.destroy', $info) }}" method="POST"
                                            style="display: inline-block"  class="form-eliminar">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-danger btn btn-none" type="submit">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New message</h5>
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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.form-eliminar');

        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Previene el envío inmediato

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Eliminar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Envía el formulario si se confirma
                    }
                });
            });
        });
    });
</script>

@endsection
