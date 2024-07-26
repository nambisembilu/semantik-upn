@extends('template.master')

@section('title', 'Home Apps')

@section('sidebar')
    @include('template.sidebar')
@endsection

@section('page-head')
    <div class="page-header">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Kuisioner - <span class="fw-normal">Home</span>
                </h4>

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
    <div class="content pt-0">

        <!-- Basic card -->
        <div class="card">

            <div class="card-body text-center">
                <p class="mt-2 mb-3">Ini adalah halaman utama pada modul <b>Kinerja</b> silahkan pilih menu di sebelah kiri!</p>
                <img class="img-fluid mt-2" width="400" src="{{asset('assets/images/illustration/work.png')}}">
            </div>
        </div>
        <!-- /basic card -->




    </div>
    <!-- /content area -->

@endsection

@push('scripts')
    <!-- Theme JS files -->

    <!-- /theme JS files -->
@endpush
