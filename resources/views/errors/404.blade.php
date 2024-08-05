@extends('template.master')

@section('title', 'Error')


@section('page-content')
    <!-- Container -->
    <div class="flex-fill my-2">

        <!-- Error title -->
        <div class="text-center mb-2">
            <img src="{{asset('assets/images/error_bg.png')}}" class="img-fluid" style="width: 250px"  alt="">
            <h2 class="display-4 fw-semibold lh-1 mb-2">404</h2>
            <h6 class="wmin-200 mx-md-auto">Oops, an error has occurred. <br> The resource requested could not be found on this server.</h6>
        </div>
        <!-- /error title -->


        <!-- Error content -->
        <div class="text-center">
            <a href="{{route('home')}}" class="btn btn-light">
                <i class="ph-house me-2"></i>
                Kembali ke home
            </a>
        </div>
        <!-- /error wrapper -->

    </div>
    <!-- /container -->

@endsection

@push('scripts')
    <!-- Theme JS files -->

    <!-- /theme JS files -->
@endpush
