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

            <!--begin::Card body-->
            <div class="card-body px-10">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-middle fs-6 gs-2 gy-4">
                        <!--begin::Table head-->
                        <thead>
                        <!--begin::Table row-->
                        <tr class="text-start bg-secondary text-muted fw-bolder fs-7 text-uppercase">
                            <th class="rounded-start min-w-125px text-center  ps-4">Pegawai</th>
                            <th class="min-w-125px">Unit Kerja</th>
                            <th class="min-w-125px text-center">Jabatan & Golongan</th>
                            <th class="min-w-100px text-center  ps-4">Tahun</th>
                            <th class="min-w-100px text-center ">Jumlah SKP</th>
                            <th class="min-w-100px text-center rounded-end ">Status</th>
                        </tr>
                        <!--end::Table row-->
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody class="text-gray-600 fw-bold">
                        @if(count($personal_workloads)>0)
                            @foreach($personal_workloads as $personal_workload)
                                <tr>
                                    <!--begin::User=-->
                                    <td>
                                        <div class="d-flex">
                                            <!--begin:: Avatar -->
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <div class="symbol-label" style="background-image:url({{$personal_workload->personal->user->avatar}})"></div>
                                            </div>
                                            <!--end::Avatar-->
                                            <!--begin::User details-->
                                            <div class="d-flex flex-column">
                                                 @if($personal_workload->is_completed==1)
                                                    <a href="{{route('modules.performance.workload.detail-approval',[$personal_workload->id])}}"  target="_blank" class="text-primary text-hover-warning mb-1">{{$personal_workload->personal->name}}</a>
                                                    <span>{{$personal_workload->personal->work_id_number}}</span>
                                                @else
                                                    <p class="text-dark mb-1">{{$personal_workload->personal->name}}</p>
                                                    <span>{{$personal_workload->personal->work_id_number}}</span>
                                                    <span class="text-danger fw-bold fs-8">Belum mengisi skp bulanan</span>
                                                @endif

                                            </div>
                                            <!--begin::User details-->
                                        </div>
                                    </td>
                                    <!--end::User=-->
                                    <!--begin::Role=-->
                                    <td>
                                        <div class="d-flex flex-column align-content-start">
                                            <span class="text-gray-800 mb-1">{{$personal_workload->personal->unit->name}}</span>
                                            <span>{{$personal_workload->personal->position->name}}</span>
                                        </div>
                                    </td>
                                    <!--end::Role=-->
                                    <td class=" text-center">
                                        <span class="text-dark mb-1">{{$personal_workload->personal->rank->grade_code}} - {{$personal_workload->personal->rank->name}}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-primary mb-1">{{$personal_workload->year}}</span>
                                    </td>
                                    <td class=" text-center">
                                        <span class="badge badge-circle badge-success">{{$personal_workload->workloadActivity->count()}}</span>
                                    </td>
                                    <td class=" text-center">
                                        @if($personal_workload->status==1)
                                            <span class="badge badge-success">Disetujui</span>
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
                                <td class="text-center" colspan="6">
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

