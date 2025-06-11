<div class="tab-pane" id="Matriculation-add">
    <div class="row clearfix">
        <form class="row" @submit.prevent="agregar_matricula">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Buscar Alumno</h3>
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
                                    <input type="hidden" id="ala_id" name="ala_id">
                                    <input type="hidden" id="alu_id" name="alu_id">
                                    <label>DNI <span class="text-danger">*</span></label>

                                    <div class="input-group mb-3">
                                        <input type="number" id="dni" name="dni" class="form-control"
                                            minlength="8" maxlength="8" required>
                                        <div class="input-group-append">
                                            <button id="btndni" name="dni" class="btn btn-primary" disabled
                                                type="button">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                                Buscar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Alumno <span class="text-danger">*</span></label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" required
                                        disabled>
                                </div>
                            </div>
                        </div>
                        <div id="cancelar1" style="text-align: end;">
                            <a href="{{ route('matricula.inicio') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="matricula_info1" class="col-lg-12 col-md-12 col-sm-12 d-none ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información Matricula</h3>

                    </div>
                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">

                                    <label>Periodo <span class="text-danger">*</span></label>
                                    <select name="per_id" id="per_id" class="form-control show-tick" data-required="true">
                                        <option value="0" selected>-- Seleccione un periodo --</option>
                                        @foreach ($periodos as $periodo)
                                            <option value="{{ $periodo->per_id }}">
                                                {{ $periodo->anio->anio_descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nivel <span class="text-danger">*</span></label>
                                    <select id="niv_id" name="niv_id" class="form-control show-tick" required
                                        disabled  data-required="true">
                                        <option value="0" selected>-- Seleccione un nivel --</option>
                                        @foreach ($niveles as $nivel)
                                            <option value="{{ $nivel->niv_id }}">{{ $nivel->niv_descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Grado <span class="text-danger">*</span></label>
                                    <select name="gra_id" id="gra_id" disabled class="form-control show-tick"
                                        required data-required="true">
                                        <option value="0" selected>-- Seleccione un grado --</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Sección <span class="text-danger">*</span></label>
                                    <select id="sec_id" name="sec_id" class="form-control show-tick" disabled
                                        required data-required="true">
                                        <option value="0" selected>-- Seleccione una sección --</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Aula </label>
                                    <input type="text" id="aula" name="aula" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nro Vacantes </label>
                                    <input type="text" id="vacantes" name="vacantes" class="form-control"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Fecha de Matricula <span class="text-danger">*</span></label>
                                    <input type="date" id="fechamatricula" name="fechamatricula"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Situación </label>
                                    <select id="situacion" name="situacion" class="form-control show-tick" required data-required="true">
                                        <option value="0" disabled>-- Selecciona --</option>
                                        <option value="Ingresante">Ingresante</option>
                                        <option value="Promovido">Promovido</option>
                                        <option value="Repite">Repite</option>
                                        <option value="Reentrante">Reentrante</option>
                                        <option value="Repite">Re ingresante</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div id="matricula_info2" class="col-lg-12 col-md-12 col-sm-12  d-none">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información Apoderado</h3>

                    </div>
                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Apoderado </label>
                                    <input type="text" id="apoderado" name="apoderado" class="form-control"
                                        disabled>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label>Parentesco <span class="text-danger">*</span></label>
                                <input type="text" id="parentesco" name="parentesco" class="form-control"
                                    disabled>

                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Vive con el estudiante</label>
                                    <input type="text" id="vive_con_estudiante" name="vive_con_estudiante"
                                        class="form-control" disabled>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="matricula_info3" class="col-lg-12 col-md-12 col-sm-12  d-none ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información Extra</h3>
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
                                <label>Tipo Procedencia </label>
                                <select id="tipo_procedencia" name="tipo_procedencia" class="form-control show-tick">
                                    <option value="0" disabled>-- Selecciona --</option>
                                    <option value="Misma IE">Misma IE</option>
                                    <option value="Otra IE">Otra IE</option>
                                    <option value="Su casa">Su casa</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Observación </label>
                                    <input type="text" name="observacion" id="observacion" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12 text-right mt-4">

                                <button type="submit" class="btn btn-primary">
                                    Registrar
                                </button>
                                <a href="{{ route('matricula.inicio') }}"
                                    class="btn btn-outline-secondary">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@section('js')
    <script src="{{ asset('js/validate.js') }}"></script>
    <script src="{{ asset('js/matriculas/matricula.js') }}"></script>
@endsection
