@extends('template.master')

@section('css')
    <link href="{{ asset('plugins/custom/vis-timeline/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('plugins/custom/typeahead/typeahead.css') }}" rel="stylesheet" type="text/css"/>
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
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Detil</small>
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
    <!--begin::Container-->
    <div class="content container pt-0">
        @include('modules.performance.result.widgets._detail-header')
        <!--begin::Tasks-->
        <div class="card card-flush mb-3">
            <!--begin::Card header-->
            <div class="card-header mt-3">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                    <h3 class="fw-bolder mb-1">Hasil Kerja</h3>
                    <div class="fs-6 text-gray-400">A.UTAMA</div>
                </div>
                @if(!empty($work_performance_period))
                    <div class="card-toolbar p-2">
                        @if($work_performance_period->status==0)
                            <span class="fs-5 badge badge-primary">Diajukan</span>
                        @elseif($work_performance_period->status==1)
                            <span class="fs-5 badge badge-info">Dinilai</span>
                        @else
                            <span class="fs-5 badge badge-success">Selesai</span>
                        @endif
                    </div>
                @endif
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Body-->
            <div class="card-body py-2">
                @include('modules.performance.result.widgets._wrp-table')
            </div>
            <!--begin::Body-->
        </div>
        <!--end::Tasks-->
        <!--begin::Tasks-->
        <div class="card card-flush mb-3">
            <!--begin::Card header-->
            <div class="card-header mt-3">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                    <h3 class="fw-bolder mb-1">PERILAKU KINERJA</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Body-->
            <div class="card-body py-2">
                @include('modules.performance.result.widgets._behavior-table')
            </div>
            <!--begin::Body-->
        </div>
        <!--end::Container-->
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/customs/apps/projects/project/project.js') }}"></script>
    <script src="{{ asset('plugins/custom/typeahead/typeahead.bundle.min.js') }}"></script>
@endsection
