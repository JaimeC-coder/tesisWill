@extends('adminlte::page')
@section('title', 'Personal Académico')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Personal Académico')
@section('content_buttom')
    <a href="{{ route('personal.create') }}" class="btn btn-primary">Nuevo Personal Académico</a>

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
                                <th scope="col">Nombre</th>
                                <th scope="col">Turno</th>
                                <th scope="col">Nivel</th>
                                <th scope="col">Especialidad</th>
                                <th scope="col">Rol</th>
                                <th scope="col">Tutor</th>
                                <th scope="col">Opciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($personal as $value => $info)
                                <tr>
                                    <th scope="row">{{ $value+1 }}</th>
                                    <td>{{ $info->persona->per_nombres}} {{  $info->persona->per_apellidos}}</td>
                                    <td>{{ $info['pa_turno'] }}</td>
                                    <td>{{ $info->nivel->niv_descripcion }}</td>
                                    <td>{{ $info['pa_especialidad'] }}</td>
                                    <td>{{ $info->rol->rol_descripcion }}</td>
                                    <td>
                                        @if ($info['pa_is_tutor'] == 1)
                                            <span class="badge badge-success">Si</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('personal.edit', $info) }}" class="text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('personal.destroy', $info) }}" method="POST"
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
