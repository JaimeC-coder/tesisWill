<div class="card">
    <div class="card-header">
        <h3 class="card-title">Información de la IE</h3>
    </div>
    <div class="card-body">


        <form >
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>Código Modular </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_codigo_modular}}" name="ie_codigo_modular">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>Anexo </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_anexo}}" name="ie_anexo">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>Gestión </label>
                        <input class="form-control"  value="{{$institucion->ie_gestion}}" type="text" name="ie_gestion">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Nombre </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_nombre}}" name="ie_nombre">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>Director </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_director}}" name="ie_director">
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>Departamento </label>
                        <select  class="form-control show-tick" required name="ie_departamento">
                            <option value="0" selected disabled>-- Selecciona --</option>
                            @foreach ($departamentos as $departamento)
                                <option value="{{$departamento->idDepa}}" @if ($departamento->idDepa == $institucion->ie_departamento) selected @endif>{{$departamento->departamento}}</option>
                            @endforeach

                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>Provincia </label>
                        <select  class="form-control show-tick" required name="ie_provincia">
                            <option value="0" selected disabled>-- Selecciona --</option>
                               @foreach ($provincias as $provincia)
                                <option value="{{$provincia->idProv}}" @if ($provincia->idProv == $institucion->ie_provincia) selected @endif>{{$provincia->provincia}}</option>

                               @endforeach

                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group">
                        <label>Distrito </label>
                        <select  class="form-control show-tick" required name="ie_distrito">
                            <option value="0" selected disabled>-- Selecciona --</option>
                            @foreach ($distritos as $distrito)
                            <option value="{{$distrito->idDist}}" @if ($distrito->idDist == $institucion->ie_distrito) selected @endif>{{$distrito->distrito}}</option>
                            @endforeach
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Dirección</label>
                        <textarea class="form-control"  aria-label="With textarea" name="ie_direccion">{{$institucion->ie_direccion}}</textarea>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>DRE </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_dre}}" name="ie_dre">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label>UGEL </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_ugel}}" name="ie_ugel">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                        <label>Nivel </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_nivel}}" name="ie_nivel">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                        <label>Género </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_genero}}" name="ie_genero">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                        <label>Turno </label>
                        <input class="form-control"   type="text" value="{{$institucion->ie_turno}}" name="ie_turno">
                    </div>
                </div>
                <div class="col-md-3 col-sm-12">
                    <div class="form-group">
                        <label>Dias Laborales </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_dias_laborales}}" name="ie_dias_laborales">
                    </div>
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class="form-group">
                        <label>Teléfono </label>
                        <input class="form-control"  type="text" value="{{$institucion->ie_telefono}}" name="ie_telefono">
                    </div>
                </div>
                <div class="col-md-5 col-sm-12">
                    <div class="form-group">
                        <label>Correo </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-envelope"></i></span>
                            </div>
                            <input type="text"  class="form-control" value="{{$institucion->ie_email}}" name="ie_email">
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12">
                    <div class="form-group">
                        <label>Página Web</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            </div>
                            <input type="text"  class="form-control" placeholder="http://" value="{{$institucion->ie_web}}" name="ie_web">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-right m-t-20">
                    <div class="col-md-7 text-right mt-4">
                        <button v-else type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
