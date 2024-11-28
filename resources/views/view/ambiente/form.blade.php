<div class="card border-primary">
    <div class="card-body">
        <div class="mb-3 row">
            <label for="descripcion" class="col-sm-2 col-form-label">Descripcion del aula <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="descripcion" name="ala_descripcion" placeholder="Ingrese descripción 1 ,descripción 2" value="{{$ambiente->ala_descripcion}}" >
            </div>
        </div>
        <div class="mb-3 row">
            <label for="tipo" class="col-sm-2 col-form-label">Tipo de ambiente <span class="text-danger">*</span></label>
            <div class="col-sm-10">
               <select name="ala_tipo" id="tipo" class="form-control">
                <option value="">Seleccione un tipo de ambiente</option>
                @foreach ($tipoAmbiente as $tipo)

                    <option value="{{$tipo}}" @if($ambiente->ala_tipo == $tipo) selected @endif>{{$tipo}}</option>
                @endforeach

               </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label for="capacidad" class="col-sm-2 col-form-label">Capacidad <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="capacidad" name="ala_aforo" placeholder="Ingrese capacidad del aula" value="{{$ambiente->ala_aforo}}">
            </div>
        </div>
        <div class="mb-3 row">
            <label for="ubicacion" class="col-sm-2 col-form-label">Ubicación <span class="text-danger">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="ubicacion" name="ala_ubicacion" placeholder="Ingrese ubicación del aula" value="{{$ambiente->ala_ubicacion}}">
            </div>
        </div>
        <div class="d-inline row p-2 float-right">

            <button type="submit" class="btn btn-primary">Registrar</button>
            <a href="{{route('ambiente.inicio')}}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</div>


