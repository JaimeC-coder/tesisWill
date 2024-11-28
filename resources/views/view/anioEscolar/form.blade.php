<div class="card border-primary">
    <div class=" p-2">
        <h5 class="card-title">Card title</h5>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="anio_descripcion">Año</label>
                <select class="form-control" id="anio" name="anio_descripcion" >
                    <option value="" >Seleccione una opción</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="inputPassword4">Estado</label>
                <select class="form-control" name="anio_estado" >
                    <option value="" id="" >Seleccione una opción</option>
                    @foreach ($estado  as $key => $tipo)
                        <option value="{{ $key }}" @if ($anio->anio_estado == $key) selected @endif>
                            {{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">Fecha de Inicio</label>
                <input type="date" class="form-control" id="anio_fechaInicio" name="anio_fechaInicio" value="{{$anio->anio_fechaInicio}}">
            </div>
            <div class="form-group col-md-6">
                <label for="anio_fechaFin">Fecha de Fin</label>
                <input type="date" class="form-control" id="anio_fechaFin" name="anio_fechaFin" value="{{$anio->anio_fechaFin}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="anio_duracionHoraAcademica">Duracion de Hora Académica</label>
                <input type="text" class="form-control" id="anio_duracionHoraAcademica" name="anio_duracionHoraAcademica" value="{{$anio->anio_duracionHoraAcademica}}">
            </div>
            <div class="form-group col-md-6">
                <label for="anio_duracionHoraLibre">Duracion de Hora Libre</label>
                <input type="text" class="form-control" id="anio_duracionHoraLibre" name="anio_duracionHoraLibre" value="{{$anio->anio_duracionHoraLibre}}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="anio_cantidadPersonal">Cantidad de Personal en la IE</label>
                <input type="number" class="form-control" id="anio_cantidadPersonal" name="anio_cantidadPersonal" value="{{$anio->anio_cantidadPersonal}}">
            </div>
            <div class="form-group col-md-6">
                <label for="anio_tallerSeleccionable">¿Habra Taller?</label>
                <select id="anio_tallerSeleccionable" class="form-control" name="anio_tallerSeleccionable">
                    <option value="">Seleccione una opción</option>
                    @foreach ($taller as $key => $tipo)
                        <option value="{{ $key }}" @if ($anio->anio_tallerSeleccionable == $key) selected @endif>
                            {{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="d-inline row p-2 float-right">

            <button type="submit" class="btn btn-primary">Registrar</button>
            <a href="{{ route('anio.inicio') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</div>

@section('js')
<script src="{{asset('js/form.js')}}"></script>
<script>
    window.anioSeleccionado = @json($anio->anio_descripcion);
</script>
@endsection

