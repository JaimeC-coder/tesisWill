@extends('adminlte::page')
@section('title', 'Usuarios')

@section('content_header', 'Inicio')
@section('content_header_title', 'Usuario')
@section('content_header_subtitle', 'Editar Usuario')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('usuario.update',$usuario) }}"  role="form" enctype="multipart/form-data">
                @csrf
                {{ method_field('PATCH') }}
                @include('view.usuario.form')

            </form>


        </div>
    </div>

@endsection


