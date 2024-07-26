@extends('template.master')

@section('css')
    <link href="{{asset('plugins/custom/vis-timeline/vis-timeline.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
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
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Info</small>
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
            <a href="{{route('modules.performance.workload.index')}}" class="btn btn-bg-light btn-sm btn-icon-primary btn-text-primary my-1">
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

        <div class="row g-5 g-xl-10 mb-5">

            <div class="col-12">
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header mt-6">
                        <!--begin::Card title-->
                        <div class="card-title flex-column">
                            <h3 class="fw-bolder mb-1">Beban Kerja - SKP {{$personal_workload->year}}</h3>
                            <div class="fs-6 text-gray-400">Bulanan</div>
                            @if($personal_workload->status=='0')
                                <span class="badge badge-secondary mt-2">Belum disetujui</span>
                            @else
                                <span class="badge badge-success mt-2">Sudah disetujui</span>
                            @endif
                        </div>
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <div class="card-title flex-column">
                                <label class="fs-7 fw-bold">
                                    <span>Nama Atasan</span>
                                </label>
                                <b>{{$personal_workload->personalLead->name}}</b>
                            </div>
                            @if($personal_workload->status==0)
                                <a href="{{route('modules.performance.workload.edit',[$personal_workload->id])}}" class="btn btn-secondary btn-sm fs-8 ml-5">
                                    <i class="bi bi-pencil-square"></i> Ubah
                                </a>
                            @endif
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Body-->
                    <div class="card-body py-3">
                        <!--begin::Table container-->
                        <div class="table-responsive">
                            <!--begin::Table-->
                            <table class="table align-top fs-6 gs-2 gy-4">
                                <!--begin::Table head-->
                                <thead>
                                <tr class="fw-bolder align-middle text-light bg-primary">
                                    <th rowspan="2" class="rounded-start text-center ps-4 w-250px">Kegiatan</th>
                                    <th colspan="13" class="min-w-150px text-center">Bulan</th>
                                </tr>
                                <tr class="fw-bolder align-middle text-light bg-primary">
                                    @foreach($months as $month)
                                        <td class="text-center">{{$month['value']}}</td>
                                    @endforeach
                                    <td class="text-center">Total</td>
                                </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                @forelse($workload_activities as $workload_activity)
                                    <tr>
                                        <td>
                                            <p>
                                                <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-7">{{$workload_activity->activity->title}}</a>
                                                <span class="text-muted fw-bold text-muted d-block fs-8">{{$workload_activity->activity->activityType->name}}</span>
                                                <span class="text-muted fw-bold text-muted d-block fs-8">{{$workload_activity->activity->unit_measure}}</span>
                                                <span class="fw-bolder text-success fs-8">{{$workload_activity->activity->completion_time}} Menit</span>
                                            </p>

                                        </td>
                                        @php
                                            $total=0;
                                        @endphp
                                        @foreach($months as $month)
                                            @php
                                                $workload_activity_line = App\Models\Transaction\PersonalWorkloadActivityLine::where('month',$month['value'])->where('personal_workload_activity_id',$workload_activity->id)->first();
                                            @endphp
                                            @if($workload_activity_line)
                                                <td class="text-center">
                                                    <b>{{$workload_activity_line->workload}}</b>
                                                </td>
                                                @php($total=$total+$workload_activity_line->workload)
                                            @else
                                                <td class="text-center">
                                                    <b>0</b>
                                                </td>
                                                @php($total=$total+0)
                                            @endif
                                        @endforeach
                                        <td class="text-center">
                                            <b class="text-success">{{$total}}</b>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="{{count($months)+2}}">
                                            <p>
                                                <img src="{{asset('media/illustrations/custom/empty-data.png')}}" class="w-175px m-10"><br/>
                                                <span class="text-danger">Data Tidak ditemukan</span>
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
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
        </div>

    </div>
@endsection

@section('modal')
@endsection

@push('scripts')
@endsection

