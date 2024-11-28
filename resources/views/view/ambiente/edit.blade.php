@extends('adminlte::page')
@section('title', 'Ambiente')

@section('content_header', 'Inicio')
@section('content_header_title', 'Ambientes')
@section('content_header_subtitle', 'Editar Ambiente')



@section('content')

    <div class="container">
        <div class="container">

            <form method="POST" action="{{ route('ambiente.update',$ambiente) }}"  role="form" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                @csrf

                @include('view.ambiente.form')

            </form>


        </div>
    </div>

@endsection


