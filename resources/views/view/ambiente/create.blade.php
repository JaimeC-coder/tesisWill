@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Ambientes')
@section('content_header_subtitle', 'Crear Ambiente')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('ambiente.store') }}"  role="form" enctype="multipart/form-data">
                @csrf

                @include('view.ambiente.form')

            </form>


        </div>
    </div>

@endsection


