@extends('template.master')

@section('css')
    <link href="{{ asset('plugins/custom/vis-timeline/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css" />
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
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Penilaian</small>
            <!--end::Description-->
        </h1>
        <!--end::Title-->
    </div>
    <!--end::Page title-->
@endsection

@section('toolbar-action')
    <!--begin::Actions-->
    <div class="d-flex">
        <!--begin::Menu-->
        <div class="me-0">
            <a href="{{ route('modules.performance.result.index') }}" class="btn btn-bg-light btn-sm btn-icon-primary btn-text-primary my-1">
                {!! getSvgIcon('media/icons/duotune/arrows/arr021.svg', 'svg-icon-2x svg-icon-secondary') !!}
                Back
            </a>
        </div>
        <!--end::Menu-->
    </div>
    <!--end::Actions-->
@endsection

@section('page-content')
    <div class="content container pt-0">
        <!--begin::Row-->
        <div class="card card-flush mb-3">
            <!--begin::Card header-->
            <div class="card-header mt-3">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                    <h3 class="fw-bolder mb-1">Pelaksanaan Kinerja Tahun {{ $assessment_year->year }}</h3>
                </div>
                <div class="card-toolbar align-left">
                    <div class="min-w-100px">
                        <a href="{{ route('modules.performance.workplan.action-plan', $assessment_year->year ) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus"></i> Tambah Rencana Aksi
                        </a>                          
                    </div>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            
            <div class="card-body p-0 px-7">
                <div class="row">
                    @foreach ($assessment_period as $assessment_periods)
                    <div class="col-md-6 col-sm-12 p-5">
                        <!--begin::Details toggle-->
                        <div class="fs-4 py-3 px-3 bg-primary opacity-75 text-light rounded">
                            <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#assessment_{{ $assessment_periods->id }}" role="button" aria-expanded="false" aria-controls="kt_view_details">{{ $assessment_periods->name }}
                                <span class="ms-2 rotate-180">
                                    {!! getSvgIcon('media/icons/duotune/arrows/arr073.svg', 'svg-icon-2x svg-icon-secondary') !!}
                                </span>
                            </div>
                        </div>
                        <!--end::Details toggle-->
                        <div class="separator"></div>
                        <!--begin::Details content-->
                        <div id="assessment_{{ $assessment_periods->id }}" class="collapse show">
                            <div class="bg-light-primary border border-dashed border-primary d-flex flex-column p-5 rounded">
                                <div class="d-flex mb-7">
                                    <div class="d-flex align-items-start flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                                        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3 pb-6">
                                            <a href="#" class="fs-6 fw-bold text-gray-600">
                                                {{ Carbon\Carbon::parse($assessment_periods->start_period)->translatedFormat('d F Y') }}
                                                -
                                                {{ Carbon\Carbon::parse($assessment_periods->end_period)->translatedFormat('d F Y') }}
                                            </a>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div class="p-1">
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a href="{{ route('modules.performance.workplan.eviden', $assessment_periods->id ) }}" class="btn btn-sm btn-light-info mb-2">
                                                        Pengisian Bukti Dukung dan Lihat Hasil
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-light-linkedin mb-2">
                                                        Feddback Perilaku
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection



