@extends('adminlte::page')
@section('title', 'Curso')

@section('content_header', 'Inicio')
@section('content_header_title', 'Curso')
@section('content_header_subtitle', 'Crear Curso')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('curso.store') }}" id="form-all-request"  role="form" enctype="multipart/form-data">
                @csrf

                @include('view.curso.form')

            </form>


        </div>
    </div>

@endsection
