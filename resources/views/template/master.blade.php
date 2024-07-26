<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ getenv('APP_NAME') }} - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset(getenv('APP_LOGO_ICON')) }}">
    <!-- Global stylesheets -->
    @vite(['resources/css/app.css'])
    <!-- /global stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    @stack('styles')
</head>

<body>
<!-- Main navbar -->
@include('template.navbar')
<!-- /main navbar -->
<!-- Page content -->
<div class="page-content">
    @yield('sidebar')
    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Inner content -->
        <div class="content-inner">

            <!-- Page header -->
            @yield('page-head')
            <!-- /page header -->

            <!-- Content area -->
            @yield('page-content')
            <div id="loader"></div>
            <!-- /content area -->
            
            <!-- Footer -->
            @include('template.page-footer')
            <!-- /footer -->
        </div>
        <!-- /inner content -->
    </div>
    <!-- /main content -->

    <!-- Modal -->
    @stack('modals')
    <!-- /modal -->
</div>
<!-- /page content -->

<!-- Notifications -->
@include('template.notification')
<!-- /notifications -->
<!-- Core JS files -->
<script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/tables/datatables/extensions/select.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
@vite(['resources/js/app.js'])
<!-- /core JS files -->

@if($errors->any())
    <script>
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "showDuration": "5000",
            "timeOut": "5000",
        }
        toastr.error('{!! nl2br(str_replace('"','',str_replace(array("\r", "\n"), '', $errors->first()))) !!}')
    </script>
@endif

@if (\Session::has('toast-success'))
    <script>
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "showDuration": "3000",
            "timeOut": "3000",
        }
        toastr.success('{!! \Session::get('success') !!}')
    </script>
@endif

@if (\Session::has('toast-info'))
    <script>
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "showDuration": "3000",
            "timeOut": "3000",
        }
        toastr.info('{!! \Session::get('info') !!}')
    </script>
@endif

@if (\Session::has('toast-warning'))
    <script>
        toastr.options = {
            "positionClass": "toast-bottom-right",
            "showDuration": "3000",
            "timeOut": "3000",
        }
        toastr.warning('{!! \Session::get('warning') !!}')
    </script>
@endif
@stack('scripts')
</body>

</html>
