<div class="row clearfix">
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
                            <input type="hidden" value="{{ $usuario->persona->per_id ?? '' }}" name="per_id"
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
                                <input type="text" value="{{ $usuario->persona->per_dni ?? '' }}"
                                    class="form-control" data-type="numbers" data-length="8" data-required="true"
                                    name="per_dni" id="per_dni" autofocus>
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
                            <input type="text"data-required="true" data-type="letters" class="form-control"
                                id="per_nombres" value="{{ $usuario->persona->per_nombres ?? '' }}" disabled
                                name="per_nombres">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Apellidos <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="per_apellidos" data-required="true"
                                data-type="letters" value="{{ $usuario->persona->per_apellidos ?? '' }}" disabled
                                name="per_apellidos">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Sexo <span class="text-danger">*</span></label>
                            <select name="per_sexo" class="form-control show-tick" id="per_sexo" data-required="true">
                                <option value="0" disabled>-- Selecciona --</option>
                                @foreach ($sexo as $key => $tipo)
                                    <option value="{{ $key }}"
                                        @if (isset($usuario->persona) && $usuario->persona->per_sexo == $key) selected @endif>
                                        {{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Fecha de Nacimiento </label>
                            <input type="date" value="{{ $usuario->persona->per_fecha_nacimiento ?? '' }}"
                                class="form-control" data-required="true" min="1777-01-01" max="2025-12-31"
                                name="per_fecha_nacimiento" id="per_fecha_nacimiento">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Estado Civil </label>
                            <select data-required="true" class="form-control show-tick" id="per_estado_civil"
                                name="per_estado_civil">
                                <option value="0" disabled>-- Selecciona --</option>
                                @foreach ($estadoCivil as $key => $tipo)
                                    <option value="{{ $key }}"
                                        @if (isset($usuario->persona) && $usuario->persona->per_sexo == $key) selected @endif>
                                        {{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>País <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" data-required="true" data-type="letters"
                                value="{{ $usuario->persona->per_pais ?? '' }}" disabled name="per_pais"
                                id="per_pais">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Departamento <span class="text-danger">*</span></label>
                            <select data-required="true" class="form-control show-tick" id="departamento"
                                name="per_departamento">
                                <option value="0" selected disabled>-- Selecciona --</option>
                                @foreach ($departamentos as $departamento)
                                    <option value="{{ $departamento->idDepa }}">{{ $departamento->departamento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Provincia <span class="text-danger">*</span></label>
                            <select data-required="true" id="provincia" class="form-control show-tick" disabled
                                name="per_provincia">
                                <option value="0" selected disabled>-- Selecciona --</option>
                            </select>
                        </div>

                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Distrito <span class="text-danger">*</span></label>
                            <select data-required="true" id="distrito" class="form-control show-tick" disabled
                                name="per_distrito">
                                <option value="0" selected disabled>-- Selecciona --</option>

                            </select>
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Dirección </label>
                            <input type="text" class="form-control" data-required="true"
                                value="{{ $usuario->persona->per_direccion ?? '' }}" id="per_direccion"
                                name="per_direccion">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Celular</label>
                            <input type="text" value="{{ $usuario->persona->per_celular ?? '' }}"
                                class="form-control"data-type="numbers" data-length="9" data-required="true"
                                id="per_celular" name="per_celular" autofocus>


                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Correo </label>
                            <input type="email" class="form-control"
                                value="{{ $usuario->persona->per_email ?? '' }}" id="per_email" name="per_email"
                                data-required="true"
                                @if (!empty($alumno->apoderado->persona?->per_email))   data-validate-correo="true"  @endif>
                            <input type="hidden" id="emailhidden" name="emailhidden">
                            <input type="hidden" id="nameUserhidden" name="nameUserhidden">

                            <input type="hidden" id="paishidden" name="paishidden">
                            <input type="hidden" id="apellidoshidden" name="apellidoshidden">
                            <input type="hidden" id="nombreshidden" name="nombreshidden">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Información de la Cuenta</h3>
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
                        <label>Nombre de Usuario <span class="text-danger">*</span></label>
                        <input id="usuario" name="usuario" type="text" value="{{ $usuario->name ?? '' }}"
                            minlength="4" maxlength="20" class="form-control" aria-invalid="true" autofocus>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Rol <span class="text-danger">*</span></label>
                        <select name="rolName" data-required="true" class="form-control show-tick">
                            <option value="0" readOnly>-- Selecciona --</option>
                            @foreach ($roles as $rol1)
                                <option value="{{ $rol1->name }}" @if (isset($usuario->roles[0]->name) && $usuario->roles[0]->name == $rol1->name) selected @endif>
                                    {{ $rol1->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Correo Electrónico <span class="text-danger">*</span></label>
                        <input id="email" name="email" type="email" value="{{ $usuario->email ?? '' }}"
                            class="form-control " aria-invalid="true" autofocus disabled>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Contraseña <span class="text-danger">*</span></label>
                        <input id="password" data-required="true" name="password" type="password"
                            v-model="usuario.password" class="form-control-merge form-control" aria-invalid="true"
                            autocomplete="current-password">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Estado <span class="text-danger">*</span></label>
                        <select name="estado" data-required="true" class="form-control show-tick">
                            <option value="0" readOnly>-- Selecciona --</option>
                            @foreach ($estados as $key => $tipo)
                                <option value="{{ $key }}" @if (isset($usuario->estado) && $usuario->estado == $key) selected @endif>
                                    {{ $tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="col-sm-12 text-right mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ $usuario->per_id ? 'Actualizar' : 'Registrar' }}
                    </button>
                    <a href="{{ route('usuarios.inicio') }}" class="btn btn-outline-secondary">Cancelar</a>

                </div>
            </div>
        </div>
    </div>

</div>

</div>

@section('js')
    <script src="{{ asset('js/usuario/usuario.js') }}"></script>
    <script src="{{ asset('js/distrito.js') }}"></script>
    <script src="{{ asset('js/validate.js') }}"></script>
@endsection
