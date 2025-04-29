<div class="card border-primary">
    <div class=" p-2">
        <h5 class="card-title">INFORMACIÓN BÁSICA</h5>
    </div>

    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
               <div class="form-group">
                <label for="periodo_descripcion">Periodo</label>
                <select class="form-control" id="periodo" name="anio_id"data-required="true" >
                    <option value="" id="" >Seleccione una opción</option>
                    @foreach ($anio  as  $tipo)
                        <option value="{{$tipo->anio_id }}" @if ($tipo->anio_id == $periodo->anio_id) selected @endif>
                            {{ $tipo->anio_descripcion }}</option>
                    @endforeach
                </select>
               </div>

            </div>


            <div class="form-group col-md-6">
                <div class="form-group">
                    <label for="inputPassword4">Estado</label>
                    <select class="form-control" name="per_estado" data-required="true">
                        <option value="" id="" >Seleccione una opción</option>
                        @foreach ($estado  as $key => $tipo)
                        <option value="{{ $key }}" @if (( $periodo->per_estado ?? '') == $key) selected @endif>
                            {{ $tipo }}
                        </option>
                    @endforeach

                    </select>
                </div>


            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <div class="form-group">
                    <label for="per_inicio_matriculas">Fecha de Inicio de Matriculas *</label>
                    <input type="date" class="form-control"  data-required="true"
                    min="1777-01-01" max="2025-12-31"     id="per_inicio_matriculas" name="per_inicio_matriculas" value="{{$periodo->per_inicio_matriculas}}">
                </div>


            </div>
            <div class="form-group col-md-6">
                <div class="form-group">
                    <label for="per_final_matricula">Fecha de Fin de Matriculas *</label>
                    <input type="date" class="form-control"  data-required="true"
                    min="1777-01-01" max="2025-12-31" id="per_final_matricula" name="per_final_matricula" value="{{$periodo->per_final_matricula}}">
                </div>


            </div>
        </div>
        <div class="form-row">

            <div class="form-group col-md-6">
                <div class="form-group">
                    <label for="per_limite_cierre">Fecha Limite para Cierre de Matriculas *</label>
                    <input type="date" class="form-control"  data-required="true"
                    min="1777-01-01" max="2025-12-31" id="per_limite_cierre" name="per_limite_cierre" value="{{$periodo->per_limite_cierre}}">
                </div>
            </div>
            <div class="form-group col-md-6">
                <div class="form-group">
                    <label for="per_tp_notas">Tipo de Registro de Notas *</label>

                    <select class="form-control" id="per_tp_notas" name="per_tp_notas" data-required="true" >
                        <option value="" id="" >Seleccione una opción</option>
                        @foreach ($tipos  as  $tip)
                            <option value="{{$tip->tp_id }}" @if ($tip->tp_id == $periodo->per_tp_notas) selected @endif>
                                {{ $tip->tp_tipo }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <div class="d-inline row p-2 float-right">

            <button type="submit" class="btn btn-primary">
                {{ isset($periodo->per_estado) ? 'Actualizar' : 'Registrar' }}
            </button>
            <a href="{{ route('periodo.inicio') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</div>

@section('js')

<script src="{{ asset('js/validate.js') }}"></script>

@endsection





