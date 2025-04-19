<div class="tab-pane" id="curso-form">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Curso <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required name="curso" value="{{ $curso->cur_nombre }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Abreviatura <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" required name="abreviatura" value="{{ $curso->cur_abreviatura }}">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Horas <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" required name="horas" value="{{ $curso->cur_horas }}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Nivel <span class="text-danger">*</span></label>
                            <select class="form-control show-tick" required name="nivel_id">
                                <option value="0" selected disabled>-- Selecciona --</option>
                                @foreach ($niveles as $nivel)
                                        <option value="{{ $nivel->niv_id }}" @if ($curso->niv_id == $nivel->niv_id) selected @endif >{{ $nivel->niv_descripcion }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Grado <span class="text-danger">*</span></label>
                            <select class="form-control show-tick" required name="grado_id">
                                <option value="0" selected disabled>-- Selecciona --</option>
                                <option value="-1">Todos los grados</option>
                                @foreach ($grados as $grado)
                                <option value="{{ $grado->gra_id }}" @if ( $curso->gra_id == $grado->gra_id) selected @endif

                                    >{{ $grado->gra_descripcion }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    @if (isset($curso) && $curso->cur_estado == 1)
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Estado <span class="text-danger">*</span></label>
                            <select class="form-control show-tick" required name="estado">
                                <option value="0" selected disabled>-- Selecciona --</option>
                                @foreach ($estados  as $key => $tipo)
                                    <option value="{{ $key }}" @if ($curso->cur_estado == $key) selected @endif
                                        >
                                        {{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-11 col-md-6 col-sm-12">
                        <div class="form-group">
                            <label>Capacidad</label>
                            <input id="input-capacidad" type="text" class="form-control">
                            <input type="hidden" name="capacidades" id="capacidades" value="{{ isset($curso) ? $curso->capacidad : '[]' }}">
                        </div>
                    </div>
                    <div class="col-lg col-md-6 col-sm-12 mt-2" style="align-items: center; display: flex; cursor: pointer; justify-content: center;">
                        <button type="button" id="btn-agregar-capacidad" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div id="detalleCapacidades2" class="col-lg-12 col-md-6 col-sm-12 d-none">
                        <div class="form-group">
                            <hr>
                            <label>Detalle de las capacidades</label>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-sm-12 text-right mt-4">
                        <button type="submit" class="btn btn-primary">

                        </button>
                        <a href="{{ route('curso.inicio')}}" class="btn btn-outline-secondary">Cancelar</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('js')
    <script src="{{ asset('js/curso/curso.js') }}"></script>
@endsection

