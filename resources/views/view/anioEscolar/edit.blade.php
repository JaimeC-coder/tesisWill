@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Año Escolar')
@section('content_header_subtitle', 'Editar año')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('anio.update', $anio) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('view.anioEscolar.form')

            </form>


        </div>
    </div>

@endsection

