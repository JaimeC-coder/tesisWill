@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Intitución Educativa')
@section('content_header_subtitle', 'Informacion de Institución Educativa')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('institucion.update', $institucion) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('view.institucionEducativa.form')

            </form>


        </div>
    </div>

@endsection

