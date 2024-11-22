@extends('adminlte::page')
@section('title', 'Matricula')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Matricula')
@section('content_buttom')
    <a href="{{ route('matricula.create') }}" class="btn btn-primary">Nueva matricula</a>

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
                                <th scope="col">Alumno</th>
                                <th scope="col">Nivel</th>
                                <th scope="col">Grado</th>
                                <th scope="col">Aula</th>
                                <th scope="col">Fecha de matricula</th>
                                <th scope="col">Matriculado por</th>
                                <th scope="col">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($informacion as $value => $info)
                                <tr>
                                    <th scope="row">{{ $value +1 }}</th>
                                    <td>{{ $info['dni'] }}</td>
                                    <td>{{ $info['alumno'] }}</td>
                                    <td>{{ $info['nivel'] }}</td>
                                    <td>{{ $info['grado'] }}</td>
                                    <td>{{ $info['aula'] }}</td>
                                    <td>{{ $info['mat_fecha'] }}</td>
                                    <td>{{ $info['apoderado'] | $info['apoderado'] }} </td>
                                    <td>
                                        @if ($info['mat_estado'] == 1)
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
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
