@extends('adminlte::page')
@section('title', 'Ambientes')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Ambiente')
@section('content_buttom')
    <a href="{{ route('ambiente.create') }}" class="btn btn-primary">Nuevo Ambiente</a>

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
                                <th scope="col">Descripcion</th>
                                <th scope="col">Tipo de ambiente</th>
                                <th scope="col">ubicacion</th>
                                <th scope="col">capacidad</th>
                                <th scope="col">Multiuso</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Opciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($aulas as $value => $info)
                                <tr>
                                    <th scope="row">{{ $value+1 }}</th>
                                    <td>{{ $info->ala_descripcion }}</td>
                                    <td>{{ $info->ala_tipo }}</td>
                                    <td>{{ $info->ala_ubicacion }}</td>
                                    <td>{{ $info->ala_aforo }}</td>
                                    <td>
                                        @if ($info->is_multiuse == 1)
                                            <span class="badge badge-success">Multiusos</span>
                                        @else
                                            <span class="badge badge-danger">Un solo uso </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($info->ala_estado == 1)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('ambiente.edit', $info) }}" class="text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('ambiente.destroy', $info) }}" method="POST"
                                            style="display: inline-block" class="form-eliminar">
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
