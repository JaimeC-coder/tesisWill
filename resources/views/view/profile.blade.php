@extends('adminlte::page')
@section('title', 'Perfil')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Perfil')


@section('content')
    @php
        $user = auth()->user();
        $user->load('roles');
        $roles = $user->roles;
        $roleNames = $roles->pluck('name')->toArray();
        $roleNamesString = implode(', ', $roleNames);

    @endphp

    <div class="container"></div>
    <container>
        <div class="row">
            <div class="col-md-12">

                <div class="card mb-3">

                    <img src="{{ asset('assets/images/ie.jpg') }}" class="card-img-top " alt="..." width="100%"
                        height="300px">
                    <div class="card-body">
                        <h5 class="card-title">{{ $user->persona->per_nombres }} {{ $user->persona->per_apellidos }} </h5>
                        <p class="card-text">
                            Rol designado : {{ $roleNamesString }} <br>
                            Correo : {{ $user->email }} <br>
                            Telefono : {{ $user->persona->per_telefono ?? 'No registrado' }} <br>

                        </p>
                        <p class="card-text float-right"><small class="text-muted">
                                {{ $user->created_at->diffForHumans() }} <br>
                            </small></p>
                    </div>
                </div>

            </div>
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        Informacion adicional persona
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        Informacion personal
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"> Fecha de Nacimiento :
                                            {{ $user->persona->per_fecha_nacimiento }}
                                        </li>
                                        <li class="list-group-item"> Genero : {{ $user->persona->per_sexo }}</li>
                                        <li class="list-group-item"> Estado Civil : {{ $user->persona->per_estado_civil }}
                                        </li>

                                    </ul>
                                </div>

                            </div>
                            <div class="col-md-6">

                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        Informacion Vivienda
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">Direccion :
                                            {{ $user->persona->per_direccion ?? 'No Registrada' }}
                                        </li>
                                        <li class="list-group-item"> Ciudad : {{ $user->persona->distrito->distrito }}</li>
                                        <li class="list-group-item"> Departamento :
                                            {{ $user->persona->departamento->departamento }}</li>
                                        <li class="list-group-item"> Pais : {{ $user->persona->per_pais }}
                                        </li>
                                    </ul>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>

            @switch($roleNamesString)
                @case('Alumno')
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Informacion adicional del alumno
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                Informacion Aula , Grado y Seccion registrada actualmente
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"> Aula :
                                                    {{ $user->persona->alumno->matricula->last()->gsa->aula->ala_descripcion }}
                                                </li>
                                                <li class="list-group-item"> Grado :
                                                    {{ $user->persona->alumno->matricula->last()->gsa->grado->gra_descripcion }}
                                                </li>
                                                <li class="list-group-item"> Seccion :
                                                    {{ $user->persona->alumno->matricula->last()->gsa->seccion->sec_descripcion }}
                                                </li>

                                            </ul>
                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                Informacion lista de Matricua
                                            </div>
                                            <ul class="m-2">
                                                @foreach ($user->persona->alumno->matricula as $matriculass)
                                                    <li>
                                                        {{ $matriculass->periodo->anio->anio_descripcion }}
                                                    </li>
                                                @endforeach


                                            </ul>
                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                Informacion del Apodero
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">Nombres :
                                                    {{ $user->persona->alumno->apoderado->persona->per_nombres }}
                                                    {{ $user->persona->alumno->apoderado->persona->per_apellidos }}

                                                </li>
                                                <li class="list-group-item"> Vive con el estudiante :
                                                    {{ $user->persona->alumno->apoderado->apo_vive_con_estudiante }}</li>
                                                <li class="list-group-item"> Parentesco :
                                                    {{ $user->persona->alumno->apoderado->apo_parentesco }}
                                                </li>

                                            </ul>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @break

                @case('Docente')
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Informacion adicional persona
                            </div>
                            <div class="card-body">
                                Usted es un Administrador del sistema, tiene acceso a todas las funcionalidades y
                                configuraciones del sistema.
                                <br>

                                No cuenta con mas informacion adicional.
                            </div>
                        </div>






                    </div>
                @break

                @case('Auxiliar')
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Informacion adicional persona
                            </div>
                            <div class="card-body">
                                Usted es un personal de tipo auxilir del sistema, cuenta con acceso limitado a las funciones 
                                <br>

                                No cuenta con mas informacion adicional.
                            </div>
                        </div>






                    </div>
                @break

                @case('Secretaria')
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Informacion adicional persona
                            </div>
                            <div class="card-body">
                               Usted es la secretaria de la institucion educativa, tiene acceso a todas las funcionalidades y
                                configuraciones del sistema.
                                <br>


                            </div>
                        </div>






                    </div>
                @break

                @case('Director')
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Informacion adicional del director
                            </div>
                            <div class="card-body">
                                Usted es el Encardado de la institucion educativa, tiene acceso a todas las funcionalidades y
                                configuraciones del sistema.
                                <br>
                            </div>
                        </div>






                    </div>
                @break

                @case('Apoderado')
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Informacion adicional del apoderado
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                Lista de alumnos a su cargo con su parentesco
                                            </div>
                                            <ul class="list-group list-group-flush">

                                                @foreach ($user->persona->apoderado->alumno as $alumnos)
                                                    <li class="list-group-item">
                                                        <strong>Alumno:</strong>
                                                        {{ $alumnos->persona->per_nombres }}
                                                        {{ $alumnos->persona->per_apellidos }}
                                                        -
                                                        <strong>Parentesco:</strong>
                                                        {{ $user->persona->alumno->apoderado->apo_parentesco }}
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>

                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                @break

                @case('Administrador')
                    <div class="col-md-12">

                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Informacion adicional persona
                            </div>
                            <div class="card-body">
                                Usted es un Administrador del sistema, tiene acceso a todas las funcionalidades y
                                configuraciones del sistema.
                                <br>

                                No cuenta con mas informacion adicional.
                            </div>
                        </div>






                    </div>
                @break

            @endswitch

        </div>
    </container>
    </div>




@endsection




@section('js')
    <script></script>
@endsection
