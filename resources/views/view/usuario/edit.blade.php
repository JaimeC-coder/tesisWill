@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Alumno')
@section('content_header_subtitle', 'Crear Alumno')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('usuario.store') }}"  role="form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('view.usuario.form')

            </form>


        </div>
    </div>

@endsection


