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
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Tambah Baru</small>
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

            <form action="{{route('modules.performance.workload.save')}}" method="post">
                @csrf
                <div class="col-12">
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header mt-6">
                            <!--begin::Card title-->
                            <div class="card-title flex-column">
                                <h3 class="fw-bolder mb-2">Pembuatan SKP {{date('Y')}}</h3>
                                <div class="fs-6 text-gray-400">Pilih kegiatan berdasarkan jabatan dan fungsi</div>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <div class="card-title flex-column">
                                    <label class="fs-7 fw-bold">
                                        <span class="required">Nama Atasan</span>
                                    </label>
                                    <select name="lead_id" data-control="select2" data-placeholder="Pilih Atasan..." class="form-select form-select-solid form-select-sm w-lg-400px w-sm-250px">
                                        <option value="">Pilih Atasan...</option>
                                        @foreach($personal_leads as $personal_lead)
                                            <option value="{{$personal_lead->id}}">{{$personal_lead->name}} - {{$personal_lead->unit->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--end::Card toolbar-->
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
                                        <th class="rounded-start text-center ps-4 min-w-300px">Kegiatan</th>
                                        <th class="min-w-150px">Satuan Hasil</th>
                                        <th class="text-center w-200px">Waktu Penyelesaian</th>
                                        <th class="rounded-end min-w-50px">Pilih</th>
                                    </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                    @forelse($activities as $activity)
                                        <tr>
                                            <td>

                                                <label class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$activity->title}}</label>
                                                <span class="text-muted fw-bold text-muted d-block fs-7">{{$activity->activityType->name}}</span>
                                            </td>
                                            <td class="">
                                                <span class="text-muted fw-bold text-muted d-block fs-7">{{$activity->unit_measure}}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bolder text-success fs-6">{{$activity->completion_time}}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid form-check-lg m-1">
                                                    <input class="form-check-input h-25px w-25px" name="workload_activity[]" type="checkbox" value="{{$activity->id}}">
                                                </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="5">
                                                <p>
                                                    <img src="{{asset('media/illustrations/custom/empty-data.png')}}" class="w-175px m-10"><br/>
                                                    <span class="text-danger">Data Tidak ditemukan</span>
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    @if(count($activities)>0)
                                        <tr>
                                            <td class="text-center" colspan="5">
                                                <input type="hidden" name="year" value="{{date('Y')}}">
                                                <button type="submit" href="#" class="btn btn-primary btn-sm">Simpan</button>
                                            </td>
                                        </tr>
                                    @endif
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

            </form>
        </div>

    </div>
@endsection

@section('modal')
@endsection

@push('scripts')
@endsection

