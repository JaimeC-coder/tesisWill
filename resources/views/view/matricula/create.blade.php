@extends('adminlte::page')
@section('title', 'Matriculas')

@section('content_header', 'Inicio')
@section('content_header_title', 'Matricula')
@section('content_header_subtitle', 'Crear Matricula')





@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('matricula.store') }}"  role="form" enctype="multipart/form-data">
                @csrf

                @include('view.matricula.form')

            </form>


        </div>
    </div>

@endsection

