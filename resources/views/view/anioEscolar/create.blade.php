@extends('adminlte::page')
@section('title', 'Año Escolar')

@section('content_header', 'Inicio')
@section('content_header_title', 'Año Escolar')
@section('content_header_subtitle', 'Crear Año Escolar')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('anio.store') }}"  role="form" enctype="multipart/form-data">
                @csrf

                @include('view.anioEscolar.form')

            </form>


        </div>
    </div>

@endsection


