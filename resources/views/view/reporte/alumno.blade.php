@extends('adminlte::page')
@section('title', 'Horario')

@section('content_header', 'Inicio')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Registrar Horario')



@section('content')
<div >
    <div class="section-body">
         <div class="container-fluid">
             <div class="d-flex justify-content-between align-items-center ">
                 <div class="header-action">
                     <h1 class="page-title">Reportes por Alumno</h1>
                     <ol class="breadcrumb page-breadcrumb d-none">
                         <li class="breadcrumb-item"><a href="#">Ericsson</a></li>
                         <li class="breadcrumb-item active" aria-current="page">aula</li>
                     </ol>
                 </div>
                 <ul class="nav nav-tabs page-header-tab">
                     <li id="li-all" class="nav-item"><a id="link-all" class="nav-link active" data-toggle="tab" href="#aula-all">Ver Lista</a></li>
                     <li id="li-grid" class="nav-item d-none"><a id="link-grid" class="nav-link" data-toggle="tab" href="#aula-grill">Ver Cuadricula</a></li>
                     <li id="li-add" class="nav-item d-none"><a id="link-add" class="nav-link" data-toggle="tab" href="#aula-add">Registrar Aula</a></li>
                     <li id="li-update" class="nav-item d-none"><a id="link-update" class="nav-link" data-toggle="tab" href="#aula-update">Actualizar Aula</a></li>
                 </ul>
             </div>
         </div>
     </div>
     <div class="section-body mt-4">
         <div class="container-fluid">
             <div class="tab-content">
                 <div class="tab-pane active" id="aula-all">
                     <div class="card">
                         <div class="card-body">
                             <div class="row">
                                 <div class="col-lg-12 col-md-6 col-sm-12">
                                     <div class="">
                                         <div class="input-group">
                                             <input type="text" v-model="reporte.buscar" class="form-control" placeholder="Ingresar DNI del alumno" required>
                                             <div class="input-group-append">
                                                 <button v-if="!loadingBuscar" @click="buscando_info_alumno()" class="btn btn-primary" type="button">
                                                     <i class="fa-solid fa-magnifying-glass"></i>
                                                     Buscar
                                                 </button>
                                                 <button v-else id="btnfollow_recargar" class="btn btn-primary" type="button">
                                                     <i class="fa-solid fa-spinner base"></i>
                                                     Procesando...
                                                 </button>
                                                 <button @click="cancelar_busqueda()" class="btn btn-secundary" type="button">
                                                     Cancelar
                                                 </button>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div id="listar-info" v-if="cargarInfo">
                         <div class="row">
                             <div class="col-xl-4 col-md-12">
                                 <div class="card">
                                     <div class="card-body w_user">
                                         <div class="user_avtar">
                                             <img class="rounded-circle" src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Alumno">
                                         </div>
                                         <div class="wid-u-info">
                                             {{-- <h5>{{data.per_nombres}} {{data.per_apellidos}}</h5>
                                             <p class="text-muted m-b-0">{{data.per_dni}}</p> --}}

                                         </div>
                                     </div>
                                 </div>
                                 <div class="card">
                                     <div class="card-body">
                                         <h3 class="card-title">Información Académica</h3>
                                         {{-- <div class="row">
                                             <div class="col-md-4 col-sm-4 col-6">
                                                 <div class="font-18 font-weight-bold">Nivel</div>
                                                 <div>{{data.nivel}}</div>
                                             </div>
                                             <div class="col-md-4 col-sm-4 col-6">
                                                 <div class="font-18 font-weight-bold">Grado</div>
                                                 <div>{{data.grado}}</div>
                                             </div>
                                             <div class="col-md-4 col-sm-4 col-6">
                                                 <div class="font-18 font-weight-bold">Sección</div>
                                                 <div>{{data.seccion}}</div>
                                             </div>
                                         </div> --}}
                                     </div>
                                     {{-- <div class="card-footer">
                                         <h3 class="card-title">Información Personal</h3>
                                         <ul class="list-group">
                                             <li class="list-group-item">
                                                 <b>Dirección</b>
                                                 <div>{{data.per_direccion == null ? "Chepén" : data.per_direccion}}</div>
                                             </li>
                                             <li class="list-group-item">
                                                 <b>Fecha Nacimiento </b>
                                                 <div>{{data.per_fecha_nacimiento}}</div>
                                             </li>
                                             <li class="list-group-item">
                                                 <b>Sexo </b>
                                                 <div>{{data.per_sexo == "F" ? "Femenino" : "Masculino" }}</div>
                                             </li>
                                             <li class="list-group-item">
                                                 <b>Correo </b>
                                                 <div>{{data.per_email == null ? "No registrado" : data.per_email }}</div>
                                             </li>
                                             <li class="list-group-item">
                                                 <b>Celular </b>
                                                 <div>{{data.per_celular == null ? "No registrado" : data.per_celular }}</div>
                                             </li>
                                         </ul>
                                     </div> --}}
                                 </div>
                             </div>
                             <div class="col-xl-8 col-md-12">
                                 <div class="card">
                                     <div class="card-header">
                                         <h3 class="card-title">Reportes</h3>
                                         <div class="card-options d-none">
                                             <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                             <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                             <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                                             <div class="item-action dropdown ml-2">
                                                 <a href="javascript:void(0)" data-toggle="dropdown"><i class="fe fe-more-vertical"></i></a>
                                                 <div class="dropdown-menu dropdown-menu-right">
                                                     <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-eye"></i> View Details </a>
                                                     <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-share-alt"></i> Share </a>
                                                     <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-cloud-download"></i> Download</a>
                                                     <div class="dropdown-divider"></div>
                                                     <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-copy"></i> Copy to</a>
                                                     <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-folder"></i> Move to</a>
                                                     <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-edit"></i> Rename</a>
                                                     <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-trash"></i> Delete</a>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="card-body">
                                         <div class="timeline_item ">
                                             <img class="tl_avatar" src="assets/images/informe.png" alt="" />
                                             <span><b>Ficha de Matricula </b></span>
                                             <div class="msg">
                                                 <a href="javascript:void(0);" @click="generar_ficha_matricula()"  class="mr-20 "><i class="fa fa-folder-plus"></i> Generar</a>
                                                 <a v-if="data.ficha_matricula" target="_blank" :href="'storage/FichaMatricula/'+data.ficha_matricula" class="mr-20"><i class="fa-solid fa-eye"></i> Ver</a>
                                                 <a v-else target="_blank" :href="'storage/FichaMatricula/'+data.ficha_matricula" class="mr-20 d-none"><i class="fa-solid fa-eye"></i> Ver</a>
                                             </div>
                                         </div>
                                         <div class="timeline_item ">
                                             <img class="tl_avatar" src="assets/images/informe.png" alt="" />
                                             <span><b>Reporte de Horario</b></span>
                                             <div class="msg">
                                                 <a href="javascript:void(0);"  class="mr-20 text-muted"><i class="fa fa-folder-plus"></i> Generar</a>
                                                 <a href="javascript:void(0);" class="mr-20 text-muted d-none"><i class="fa-solid fa-eye"></i> Ver</a>
                                             </div>
                                         </div>
                                         <div class="timeline_item ">
                                             <img class="tl_avatar" src="assets/images/informe.png" alt="" />
                                             <span><b>Libreta de Notas </b></span>
                                             <div class="msg">
                                                 <a href="javascript:void(0);" @click="generar_libreta_notas()"  class="mr-20"><i class="fa fa-folder-plus"></i> Generar</a>
                                                 <a v-if="data.libreta_notas" target="_blank" :href="'storage/LibretasNotas/'+data.libreta_notas" class="mr-20"><i class="fa-solid fa-eye"></i> Ver</a>
                                                 <a v-else target="_blank" :href="'storage/LibretasNotas/'+data.libreta_notas" class="mr-20 d-none"><i class="fa-solid fa-eye"></i> Ver</a>
                                             </div>
                                         </div>
                                         <div class="timeline_item ">
                                             <img class="tl_avatar" src="assets/images/informe.png" alt="" />
                                             <span><b>Cursos Faltantes</b></span>
                                             <div class="msg">
                                                 <a href="javascript:void(0);" class="mr-20 text-muted"><i class="fa fa-folder-plus"></i> Generar</a>
                                                 <a href="" class="mr-20 text-muted d-none"><i class="fa-solid fa-eye"></i> Ver</a>
                                             </div>
                                         </div>
                                         <div class="timeline_item ">
                                             <img class="tl_avatar" src="assets/images/informe.png" alt="" />
                                             <span><b>Certificado de Estudios</b></span>
                                             <div class="msg">
                                                 <a href="javascript:void(0);" class="mr-20 text-muted"><i class="fa fa-folder-plus"></i> Generar</a>
                                                 <a  href="javascript:void(0);" class="mr-20 text-muted d-none"><i class="fa-solid fa-eye"></i> Ver</a>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div id="listar-info" v-else>
                         <div class="card-body text-center">
                             <div class="display-1 text-muted mb-2">
                                 <img src="assets/images/NotData.png" alt="error" width="30%">
                             </div>
                             {{-- <h1 class="h3 mb-3"><!-- No hay información disponible... -->{{ mensajeError }}</h1> --}}
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection


 @section('js')
     <script src="{{ asset('js/reportes/general.js') }}"></script>
 @endsection
