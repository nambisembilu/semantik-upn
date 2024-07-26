@extends('template.master')

@section('css')
    <link href="{{asset('plugins/custom/vis-timeline/vis-timeline.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('toolbar-title')
    <!--begin::Page title-->
    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Beban Kerja
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Data</small>
            <!--end::Description--></h1>
        <!--end::Title-->
    </div>
    <!--end::Page title-->
@endsection

@section('toolbar-action')

    <!--begin::Actions-->
    <div class="d-flex">

    </div>
    <!--end::Actions-->
@endsection

@section('page-content')
    <!--begin::Container-->
    <div class="content container pt-0">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">

                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end">
                        <a href="{{route('modules.performance.workload.create')}}" type="button" class="btn btn-sm btn-success">
                            <i class="ph-plus"></i>
                            Baru
                        </a>

                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle fs-6 gs-2 gy-4">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start bg-secondary text-muted fw-bolder fs-7 text-uppercase">
                            <th class="rounded-start min-w-125px text-center  ps-4">Tahun</th>
                            <th class="min-w-125px text-center ">Jumlah SKP</th>
                            <th class="w-lg-300px w-sm-250px text-center ">Status</th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="text-gray-600 fw-bold">
                        @if(count($personal_workloads)>0)
                            @foreach($personal_workloads as $personal_workload)
                                <tr>
                                    <td class="text-center">
                                        <a href="{{route('modules.performance.workload.detail',[$personal_workload->id])}}" class="text-primary text-hover-warning mb-1">{{$personal_workload->year}}</a>
                                        @if($personal_workload->is_completed!=1)
                                            <br><span class="text-danger fw-bold fs-8">Belum mengisi skp bulanan</span>
                                        @endif

                                    </td>
                                    <td class=" text-center">
                                        <span class="badge badge-circle badge-success">{{$personal_workload->workloadActivity->count()}}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($personal_workload->status==1)
                                            <div class="d-flex flex-column">
                                                <div><span class="badge badge-success">Disetujui</span></div>
                                                <b>{{$personal_workload->personalLead->name}}</b>
                                                <span class="text-muted">{{Carbon\Carbon::parse($personal_workload->approve_time)->translatedFormat('l, d F Y')}}</span>
                                            </div>
                                        @else
                                            <span class="badge badge-secondary">Belum disetujui</span>
                                        @endif
                                    </td>
                                    <!--begin::Joined-->
                                </tr>
                                <!--end::Table row-->
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="3">
                                    <p>
                                        <img src="{{asset('media/illustrations/custom/empty-people.png')}}" class="w-175px m-10"><br/>
                                        <span class="text-danger">Belum Beban Kerja SKP</span>
                                    </p>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection

@section('modal')
@endsection

