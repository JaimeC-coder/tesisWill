@extends('adminlte::page')
@section('title', 'Periodo Academico')

@section('content_header', 'Inicio')
@section('content_header_title', 'Personal Académico')
@section('content_header_subtitle', 'Crear Personal Académico')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('personal.store') }}"  role="form" enctype="multipart/form-data">
                @csrf

                @include('view.personalAcademico.form')

            </form>


        </div>
    </div>

@endsection


