<div class="card border-primary">
    <div class="card-body">
        <div class="mb-3 row">
            <label for="descripcion" class="col-sm-2 col-form-label">Descripcion del aula <span
                    class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" data-required="true" class="form-control" id="descripcion" name="ala_descripcion"
                    placeholder="Ingrese descripción 1 ,descripción 2" value="{{ $ambiente->ala_descripcion }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="tipo" class="col-sm-2 col-form-label">Tipo de ambiente <span
                    class="text-danger">*</span></label>
            <div class="col-sm-10">
                <select name="ala_tipo" id="tipo" class="form-control" data-required="true">
                    <option value="">Seleccione un tipo de ambiente</option>
                    @foreach ($tipoAmbiente as $tipo)
                        <option value="{{ $tipo }}" @if ($ambiente->ala_tipo == $tipo) selected @endif>
                            {{ $tipo }}</option>
                    @endforeach

                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="capacidad" class="col-sm-2 col-form-label">Capacidad <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="capacidad" name="ala_aforo"
                    placeholder="Ingrese capacidad del aula" value="{{ $ambiente->ala_aforo }}" data-required="true"
                    data-type="number" min="1">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="ubicacion" class="col-sm-2 col-form-label">Ubicación <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" data-required="true"  class="form-control" id="ubicacion"
                    name="ala_ubicacion" placeholder="Ingrese ubicación del aula"
                    value="{{ $ambiente->ala_ubicacion }}">
            </div>
        </div>
        <div class="mb-3 row">
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_multiuse" name="is_multiuse"
                        @if ($ambiente->is_multiuse) checked @endif>
                    <label class="form-check-label" for="is_multiuse">
                        Es multi aula?
                    </label>
                </div>
            </div>
        </div>
        <div class="d-inline row p-2 float-right">


            <button type="submit" class="btn btn-primary">
                {{ $ambiente->ala_id ? 'Actualizar' : 'Registrar' }}
            </button>



            <a href="{{ route('ambiente.inicio') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</div>


@section('js')
    <script src="{{ asset('js/validate.js') }}"></script>
@endsection
