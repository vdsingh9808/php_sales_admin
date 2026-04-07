<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') @hasSection('title')
            |
        @endif Admin | ENI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('assets/vendor/css/core.css')}}" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="{{asset('assets/vendor/css/theme-default.css')}}"
          class="template-customizer-theme-css"/>
    <link rel="stylesheet" href="{{asset('assets/css/demo.css')}}"/>
    <link href="{{ asset('assets/plugins/toastr/toastr.css') }}" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.5.0/octicons.min.css" rel="stylesheet">
    <link href="https://www.jquery-az.com/boots/css/bootstrap-colorpicker/bootstrap-colorpicker.css" rel="stylesheet">
    <link href="https://www.jquery-az.com/boots/css/bootstrap-colorpicker/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/dataTables/datatables.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/jquery-datatables-checkboxes-1.2.12/css/dataTables.checkboxes.css') }}"
          rel="stylesheet"/>

</head>
<body>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        @include('layouts.sidebar')
        <div class="layout-page custom-margin-lg">
            @include('layouts.header')
            <div class="container-xxl flex-grow-1 container-p-y">
                @yield('content')
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
</div>

<script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/js/config.js')}}"></script>

<script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>

<script src="{{asset('assets/vendor/js/menu.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script
    src="{{ asset('assets/plugins/jquery-datatables-checkboxes-1.2.12/js/dataTables.checkboxes.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTable.js') }}"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script>
    (function (document, window, $) {
        'use strict';

        <?php
        $alerts = ['success', 'info', 'warning', 'error'];
        ?>

            @foreach($alerts as $alert)
            @if(session()->has($alert))
            toastr['{{ $alert }}']('{{ session()->get($alert) }}');
        @endif
        @endforeach

        <?php
        session()->forget($alerts);
        ?>

    })(document, window, jQuery);

    $(document).ready(function () {
        $('.select2').select2({width: '100%'})
    })
    $(document).ready(function () {
        $('.js-example-basic-multiple').select2({width: '100%'});
    });

</script>

<script src="https://www.jquery-az.com/boots/js/bootstrap-colorpicker/bootstrap-colorpicker.js"></script>
<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@stack('custom-scripts')
</body>
</html>
<style>
    .table.dataTable {
        width: 100% !important;
    }

    @media (min-width: 992px) {
        .custom-margin-lg {
            margin-left: 278px;
        }
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #FFF0D0 !important;
        border: none !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #C39F74 !important;
        border-right: 1px solid #C39F74 !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
        color: #C39F74 !important;
    }

    .select2-selection__clear {
        display: none;
    }

    .select2-selection--multiple::after {
        content: "";
        position: absolute;
        top: 50%;
        right: 8px;
        margin-top: -2px;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 5px 5px 0 5px;
        border-color: #999 transparent transparent transparent;
        pointer-events: none;
    }

</style>
