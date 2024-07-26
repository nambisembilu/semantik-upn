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
                    <h3 class="fw-bolder mb-1">Pelaksanaan Kinerja</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Body-->
            <div class="card-body py-2">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-top gx-3 gy-5 table-rounded table-striped">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="fw-bolder text-light bg-primary">
                                <th class="text-center">HASIL KERJA</th>
                                <th class="text-center">SIKAP/PERILAKU</th>
                                <th class="text-center">PENILAIAN</th>
                                <th class="text-center">CAPAIAN ORGANISASI</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($assessment_period as $assessment_periods)
                                <tr>
                                <td colspan="5">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-column border-gray-300 border-dotted rounded p-5 pt-10 mb-5">
                                        <div class="d-flex mb-7">
                                            <!--begin::Symbol-->
                                            <div class="symbol symbol-75px symbol-2by3 flex-shrink-0 me-4">
                                                <div class="symbol-label fs-1 fw-bold bg-gray-300 text-dark">
                                                    <b>{{ $assessment_periods->year }}</b>
                                                </div>
                                            </div>
                                            <!--end::Symbol-->
                                            <!--begin::Section-->
                                            <div class="d-flex align-items-start flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                                                <!--begin::Title-->
                                                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                                                    <a href="#" class="fs-6 text-gray-800 fw-bolder">
                                                        {{ Carbon\Carbon::parse($assessment_periods->start_period)->translatedFormat('d F Y') }}
                                                        -
                                                        {{ Carbon\Carbon::parse($assessment_periods->end_period)->translatedFormat('d F Y') }}
                                                    </a>
                                                    <span class="text-primary fw-bolder fs-7 my-1">{{ $assessment_periods->name }}</span>
                                                </div>
                                            </div>
                                            <!--end::Section-->
                                        </div>
                                    </div>
                                    <!--end::Item-->


                                </td>
                                </tr>
                                <tr>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td class="align-left">
                                    <div class="d-flex justify-content-between">
                                        <div class="p-1">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="{{ route('modules.performance.workplan.action-plan', [$assessment_periods->id, $personal->id] ) }}" class="btn btn-sm btn-light-facebook mb-2">Rencana Aksi
                                                </a>
                                                <a href="#" class="btn btn-sm btn-light-info mb-2">Pengisian Bukti Dukung dan Lihat Hasil
                                                </a>
                                                <a href="#" class="btn btn-sm btn-light-linkedin mb-2">Feddback Perilaku
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Table container-->
            </div>
            <!--begin::Body-->
        </div>

    </div>
@endsection



