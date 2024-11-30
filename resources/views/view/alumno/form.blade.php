<div class="row clearfix">
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información Básica</h3>
                </div>
                <div class="card-body">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <input type="hidden" name="per_id_Alumno" id="per_id_Alumno" value="{{$alumno->persona->per_id?? ''}}">
                                    <input type="hidden" name="per_id_Apoderado" id="per_id_Apoderado" value="{{$alumno->apoderado->persona->per_id?? ''}}">

                                    <input type="hidden"  id="per_provincia_Alumno_hidden" value="{{$alumno->persona->per_provincia ?? ''}}">
                                    <input type="hidden"  id="per_distrito_Alumno_hidden" value="{{$alumno->persona->per_distrito ?? ''}}">
                                    <input type="hidden" id="per_provincia_Apoderado_hidden" value="{{$alumno->apoderado->persona->per_provincia?? ''}}">
                                    <input type="hidden"  id="per_distrito_Apoderado_hidden" value="{{$alumno->apoderado->persona->per_distrito?? ''}}">

                                    <label>DNI <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" v-model="enableStudentField"
                                            type="checkbox" value="on" id="flexCheckDefaultAlumno">
                                        <label class="form-check-label text-info"
                                            for="flexCheckDefault">
                                            Ingreso manual?
                                        </label>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control"
                                        minlength="8" maxlength="8" required value="{{$alumno->persona->per_dni?? ''}}" name="per_dni_Alumno" id="per_dni_Alumno">
                                    <div class="input-group-append">
                                        <button id="buscarDni"
                                            class="btn btn-primary" type="button" disabled>
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            Buscar
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required value="{{$alumno->persona->per_nombres?? ''}}" name="per_nombres_Alumno" id="per_nombres_Alumno" disabled>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Apellidos <span class="text-danger">*</span></label>
                                <input type="text" v-model="persona.apellidos" class="form-control"
                                    required value="{{$alumno->persona->per_apellidos?? ''}}" id="per_apellidos_Alumno" name="per_apellidos_Alumno" disabled>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Sexo <span class="text-danger">*</span></label>
                            <select class="form-control show-tick" required id="per_sexo_Alumno" name="per_sexo_Alumno">
                                <option value="0" disabled>-- Selecciona --</option>
                                @foreach ($sexo as $key => $tipo)
                                <option value="{{ $key }}" @if (isset($alumno->persona) && $alumno->persona->per_sexo == $key) selected @endif>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Fecha de Nacimiento </label>
                                <input type="date" class="form-control" required value="{{$alumno->persona->per_fecha_nacimiento ?? ''}}" id="per_fecha_nacimiento_Alumno" name="per_fecha_nacimiento_Alumno">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Estado Civil </label>
                            <select class="form-control show-tick" required id="per_estado_civil_Alumno" name="per_estado_civil_Alumno">
                                <option value="0" disabled>-- Selecciona --</option>
                                @foreach ($estadoCivil as $key => $tipo)
                                <option value="{{ $key }}" @if (isset($alumno->persona) && $alumno->persona->per_estado_civil == $key) selected @endif>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>País <span class="text-danger">*</span></label>
                                <input type="text"   class="form-control"
                                    required value="{{$alumno->persona->per_pais?? ''}}" id="per_pais_Alumno" name="per_pais_Alumno"disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Departamento </label>
                            <select  class="form-control show-tick" required id="per_departamento_Alumno" name="per_departamento_Alumno">
                                <option value="0" selected disabled>-- Selecciona --</option>
                                @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->idDepa }}"  @if (isset($alumno->persona) && $alumno->persona->per_departamento == $departamento->idDepa) selected @endif>{{ $departamento->departamento }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Provincia </label>
                            <select  class="form-control show-tick" required id="per_provincia_Alumno" name="per_provincia_Alumno" disabled>
                                <option value="0" selected disabled>-- Selecciona --</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Distrito </label>
                            <select  class="form-control show-tick" required id="per_distrito_Alumno" name="per_distrito_Alumno" disabled>
                                <option value="0" selected disabled>-- Selecciona --</option>

                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Dirección </label>
                                <input type="text" class="form-control" required value="{{$alumno->persona->per_direccion?? ''}}" id="per_direccion_Alumno" name="per_direccion_Alumno">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="number" class="form-control" required value="{{$alumno->persona->per_celular ?? ''}}" id="per_celular_Alumno" name="per_celular_Alumno" minlength="9" maxlength="9">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Correo </label>
                                <input type="email" class="form-control" required value="{{$alumno->persona->per_email?? ''}}" id="per_email_Alumno" name="per_email_Alumno">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información Apoderado</h3>
                    <div class="card-options ">
                        <a href="#" class="card-options-collapse d-none"
                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                        <a href="#" class="card-options-remove d-none" data-toggle="card-remove"><i
                                class="fe fe-x"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <label>DNI <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input"type="checkbox" value="on" id="flexcheckApoderado">
                                        <label class="form-check-label text-info" for="flexcheckApoderado">
                                            Ingreso manual?
                                        </label>
                                    </div>
                                </div>

                                <div class="input-group mb-3">
                                    <input type="number" value="{{$alumno->apoderado->persona->per_dni?? ''}}" name="per_dni_Apoderado" id="per_dni_Apoderado"
                                        class="form-control" minlength="8" maxlength="8">
                                    <div class="input-group-append">
                                        <button id="buscarDniApoderda"
                                            class="btn btn-primary" type="button" disabled>
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            Buscar
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required value="{{$alumno->apoderado->persona->per_nombres?? ''}}" id="per_nombres_Apoderado" name="per_nombres_Apoderado"disabled>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required value="{{$alumno->apoderado->persona->per_apellidos?? ''}}" id="per_apellidos_Apoderado" name="per_apellidos_Apoderado" disabled>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Sexo <span class="text-danger">*</span></label>
                            <select  class="form-control show-tick" required id="per_sexo_Apoderado" name="per_sexo_Apoderado">
                                <option value="0" disabled>-- Selecciona --</option>
                                @foreach ($sexo as $key => $tipo)
                                <option value="{{ $key }}" @if (isset($alumno->apoderado->persona) && $alumno->apoderado->persona->per_sexo == $key) selected @endif>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Fecha de Nacimiento </label>
                                <input type="date"  class="form-control" required value="{{$alumno->apoderado->persona->per_fecha_nacimiento ?? ''}}" id="per_fecha_nacimiento_Apoderado" name="per_fecha_nacimiento_Apoderado">

                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label>Estado Civil </label>
                            <select class="form-control show-tick" required id="per_estado_civil_Apoderado" name="per_estado_civil_Apoderado">
                                <option value="0" disabled>-- Selecciona --</option>
                                @foreach ($estadoCivil as $key => $tipo)
                                <option value="{{ $key }}" @if (isset($alumno->apoderado->persona) && $alumno->apoderado->persona->per_sexo == $key) selected @endif>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>País <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" required value="{{$alumno->apoderado->persona->per_pais ?? ''}}" id="per_pais_Apoderado" name="per_pais_Apoderado"  disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Departamento </label>
                            <select  class="form-control show-tick" required id="per_departamento_Apoderado" name="per_departamento_Apoderado">
                                <option value="0" selected disabled>-- Selecciona --</option>
                                @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->idDepa }}" @if (isset($alumno->apoderado->persona) && $alumno->apoderado->persona->per_departamento == $departamento->idDepa) selected @endif>{{ $departamento->departamento }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Provincia </label>
                            <select class="form-control show-tick" required id="per_provincia_Apoderado" name="per_provincia_Apoderado"disabled>
                                <option value="0" selected disabled>-- Selecciona --</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>Distrito </label>
                            <select  class="form-control show-tick" required id="per_distrito_Apoderado" name="per_distrito_Apoderado" disabled>
                                <option value="0" selected disabled>-- Selecciona --</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Dirección </label>
                                <input type="text" class="form-control" required value="{{$alumno->apoderado->persona->per_direccion ?? ''}}" id="per_direccion_Apoderado" name="per_direccion_Apoderado">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="number" class="form-control" required value="{{$alumno->apoderado->persona->per_celular ?? ''}}" id="per_celular_Apoderado" name="per_celular_Apoderado" minlength="9" maxlength="9">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Correo </label>
                                <input type="email"class="form-control" required value="{{$alumno->apoderado->persona->per_email?? ''}}" id="per_email_Apoderado" name="per_email_Apoderado">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label>Parentesco <span class="text-danger">*</span></label>
                            <select class="form-control show-tick" required id="per_parentesco_Apoderado" name="per_parentesco_Apoderado">
                                <option value="0" disabled>-- Selecciona --</option>
                                @foreach ($parentesco as $key => $tipo)
                                <option value="{{ $key }}" @if (isset($alumno->apoderado) && $alumno->apoderado->apo_parentesco == $key) selected @endif>{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Vive con el estudiante</label>
                                <select class="form-control show-tick" required id="per_vive_con_estudiante_Apoderado" name="per_vive_con_estudiante_Apoderado">

                                    <option value="0" disabled>-- Selecciona --</option>
                                    @foreach ($vive as $key => $tipo)
                                    <option value="{{ $key }}" @if (isset($alumno->apoderado) && $alumno->apoderado->apo_vive_con_estudiante == $key) selected @endif>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 text-right mt-4">
                            <button v-if="!loading" type="submit"
                                class="btn btn-primary">Registrar</button>
                                <a href="{{ route('alumno.inicio')}}" class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>


@section('js')
    <script src="{{ asset('js/alumno/infromacionAlumnoApoderado.js') }}"></script>
    <script src="{{ asset('js/alumno/distrito.js') }}"></script>
@endsection
