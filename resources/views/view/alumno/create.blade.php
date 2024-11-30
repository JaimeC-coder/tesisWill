@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Alumno')
@section('content_header_subtitle', 'Crear Alumno')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('alumno.store') }}"  role="form" enctype="multipart/form-data">
                @csrf

                @include('view.alumno.form')

            </form>


        </div>
    </div>

@endsection


