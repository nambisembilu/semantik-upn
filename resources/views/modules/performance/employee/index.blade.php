@extends('template.master')

@section('css')
    <link href="{{asset('plugins/custom/vis-timeline/vis-timeline.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('toolbar-title')
    <!--begin::Page title-->
    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Kinerja
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Bulanan</small>
            <!--end::Description--></h1>
        <!--end::Title-->
    </div>
    <!--end::Page title-->
@endsection

@section('toolbar-action')

    <!--begin::Actions-->
    <div class="d-flex">
        <!--begin::Menu-->
        <div class="me-0">
            <button class="btn btn-bg-light btn-sm btn-icon-primary btn-text-primary my-1" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                {!! getSvgIcon('media/icons/duotune/text/txt009.svg', 'svg-icon-2x svg-icon-gray-600') !!}
                Tahun
            </button>
            <!--begin::Menu 3-->
            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                <!--begin::Heading-->
                <div class="menu-item px-3">
                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Pilih Tahun</div>
                </div>
                <!--end::Heading-->

                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3">2021</a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                    <a href="#" class="menu-link px-3">2022</a>
                </div>
                <!--end::Menu item-->
            </div>
            <!--end::Menu 3-->
        </div>
        <!--end::Menu-->
    </div>
    <!--end::Actions-->
@endsection

@section('page-content')
    <div class="content container pt-0">
        <!--begin::Row-->
        <div class="row g-5 g-xl-10 pt-5">
            @if($personal_workload)
                @foreach($months as $month)
                    @php
                        $personal_assessment=App\Models\Transaction\PersonalAssessment::where('personal_id',$personal->id)->where('month', $month['value'])->where('year', date('Y'))->first();
                    @endphp
                    <div class="col-md-4 col-xl-4">
                        <!--begin::Card widget 5-->
                        <div class="card card-flush ">
                            <!--begin::Header-->
                            <div class="card-header pt-5">
                                <!--begin::Title-->
                                <div class="card-title d-flex flex-column">
                                    <!--begin::Info-->
                                    <div class="d-flex align-items-center">
                                        <!--begin::Amount-->
                                        <span class="fs-2 fw-bolder text-dark me-2 lh-1 ls-n2">{{$month['name']}} </span>
                                        <!--end::Amount-->

                                        <!--begin::Badge-->
                                        @if($personal_assessment)
                                            @if($personal_assessment->status=='0')
                                                <span class="badge badge-secondary fs-9" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Draft</span>
                                            @elseif($personal_assessment->status=='1')
                                                <span class="badge badge-primary fs-9" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Sudah dikirim</span>
                                            @elseif($personal_assessment->status=='2')
                                                <span class="badge badge-info fs-9" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Sedang dinilai</span>
                                            @elseif($personal_assessment->status=='3')
                                                <span class="badge badge-success fs-9" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Sudah dinilai</span>
                                            @endif
                                        @else
                                            <span class="badge badge-warning fs-9" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Belum Mengisi</span>
                                    @endif
                                    <!--end::Badge-->
                                    </div>
                                    <!--end::Info-->
                                    <!--begin::Subtitle-->
                                    <span class="text-gray-400 pt-1 fw-bold fs-8">Rekap Kinerja Bulanan</span>
                                    <!--end::Subtitle-->
                                </div>
                                <!--end::Title-->
                                <div class="card-toolbar">
                                    <a href="{{route('modules.performance.employee.detail',['month'=>$month['value'],'year'=>date('Y')])}}" class="btn btn-sm btn-icon btn-secondary me-2 mb-2">
                                        <i class="bi bi-search fs-5"></i>
                                    </a>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body  pt-5">
                                <!--begin::Table container-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                        <!--begin::Table head-->
                                        <thead>
                                        <tr class="border-0">
                                            <th class="p-0 w-30px"></th>
                                            <th class="p-0 min-w-150px"></th>
                                            <th class="p-0 min-w-110px"></th>
                                        </tr>
                                        </thead>
                                        <!--end::Table head-->
                                        <!--begin::Table body-->
                                        <tbody>
                                        <tr>
                                            <td>
                                                {!! getSvgIcon('media/icons/duotune/general/gen013.svg', 'svg-icon-2x svg-icon-primary') !!}
                                            </td>
                                            <td>
                                                <a href="#" class="text-gray-600 fw-bold text-hover-primary mb-1 fs-6">Absensi</a>
                                            </td>
                                            <td class="text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {!! getSvgIcon('media/icons/duotune/general/gen055.svg', 'svg-icon-2x svg-icon-primary') !!}
                                            </td>
                                            <td>
                                                <a href="{{route('modules.performance.employee.detail',['month'=>$month['value'],'year'=>date('Y')])}}" class="text-gray-600 fw-bold text-hover-primary mb-1 fs-6">Kinerja</a>
                                            </td>
                                            <td class="text-end">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {!! getSvgIcon('media/icons/duotune/abstract/abs022.svg', 'svg-icon-2x svg-icon-primary') !!}
                                            </td>
                                            <td>
                                                <a href="#" class="text-gray-600 fw-bold text-hover-primary mb-1 fs-6">Sikap dan Perilaku</a>
                                            </td>
                                            <td class="text-end">

                                            </td>
                                        </tr>
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card widget 5-->
                    </div>
                @endforeach
            @else
                <div class="row">
                    <div class="col py-10">
                        <div class="card ">
                            <div class="card-body">
                                <p class="text-center">
                                    <img src="{{asset('media/illustrations/custom/warning.png')}}" class="w-175px m-10"><br/>
                                    <span class="text-danger">Beban Kerja SKP belum dibuat / belum disetujui</span><br/>
                                    <a href="{{route('modules.performance.workload.create')}}" type="button" class="btn btn-sm btn-success mt-10">
                                        <i class="ph-plus"></i>
                                        Baru
                                    </a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
        <!--end::Row-->

    </div>
@endsection

@section('modal')
@endsection

