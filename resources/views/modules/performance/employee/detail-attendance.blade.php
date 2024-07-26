@extends('template.master')

@section('css')
    <link href="{{asset('plugins/custom/vis-timeline/vis-timeline.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('toolbar-title')
    <!--begin::Page title-->
    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Performance
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Input</small>
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
            <a href="{{route('modules.performance.employee.index')}}" class="btn btn-bg-light btn-sm btn-icon-primary btn-text-primary my-1">
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
    @include('modules.performance.employee.widgets._detail-header')
        <!--begin::Card-->
        <div class="card card-flush py-3">
            <!--begin::Card header-->
            <div class="card-header mt-6">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                    <h3 class="fw-bolder mb-1">Absensi</h3>
                    <div class="fs-6 text-gray-400">Data Kehadiran perbulan</div>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Body-->
            <div class="card-body py-3">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-top gs-2 gy-4">
                        <!--begin::Table head-->
                        <thead>
                        <tr class="fw-bolder text-light bg-primary">
                            <th class="ps-4 rounded-start">Keterangan</th>
                            <th class="min-w-50px rounded-end">Jumlah</th>
                        </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                        @if(!empty($personal_attendance_summaries))
                            <tr>
                                <td><b>Prosentase Absensi</b></td>
                                <td>{{$personal_attendance_summaries->work_percent}}%</td>
                            </tr>
                            <tr>
                                <td><b>Hari Kerja</b></td>
                                <td>{{$personal_attendance_summaries->work_day}}</td>
                            </tr>
                            <tr>
                                <td><b>Hari Kerja (WHO)</b></td>
                                <td>{{$personal_attendance_summaries->work_day_office}}</td>
                            </tr>
                            <tr>
                                <td><b>Hari Kerja (WHF)</b></td>
                                <td>{{$personal_attendance_summaries->work_day_home}}</td>
                            </tr>
                            <tr>
                                <td><b>Terlambat</b></td>
                                <td>{{$personal_attendance_summaries->late}}</td>
                            </tr>

                            <tr>
                                <td><b>Tidak Masuk</b></td>
                                <td>{{$personal_attendance_summaries->absent}}</td>
                            </tr>
                            <tr>
                                <td><b>Tidak Masuk dengan Ijin</b></td>
                                <td>{{$personal_attendance_summaries->absent_permission}}</td>
                            </tr>
                            <tr>
                                <td><b>Cuti</b></td>
                                <td>{{$personal_attendance_summaries->absent_leave}}</td>
                            </tr>
                            <tr>
                                <td><b>Pulang Mendahului</b></td>
                                <td>{{$personal_attendance_summaries->return_before_time}}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2" class="text-center">
                                    <p>
                                        <img src="{{asset('media/illustrations/custom/sync-process.png')}}" class="w-175px m-10"><br/>
                                        <span class="text-danger">Data absensi kosong / belum  dilakukan sinkronisasi</span>
                                    </p>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Table container-->
                <!--begin::Table container-->
                <div class="table-responsive d-none">
                    <!--begin::Table-->
                    <table class="table align-top gs-2 gy-4">
                        <!--begin::Table head-->
                        <thead>
                        <tr class="fw-bolder text-light bg-primary">
                            <th class="ps-4 min-w-250px rounded-start">Tanggal</th>
                            <th class="min-w-150px text-center">Status</th>
                            <th class="min-w-100px text-center">Jam Masuk</th>
                            <th class="min-w-100px text-center">Jam Keluar</th>
                            <th class="min-w-150px text-center">Keterangan</th>
                            <th class="min-w-50px text-c rounded-end"></th>
                        </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                        @forelse($personal_attendances as $personal_attendance)
                            <tr>
                                <td>
                                    <span class="fw-bolder text-muted fs-6">{{Carbon\Carbon::parse($personal_attendance->date)->translatedFormat('l, d F Y')}}</span>
                                </td>
                                <td class="text-center">
                                    <div class="badge badge-{{searchAttendanceStatus($personal_attendance->status)['class']}} fw-bolder">{{searchAttendanceStatus($personal_attendance->status)['name']}}</div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bolder text-dark fs-7">{{Carbon\Carbon::parse($personal_attendance->in_time)->translatedFormat('H:i')}}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bolder text-dark fs-7">{{Carbon\Carbon::parse($personal_attendance->out_time)->translatedFormat('H:i')}}</span>
                                </td>
                                <td class="text-center">
                                    -
                                </td>

                                <td class="text-center">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="6">
                                    <p>
                                        <img src="{{asset('media/illustrations/custom/sync-process.png')}}" class="w-175px m-10"><br/>
                                        <span class="text-danger">Data absensi belum dilakukan sinkronisasi</span>
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
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection

@section('modal')

@endsection

@push('scripts')

    <script src="{{asset('js/customs/apps/projects/settings/settings.js')}}"></script>
    <script src="{{asset('js/customs/apps/projects/project/project.js')}}"></script>
@endsection

