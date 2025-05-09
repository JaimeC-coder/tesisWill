@extends('adminlte::page')
@section('title', 'Reportes')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Reportes por Alumno')



@section('content')
    <div>

        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane active" id="aula-all">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-6 col-sm-12">
                                        <form action="{{ route('reporte.alumno') }}" method="GET">

                                            <div class="input-group">
                                                <input type="text" name="buscar" class="form-control"
                                                    value=" {{ $dni ?? '' }}"
                                                    @isset($dni)   disabled @endisset
                                                    placeholder="Ingresar DNI del alumno" required />
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">

                                                        <i class="fa-solid fa-magnifying-glass"></i>
                                                        Buscar
                                                    </button>

                                                    <a href="
                                                 {{ route('reporte.alumno') }}"
                                                        class="btn btn-default" type="button">
                                                        Cancelar
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (isset($matricula))
                            <div id="listar-info" v-if="cargarInfo">
                                <div class="row">
                                    <div class="col-xl-4 col-md-12">
                                        <div class="card">

                                            <div class="media card-body ">
                                                <img class="mr-3 rounded-circle rounded-sm" width="64" height="64"
                                                    src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                                                    alt="Alumno">
                                                <div class="media-body">
                                                    <h4>{{ $alumno->persona->per_nombre_completo }}</h4>
                                                    <p class="text-muted m-b-0">{{ $alumno->persona->per_dni }}</p>
                                                    <input type="hidden" value="{{ $alumno->persona->per_id }}"
                                                        id="per_id">
                                                </div>
                                            </div>


                                        </div>
                                        <div class="card">
                                            <h3 class="card-header">Información Académica</h3>
                                            <div class="card-body">
                                                <div class="card-deck">
                                                    <div class="card text-center">
                                                        <div class="card-body">
                                                            <div class="card-title" style="float: none">Nivel</div>

                                                            <div class="card-text">{{ $nivel->niv_descripcion }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="card text-center">

                                                        <div class="card-body">
                                                            <div class="card-title" style="float: none">Grado</div>
                                                            <div class="card-text">{{ $grado->gra_descripcion }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="card text-center">

                                                        <div class="card-body">
                                                            <div class="card-title" style="float: none">Sección</div>
                                                            <div class="card-text">{{ $seccion->sec_descripcion }}</div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="card w-100">
                                                    <h3 class="card-header">Información Personal</h3>
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <b>Dirección</b>
                                                            <div>
                                                                {{ $alumno->persona->per_direccion == null ? 'Chepén' : $alumno->persona->per_direccion }}
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Fecha Nacimiento </b>
                                                            <div>{{ $alumno->persona->per_fecha_nacimiento }}</div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Sexo </b>
                                                            <div>
                                                                {{ $alumno->persona->per_sexo == 'F' ? 'Femenino' : 'Masculino' }}
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Correo </b>
                                                            <div>
                                                                {{ $alumno->persona->per_email == null ? 'No registrado' : $alumno->persona->per_email }}
                                                            </div>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <b>Celular </b>
                                                            <div>
                                                                {{ $alumno->persona->per_celular == null ? 'No registrado' : $alumno->persona->per_celular }}
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Reportes</h3>

                                            </div>
                                            <div class="card-body">
                                                <div class="timeline_item ">
                                                    <img class="tl_avatar" src="{{ asset('assets/images/informe.png') }}"
                                                        width="50" height="50" alt="" />
                                                    <span><b>Ficha de Matricula </b></span>
                                                    <div class="msg p-2">
                                                        <button id="generar_ficha_matricula"
                                                            class="mr-20 btn btn-outline-primary "><i
                                                                class="fa fa-folder-plus"></i> Generar</button>
                                                        <a target="_blank"
                                                            href="{{ route('reporte.alumno.matricula.pdf', ['per_id' => $alumno->persona->per_id]) }}"
                                                            class="mr-20 btn btn-outline-primary "><i
                                                                class="fa-solid fa-eye"></i> Ver</a>

                                                        <br>
                                                    </div>
                                                </div>

                                                <div class="timeline_item ">
                                                    <img class="tl_avatar" src="{{ asset('assets/images/informe.png') }}"
                                                        width="50" height="50" alt="" />
                                                    <span><b>Libreta de Notas </b></span>
                                                    <div class="msg p-2">
                                                        <button id="generar_libreta_notas"

                                                        class="mr-20 btn btn-outline-primary "><i class="fa fa-folder-plus"></i> Generar</button>
                                                        <a  target="_blank"
                                                            href="{{ route('reporte.alumno.libreta_notas.pdf', ['per_id' => $alumno->persona->per_id]) }}"
                                                            class="mr-20 btn btn-outline-primary "><i class="fa-solid fa-eye"></i> Ver</a>

                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div id="listar-info" v-else>
                                <div class="card-body text-center">
                                    <div class="display-1 text-muted mb-2">
                                        <img src={{ asset('assets/images/NotData.png') }} alt="error" width="30%">
                                        <h1 class="h3 mb-3">
                                            @if (isset($error))
                                                {{ $error }}
                                            @endif
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')
    {{-- <script src="{{ asset('js/reportes/general.js') }}"></script> --}}

    <script>
        let button = document.getElementById('generar_ficha_matricula');
        button.addEventListener('click', function() {
            alert('Generando PDF...');
            let per_id = document.getElementById('per_id').value;

            // Redirigir a la URL con los parámetros
            window.open('/api/reporte/alumno/matricula/pdf?per_id=' + per_id, '_blank');
        });
        let button2 = document.getElementById('generar_libreta_notas');
        button2.addEventListener('click', function() {
            alert('Generando PDF...');
            let per_id = document.getElementById('per_id').value;

            // Redirigir a la URL con los parámetros
            window.open('/api/reporte/alumno/libreta_notas/pdf?per_id=' + per_id, '_blank');
        });
        //obtener los datos de la url
    </script>
@endsection
