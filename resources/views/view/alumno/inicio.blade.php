@extends('adminlte::page')
@section('title', 'Alumnos')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Alumno')
@section('content_buttom')
    <a href="{{ route('anio.create') }}" class="btn btn-primary">Nueva Alumno (falta)</a>

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
                                <th scope="col">DNI</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Apoderado</th>
                                <th scope="col">Parentesco</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Opciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($alumnos as $value => $info)
                                <tr>
                                    <th scope="row">{{ $value + 1 }}</th>
                                    <td>
                                        {{ $info->persona->per_dni }}
                                    </td>
                                    <td>
                                        {{ $info->persona->per_nombres }}

                                    </td>
                                    <td>
                                        {{ $info->persona->per_apellidos }}
                                    </td>
                                    <td>

                                    {{ $info->apoderado->persona->per_apellidos }}
                                        {{ $info->apoderado->persona->per_nombres }}
                                    </td>
                                    <td>{{ $info->apoderado->apo_parentesco }}</td>

                                    <td>
                                        @if ($info->alu_estado == 1)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>

                                        <a href="{{ route('alumno.destroy', $value) }}" class="text-secundary btn btn-none">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('alumno.destroy', $value) }}" method="POST"
                                            style="display: inline-block">
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
@endsection
