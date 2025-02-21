@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Alumno')
@section('content_header_subtitle', 'Editar Grado')



@section('content')

    <div class="container">
        <div class="container">

            <div class="tab-pane" id="seccion-add">
                <div class="card">
                    <form class="card-body"  method="POST" action="{{ route('gradoSeccion.secciongradoregister', $seccionAdd2) }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Periodo <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select name="periodo" class="form-control show-tick" required>
                                    <option value="0" selected disabled>-- Selecciona --</option>
                                    @foreach ($periodos as $periodo)
                                        <option value="{{ $periodo->per_id }}">
                                            {{ $periodo->anio->anio_descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Descripción <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="text" placeholder="Seccion 1, Seccion 2, Seccion 3 ..." class="form-control"
                                    name="descripcion" autofocus required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Grado <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                               <input type="text" name="grado" id="" class="form-control"   value="{{ $seccionAdd2->gra_descripcion }} de {{$seccionAdd2->nivel->niv_descripcion}}" readOnly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Tutor </label>
                            <div class="col-md-7">
                                <select   class="form-control show-tick" name="tutor">

                                    <option value="0" selected disabled>-- Selecciona --</option>
                                    @foreach ($tutores as $tutor)
                                        <option value="{{ $tutor->pa_id }}">
                                            {{ $tutor->persona->per_nombres }} {{ $tutor->persona->per_apellidos }}
                                        </option>
                                    @endforeach


                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Aula <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <select name="aula" class="form-control show-tick" required>
                                    {{-- {{ a.ala_descripcion }} - {{ a.ala_ubicacion }} --}}
                                    <option value="0" selected disabled>-- Selecciona --</option>
                                    @foreach ($aulas as $aula)
                                        <option value="{{ $aula->ala_id }}">
                                            {{ $aula->ala_descripcion }} - {{ $aula->ala_ubicacion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Nro Vacantes <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="number" placeholder="0" class="form-control" name="vacantes"
                                    autofocus required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label"></label>
                            <div class="col-md-7 text-right mt-4">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                                <a href="{{ route('gradoSeccion.inicio')}}" class="btn btn-outline-secondary">Cancelar</a>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>

@endsection
