<div class="card">
    <div class="card-body">

        <div class="form-group row">
            <label class="col-md-3 col-form-label">Descripcion <span class="text-danger">*</span></label>
            <div class="col-md-7">
                <input type="text" placeholder="Ingrese descripcion del rol" class="form-control"
                    autofocus  data-required="true"  value="{{$role->name}}" name="rol_descripcion">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3 col-form-label">Estado <span class="text-danger">*</span></label>
            <div class="col-md-7">
                <select name="rol_estado" class="form-control show-tick"  data-required="true" >
                    <option disabled selected>-- Selecciona --</option>
                    @foreach ($estado  as $key => $tipo)
                    <option value="{{ $key }}" @if ($role->rol_estado== $key ) selected @endif>
                        {{ $tipo }}

            </option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-3 col-form-label">Permisos <span class="text-danger">*</span></label>

            <div class="col-md-9">
                <div class="row">
                    @foreach ($permissions->chunk(ceil($permissions->count() / 2)) as $chunk)
                        <div class="col-md-6">
                            <ul class="list-group list-group-flush">
                                @foreach ($chunk as $permission)
                                    <li class="list-group-item border-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                   value="{{ $permission->name }}" id="permission_{{ $permission->id }}"
                                                   {{$role->permissions->contains($permission->id) ? 'checked' : ''}}>
                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                {{ $permission->description }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="d-inline row p-2 float-right">

            <button type="submit" class="btn btn-primary">
                {{ isset($role->rol_estado) ? 'Actualizar' : 'Registrar' }}
            </button>

            <a href="{{ route('roles.inicio') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>


    </div>



    <script src="{{ asset('js/validate.js') }}"></script>

</div>
