@extends('adminlte::page')
@section('title', 'Personal Académico')

@section('content_header', 'Inicio')
@section('content_header_title', 'Personal Académico')
@section('content_header_subtitle', 'Editar Personal Académico')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('personal.update', $personal) }}" id="form-all-request"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('view.personalAcademico.form')

            </form>


        </div>
    </div>

@endsection

