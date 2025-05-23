




<div class="tab-pane" id="curso-form">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row clearfix">
                    <div class="col-lg-11 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Descripción <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="Grado 1, Grado 2, Grado 3 ..."
                                autofocus  name="gra_descripcion" data-required="true" value="{{ $grado->gra_descripcion }}">
                        </div>
                    </div>
                    <div class="col-lg-11 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Nivel <span class="text-danger">*</span></label>
                            <select class="form-control show-tick" data-required="true" name="niv_id">
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
                            {{  $grado->gra_descripcion == ' ' ? 'Actualizar' : 'Registrar' }}
                        </button>
                        <a href="{{ route('gradoSeccion.inicio')}}" class="btn btn-outline-secondary">Cancelar</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('js')

    <script src="{{ asset('js/validate.js') }}"></script>
@endsection
