@extends('adminlte::page')
@section('title', 'Periodo Académico')

@section('content_header', 'Inicio')
@section('content_header_title','Home')
@section('content_header_subtitle','Periodo Académico')
@section('content_buttom')
    <a href="{{ route('periodo.create') }}" class="btn btn-primary">Nuevo Periodo Académico
    </a>

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
                                <th scope="col">Periodo</th>
                                <th scope="col">Inicio de matricula</th>
                                <th scope="col">Fin de matricula</th>
                                <th scope="col">Tipo de registro de notas </th>
                                <th scope="col">Estado</th>
                                <th scope="col">Opciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periodos as $value => $info)
                                <tr>
                                    <th scope="row">{{ $value+1 }}</th>
                                    <td>{{ $info->anio->anio_descripcion }}</td>
                                    <td>{{ $info->per_inicio_matriculas }}</td>
                                    <td>{{ $info->per_final_matricula }}</td>
                                    <td>{{ $info->tipo->tp_tipo }}</td>
                                    <td>
                                        @if ($info->per_estado == 1)
                                        <span class="badge badge-success">{{$info->per_estado}}  Activo</span>
                                        @else
                                            <span class="badge badge-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('periodo.edit', $info) }}" class="text-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('periodo.destroy', $info) }}" method="POST"
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
