@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\preloaderHelper')

@if ($layoutHelper->isLayoutTopnavEnabled())
    @php($def_container_class = 'container')
@else
    @php($def_container_class = 'container-fluid')
@endif

{{-- Default Content Wrapper --}}
<div class="{{ $layoutHelper->makeContentWrapperClasses() }}">

    {{-- Preloader Animation (cwrapper mode) --}}
    @if ($preloaderHelper->isPreloaderEnabled('cwrapper'))
        @include('adminlte::partials.common.preloader')
    @endif

    {{-- Content Header --}}
    @hasSection('content_header')
        <div class="content-header border-bottom">
            <div class="{{ config('adminlte.classes_content_header') ?: $def_container_class }}">
                @hasSection('content_header_title')
                    <h1 class="text-muted">
                        @yield('content_header_title')

                        @hasSection('content_header_subtitle')
                            <small class="text-dark">
                                <i class="fas fa-xs fa-angle-right text-muted"></i>
                                @yield('content_header_subtitle')
                            </small>
                        @endif
                    </h1>
                    <br>
                @endif
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <div class="content">
        <div class="{{ config('adminlte.classes_content') ?: $def_container_class }}">
            @stack('content')
            @yield('content')
        </div>
    </div>

</div>
