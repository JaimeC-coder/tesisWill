@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Alumno')
@section('content_header_subtitle', 'Editar Curso')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('curso.update' ,$curso) }}"  role="form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('view.curso.form')

            </form>


        </div>
    </div>

@endsection
