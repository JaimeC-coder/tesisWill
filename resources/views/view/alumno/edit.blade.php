@extends('adminlte::page')
@section('title', 'Alumno')

@section('content_header', 'Inicio')
@section('content_header_title', 'Alumno')
@section('content_header_subtitle', 'Editar Alumno')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('alumno.update',$alumno) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf


                @include('view.alumno.form')

            </form>


        </div>
    </div>

@endsection


