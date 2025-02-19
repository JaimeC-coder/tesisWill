




<div class="tab-pane" id="curso-form">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row clearfix">
                    <div class="col-lg-11 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Descripción <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Grado 1, Grado 2, Grado 3 ..."
                                autofocus required name="gra_descripcion" value="{{ $grado->gra_descripcion }}">
                        </div>
                    </div>
                    <div class="col-lg-11 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Nivel <span class="text-danger">*</span></label>
                            <select class="form-control show-tick" required name="niv_id">
                                <option value="0" selected disabled>-- Selecciona --</option>

                                @foreach ($niveles as $nivel)
                                    <option value="{{ $nivel->niv_id }}"
                                        @if ($nivel->niv_id == $nivel->niv_id) selected @endif>
                                        {{ $nivel->niv_descripcion }}</option>)
                                @endforeach

                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-12 text-right mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{  $grado->gra_descripcion == ' ' ? 'Actualizar' : 'Guardar' }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
