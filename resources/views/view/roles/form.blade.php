<div class="card">
    <div class="card-body">

        <div class="form-group row">
            <label class="col-md-3 col-form-label">Descripcion <span class="text-danger">*</span></label>
            <div class="col-md-7">
                <input type="text" placeholder="Ingrese descripcion del rol" class="form-control"
                    autofocus required value="{{$rol->rol_descripcion}}" name="rol_descripcion">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">Estado <span class="text-danger">*</span></label>
            <div class="col-md-7">
                <select name="rol_estado" class="form-control show-tick" required>
                    <option disabled selected>-- Selecciona --</option>
                    @foreach ($estado  as $key => $tipo)
                    <option value="{{ $key }}" @if ($rol->rol_estado === $key ) selected @endif>
                        {{ $tipo }}

            </option>
                @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">

            <div class="col-md-7 text-right mt-4">
                <button  type="submit" class="btn btn-primary">Registrar</button>

                <a href="{{ route('roles.inicio') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </div>
    </div>

</div>
