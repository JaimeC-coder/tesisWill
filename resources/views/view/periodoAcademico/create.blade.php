@extends('adminlte::page')
@section('title', 'Periodo Académico')

@section('content_header', 'Inicio')
@section('content_header_title', 'Periodo Académico')
@section('content_header_subtitle', 'Crear Periodo Académico')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('periodo.store') }}"  role="form" enctype="multipart/form-data">
                @csrf

                @include('view.periodoAcademico.form')

            </form>


        </div>
    </div>

@endsection


