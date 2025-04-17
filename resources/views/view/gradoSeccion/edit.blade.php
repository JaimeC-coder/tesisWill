@extends('adminlte::page')
@section('title', 'Grado y Sección')

@section('content_header', 'Inicio')
@section('content_header_title', 'Grado y Sección')
@section('content_header_subtitle', 'Editar Grado')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('gradoSeccion.update', $grado) }}"  role="form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('view.gradoSeccion.form')

            </form>


        </div>
    </div>

@endsection
