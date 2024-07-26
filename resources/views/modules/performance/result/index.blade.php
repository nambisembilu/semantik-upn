@extends('template.master')

@section('css')
    <link href="{{ asset('plugins/custom/vis-timeline/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('toolbar-title')
    <!--begin::Page title-->
    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
         class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Sasaran Kinerja
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Pegawai</small>
            <!--end::Description-->
        </h1>
        <!--end::Title-->
    </div>
    <!--end::Page title-->
@endsection


@section('page-content')
    <div class="content container pt-0">
        <!--begin::Row-->
        <div class="row g-5 g-xl-10 pt-1">
            <div class="col-12">
                <div class="card card-bordered shadow-sm card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header align-items-center mt-3">
                        <h3 class="card-title">
                            Daftar SKP
                        </h3>
                    </div>
                    <!--begin::Body-->
                    <div class="card-body pt-5">
                        @forelse ($work_performance_results as $work_performance_result)
                            <!--begin::Item-->
                            <div class="d-flex flex-column border-gray-300 border-dotted rounded p-5 pt-10 mb-5">
                                <div class="d-flex mb-7">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-75px symbol-2by3 flex-shrink-0 me-4">
                                        <div class="symbol-label fs-1 fw-bold bg-gray-300 text-dark">
                                            <b>{{ $work_performance_result->year }}</b>
                                        </div>
                                    </div>
                                    <!--end::Symbol-->
                                    <!--begin::Section-->
                                    <div class="d-flex align-items-start flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                                        <!--begin::Title-->
                                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                                            <a href="#" class="fs-6 text-gray-800 fw-bolder">
                                                {{ Carbon\Carbon::parse($work_performance_result->start_period)->translatedFormat('d F Y') }}
                                                -
                                                {{ Carbon\Carbon::parse($work_performance_result->end_period)->translatedFormat('d F Y') }}
                                            </a>
                                            <span class="text-primary fw-bolder fs-7 my-1">{{ $work_performance_result->measurement }}</span>
                                            <p class="text-gray-600 fw-bold fs-7">
                                                {{ $work_performance_result->workRank->name }}
                                                <b class="text-gray-400  fw-bold">
                                                    {{ $work_performance_result->workRank->grade_code }}
                                                </b>
                                            </p>
                                            <b class="text-gray-400  fw-bold">
                                                {{ $work_performance_result->workPosition->name }}
                                            </b>
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Info-->
                                        <div class="text-end py-lg-0 py-2">
                                            @if ($work_performance_result->status == '1')
                                                <span class="fs-7 badge badge-success">Selesai</span>
                                            @else
                                                <span class="fs-7 badge badge-secondary">Sedang berlangsung</span>
                                            @endif
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                            </div>
                            <!--end::Item-->
                        @empty
                            <div class="d-flex justify-content-center mb-20">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="{{ asset('media/illustrations/custom/empty-data.png') }}" class="w-175px"><br>
                                    <span class="text-danger">Data masih kosong</span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <!--end::Body-->
                </div>
            </div>
        </div>
        <!--end::Row-->

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/customs/apps/projects/project/project.js') }}"></script>
@endsection
