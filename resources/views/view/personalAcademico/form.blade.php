<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información Básica</h3>
            <div class="card-options ">
                <a href="#" class="card-options-collapse d-none" data-toggle="card-collapse"><i
                        class="fe fe-chevron-up"></i></a>
                <a href="#" class="card-options-remove d-none" data-toggle="card-remove"><i
                        class="fe fe-x"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <input type="hidden" value="{{ $personal->persona->per_id ?? '' }}" name="per_id"
                            id="per_id">
                        <div class="d-flex justify-content-between">
                            <label>DNI <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="on" id="flexCheckDefault"
                                    name="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault" id="flexCheckDefaultlabel">
                                    Ingreso manual?
                                </label>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" value="{{ $personal->persona->per_dni ?? '' }}"
                                @if (empty($personal->persona?->per_dni)) data-unic="true" @endif class="form-control"
                                data-type="numbers" data-length="8" data-required="true" name="per_dni" id="per_dni"
                                autofocus>
                            <div class="input-group-append">
                                <button id="buscarDni" class="btn btn-primary" type="button" disabled>
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
                        <input type="text" data-required="true" data-type="letters" class="form-control"
                            id="per_nombres" value="{{ $personal->persona->per_nombres ?? '' }}" name="per_nombres"
                            @if (isset($personal->persona->per_nombres)) disabled @endif>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Apellidos <span class="text-danger">*</span></label>
                        <input type="text" data-required="true" data-type="letters" class="form-control"
                            id="per_apellidos" value="{{ $personal->persona->per_apellidos ?? '' }}"
                            @if (isset($personal->persona->per_apellidos)) disabled @endif name="per_apellidos">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                 <div class="form-group">
                    <label>Sexo <span class="text-danger">*</span></label>
                    <select data-required="true" name="per_sexo" class="form-control show-tick" id="per_sexo">
                        <option value="0" disabled>-- Selecciona --</option>
                        @foreach ($sexo as $key => $tipo)
                            <option value="{{ $key }}" @if (isset($personal->persona) && $personal->persona->per_sexo == $key) selected @endif>
                                {{ $tipo }}</option>
                        @endforeach
                    </select>
                 </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Fecha de Nacimiento </label>
                        <input type="date" data-required="true" min="1777-01-01" max="2025-12-31" value="{{ $personal->persona->per_fecha_nacimiento ?? '' }}"
                            class="form-control" name="per_fecha_nacimiento" id="per_fecha_nacimiento">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label>Estado Civil </label>
                    <div class="form-group">

                        <select data-required="true" class="form-control show-tick" id="per_estado_civil" name="per_estado_civil">
                            <option value="0" disabled>-- Selecciona --</option>
                            @foreach ($estadoCivil as $tipo)
                                <option value="{{ $tipo }}" @if (isset($personal->persona) && $personal->persona->per_sexo == $tipo) selected @endif>
                                    {{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>País <span class="text-danger">*</span></label>
                        <input type="text" data-required="true" data-type="letters" class="form-control"
                            value="{{ $personal->persona->per_pais ?? '' }}" name="per_pais" id="per_pais">
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <label>Departamento <span class="text-danger">*</span></label>
                    <div class="form-group">

                        <select data-required="true" class="form-control show-tick" id="departamento" name="per_departamento">
                            <option value="0" selected disabled>-- Selecciona --</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{ $departamento->idDepa }}">{{ $departamento->departamento }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">

                        <label>Provincia <span class="text-danger">*</span></label>
                        <select data-required="true" id="provincia" class="form-control show-tick" disabled name="per_provincia">
                            <option value="0" selected disabled>-- Selecciona --</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Distrito <span class="text-danger">*</span></label>
                    <select data-required="true" id="distrito" class="form-control show-tick" disabled name="per_distrito">
                        <option value="0" selected disabled>-- Selecciona --</option>

                    </select>
                    </div>

                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Dirección </label>
                        <input type="text" data-required="true" class="form-control"
                            value="{{ $personal->persona->per_direccion ?? '' }}" id="per_direccion"
                            name="per_direccion">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Celular</label>
                        <input type="number" class="form-control" data-type="numbers" data-length="9"
                            data-required="true" value="{{ $personal->persona->per_celular ?? 'No registrado' }}"
                            id="per_celular" name="per_celular" minlength="9" maxlength="9">
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Correo </label>
                        <input type="email" class="form-control" value="{{ $personal->persona->per_email ?? '' }}"
                            id="per_email" name="per_email" data-required="true"
                            @if (empty($alumno->persona?->per_email)) data-validate-correo="true"  data-unic="true" @endif>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información Académica</h3>
            <div class="card-options ">
                <a href="#" class="card-options-collapse d-none" data-toggle="card-collapse"><i
                        class="fe fe-chevron-up"></i></a>
                <a href="#" class="card-options-remove d-none" data-toggle="card-remove"><i
                        class="fe fe-x"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row clearfix">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">

                        <label>Nivel <span class="text-danger">*</span></label>
                        <select data-required="true" class="form-control show-tick" name="niv_id">
                            <option value="0" selected disabled>-- Selecciona --</option>
                            @foreach ($niveles as $nivel)
                                <option value="{{ $nivel->niv_id }}" @if ($personal->niv_id == $nivel->niv_id) selected @endif>
                                    {{ $nivel->niv_descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Turno <span class="text-danger">*</span></label>
                        <input id="pa_turno" name="pa_turno" type="text" data-required="true"
                            class="form-control" value="{{ $personal->pa_turno ?? '' }}" aria-invalid="true">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">

                        <label>Rol <span class="text-danger">*</span></label>
                        <select data-required="true" class="form-control show-tick" name="rol_id" id="rol_id">
                            <option value="0" selected disabled>-- Selecciona --</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->rol_id }}" @if ($personal->rol_id == $rol->rol_id) selected @endif>
                                    {{ $rol->rol_descripcion }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Condición Laboral <span class="text-danger">*</span></label>
                        <input id="pa_condicion_laboral" name="pa_condicion_laboral" type="text"
                            data-required="true" data-type="letters"
                            value="{{ $personal->pa_condicion_laboral ?? '' }}" class="form-control"
                            aria-invalid="true">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Especialidad <span class="text-danger">*</span></label>
                        <input id="pa_especialidad" name="pa_especialidad" type="text" data-required="true"
                            data-type="letters" value="{{ $personal->pa_especialidad ?? '' }}" class="form-control"
                            aria-invalid="true" autofocus>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">

                        <label>Tutor <span class="text-danger">*</span></label>
                        <select data-required="true" name="pa_is_tutor" class="form-control show-tick">
                            <option value="0">-- Selecciona --</option>
                            @foreach ($tutor as $key => $tipo)
                                <option value="{{ $key }}" @if (isset($personal) && $personal->pa_is_tutor == $key) selected @endif>
                                    {{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-12 text-right mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $personal->per_id ? 'Actualizar' : 'Registrar' }}
                    </button>
                    <a href="{{ route('personal.inicio') }}" class="btn btn-outline-secondary">Cancelar</a>

                </div>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script src="{{ asset('js/informacionUsuario.js') }}"></script>
    <script src="{{ asset('js/distrito.js') }}"></script>
    <script src="{{ asset('js/validate.js') }}"></script>
@endsection
