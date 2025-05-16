@extends('adminlte::page')
@section('title', 'Rol')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Roles')
@section('content_buttom')
    <a href="{{ route('roles.create') }}" class="btn btn-primary">Nuevo Rol</a>

@endsection


@section('content')

    <div class="container">
        <div class="container">

            <div class="row row-cols-1 row-cols-md-4 g-4 p-4">
                @foreach ($roles as $value => $info)
                    <div class="col">

                        <div class="card text-bg-dark ">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title">{{ $info->name }}</h5>

                                <form action="{{ route('roles.destroy', $info) }}" method="POST"
                                    style="display: inline-block" class=" btn-danger rounded-circle form-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-light  btn btn-none" type="submit">
                                        <i class="fas fa-trash-alt"></i>

                                    </button>
                                </form>
                                <a href="{{ route('roles.edit', $info) }}"  style="display: inline-block" class=" btn-primary rounded-circle">
                                    <button class="text-light  btn btn-none" type="submit">


                                        <i class="fas fa-edit"></i>
                                    </button>
                                </a>

                            </div>
                            <div class="card-body  d-flex justify-content-between">
                                <h5 class="card-title">
                                    <span class="rounded-pill text-success ">
                                        <i class="fas fa-door-open"></i>
                                    </span>
                                    Estado
                                </h5>
                                <p class="card-text">
                                    @if ($info->rol_estado == 1)
                                        <span class="rounded-pill bg-success p-1">Disponible</span>
                                    @else
                                        <span class="rounded-pill bg-danger p-1">no disponible</span>
                                    @endif
                                </p>
                            </div>

                        </div>
                    </div>
                @endforeach


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
