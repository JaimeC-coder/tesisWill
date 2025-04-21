<div class="tab-pane" id="Matriculation-add">
    <div class="row clearfix">
        <form class="row" @submit.prevent="agregar_matricula">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Buscar Alumno</h3>
                        <div class="card-options ">
                            <a href="#" class="card-options-collapse d-none" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            <a href="#" class="card-options-remove d-none" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>DNI <span class="text-danger">*</span></label>

                                    <div class="input-group mb-3">
                                        <input type="number" v-model="matricula.dni" class="form-control" minlength="8" maxlength="8" required>
                                        <div class="input-group-append">
                                            <button v-if="!loadingDni" @click="buscando_dni()" class="btn btn-primary" type="button">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                                Buscar
                                            </button>
                                            <button v-else id="btnfollow_recargar" class="btn btn-primary" type="button">
                                                <i class="fa-solid fa-spinner base"></i>
                                                Procesando...
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Alumno <span class="text-danger">*</span></label>
                                    <input type="text" v-model="matricula.alumno" class="form-control" required disabled>
                                </div>
                            </div>
                        </div>
                        <div id="cancelar1" style="text-align: end;">
                            <button type="button" class="btn btn-outline-secondary" @click="cancelar()">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="matricula_info" class="col-lg-12 col-md-12 col-sm-12 ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información Matricula</h3>
                        <div class="card-options ">
                            <a href="#" class="card-options-collapse d-none" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            <a href="#" class="card-options-remove d-none" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Periodo <span class="text-danger">*</span></label>
                                    <select  v-model="matricula.per_id"  class="form-control show-tick" >
                                        <option value="0" selected >-- Selecciona --</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nivel <span class="text-danger">*</span></label>
                                    <select  @change="listar_grados()" v-model="matricula.niv_id"  class="form-control show-tick" required>
                                        <option value="0" selected >-- Selecciona --</option>
                                        <option v-for="n in niveles" :key="n.id" v-bind:value="n.niv_id">

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Grado <span class="text-danger">*</span></label>
                                    <select @change="listar_secciones()" v-model="matricula.gra_id"  class="form-control show-tick" required>
                                        <option value="0" selected >-- Selecciona --</option>
                                        <option v-for="g in grados" :key="g.id" v-bind:value="g.gra_id">

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Sección <span class="text-danger">*</span></label>
                                    <select  @change="mostrar_info_seccion()" v-model="matricula.sec_id"  class="form-control show-tick" required>
                                        <option value="0" selected >-- Selecciona --</option>
                                        <option v-for="s in secciones" :key="s.id" v-bind:value="s.sec_id">

                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Aula </label>
                                    <input type="text" v-model="matricula.aula" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Nro Vacantes </label>
                                    <input type="text" v-model="matricula.vacantes" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Fecha de Matricula <span class="text-danger">*</span></label>
                                    <input type="date" v-model="matricula.fecha" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Situación </label>
                                    <select v-model="matricula.situacion" class="form-control show-tick" required>
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
            <div id="matricula_info2" class="col-lg-12 col-md-12 col-sm-12  ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información Apoderado</h3>
                        <div class="card-options ">
                            <a href="#" class="card-options-collapse d-none" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            <a href="#" class="card-options-remove d-none" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Apoderado </label>
                                    <input type="text" v-model="matricula.apoderado" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label>Parentesco <span class="text-danger">*</span></label>
                                <select v-model="matricula.parentesco" class="form-control show-tick" required disabled>
                                    <option value="0" disabled>-- Selecciona --</option>
                                    <option value="PADRE">Padre</option>
                                    <option value="MADRE">Madre</option>
                                    <option value="TIO">Madre</option>
                                    <option value="TUTOR">Tutor</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Vive con el estudiante</label>
                                    <select v-model="matricula.vive_con_estudiante" class="form-control show-tick">
                                        <option value="0" disabled>-- Selecciona --</option>
                                        <option value="1">SI</option>
                                        <option value="2">NO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="matricula_info3" class="col-lg-12 col-md-12 col-sm-12  ">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Información Extra</h3>
                        <div class="card-options ">
                            <a href="#" class="card-options-collapse d-none" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            <a href="#" class="card-options-remove d-none" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <label>Tipo Procedencia </label>
                                <select v-model="matricula.tipo_procedencia" class="form-control show-tick" >
                                    <option value="0" disabled>-- Selecciona --</option>
                                    <option value="Misma IE">Misma IE</option>
                                    <option value="Otra IE">Otra IE</option>
                                    <option value="Su casa">Su casa</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Observación </label>
                                    <input type="text" v-model="matricula.observacion" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12 text-right mt-4">
                                <button v-if="!loading" type="submit" class="btn btn-primary">Registrar</button>
                                <button v-else disabled type="button" class="btn btn-primary">Procesando...</button>
                                <button type="button" class="btn btn-outline-secondary" @click="cancelar()">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
