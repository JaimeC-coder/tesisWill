@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Rol')
@section('content_header_subtitle', 'Informacion de Rol Educativa')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('roles.store') }}"  role="form" enctype="multipart/form-data">

                @csrf

                @include('view.roles.form')

            </form>


        </div>
    </div>

@endsection

