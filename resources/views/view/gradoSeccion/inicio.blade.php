@extends('adminlte::page')
@section('title', 'Grado y Sección')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Grado y Sección')
@section('content_buttom')
    <a href="{{ route('anio.create') }}" class="btn btn-primary">Nueva Curso (falta)</a>

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
                                    <th scope="row">{{ $value+1 }}</th>
                                    <td>{{ $info->nivel->niv_descripcion }}</td>
                                    <td>{{ $info->gra_descripcion }}</td>
                                    <td>

                                        <a href="{{ route('ambiente.edit', $info) }}" class="text-secondary btn btn-none">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{ $info->seccion->where('is_deleted', '!=', 1)->count() }}
                                    </td>
                                    <td>
                                        <a href="{{ route('ambiente.edit', $info) }}" class="text-secondary btn btn-none">
                                            <i class="fas fa-list"></i>
                                        </a>
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

                                        <a href="{{ route('gradoSeccion.edit', $info) }}" class="text-primary btn btn-none">
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
