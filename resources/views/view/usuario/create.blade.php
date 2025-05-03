@extends('adminlte::page')
@section('title', 'Usuarios')

@section('content_header', 'Inicio')
@section('content_header_title', 'Usuario')
@section('content_header_subtitle', 'Crear Usuario')




@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('usuario.store') }}" id="form-all-request"   role="form" enctype="multipart/form-data">
                @csrf

                @include('view.usuario.form')

            </form>


        </div>
    </div>

@endsection


