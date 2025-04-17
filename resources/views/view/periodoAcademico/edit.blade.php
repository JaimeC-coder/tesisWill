@extends('adminlte::page')
@section('title', 'Periodo Académico')

@section('content_header', 'Inicio')
@section('content_header_title', 'Periodo Académico')
@section('content_header_subtitle', 'Editar Periodo Académico')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('periodo.update', $periodo) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('view.periodoAcademico.form')

            </form>


        </div>
    </div>

@endsection

