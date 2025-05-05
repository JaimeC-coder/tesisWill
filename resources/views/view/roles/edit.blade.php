@extends('adminlte::page')


@section('title', 'Roles')
@section('content_header', 'Inicio')
@section('content_header_title', 'Rol')
@section('content_header_subtitle', 'Información de Rol Educativa')
@section('content')

    <div class="container">
        <div class="container">
            <form method="POST" action="{{ route('roles.update', $role) }}" role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf
                @include('view.roles.form')

            </form>


        </div>
    </div>



@endsection


