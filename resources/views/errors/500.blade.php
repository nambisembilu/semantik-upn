@extends('template.master')

@section('title', 'Error')


@section('page-content')
    <!-- Container -->
    <div class="flex-fill my-5">

        <!-- Error title -->
        <div class="text-center mb-2">
            <img src="{{asset('assets/images/error_bg.svg')}}" class="img-fluid mb-3" style="width: 300px"  alt="">
            <h2 class="display-4 fw-semibold lh-1 mb-2">400</h2>
            <h6 class="wmin-200 mx-md-auto">Oops, an error has occurred. <br> The server encountered an internal error and was unable to complete your request.</h6>
        </div>
        <!-- /error title -->


        <!-- Error content -->
        <div class="text-center">
            <a href="{{route('home')}}" class="btn btn-primary">
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
