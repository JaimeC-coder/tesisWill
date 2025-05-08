@extends('adminlte::page')
@section('title', 'Inicio')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')


@section('content')
    <div class="container">
        <div class="container">
            <div class="row">
                @foreach ($informacion as $info )
                <div class="col-sm-4">
                    <div class="card  text-center">
                        <div class="card-body ">
                            <h5 class="text-center">
                                <p> <i class="{{$info['icon']}}"></i></p>

                            </h5>
                            <p class="card-text">
                                {{$info['title']}}
                                {{$info['value']}}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <div class="carousel-item active" data-interval="1000">
                <img src="{{ asset('assets/images/home/img1.jpg') }}" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item" data-interval="1000">
                <img src="{{ asset('assets/images/home/img2.jpg') }}" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item" data-interval="1000">
                <img src="{{ asset('assets/images/home/img3.jpg') }}" class="d-block w-100" alt="...">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </button>
          </div>
    </div>
    @endsection



    @section('js')
        <script>

        </script>
    @endsection
