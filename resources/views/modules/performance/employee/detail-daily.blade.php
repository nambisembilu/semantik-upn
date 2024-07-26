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
                    <h3 class="fw-bolder mb-1">Kegiatan Harian</h3>
                    <div class="fs-6 text-gray-400">Semua kegiatan sehari-hari yang mendukung tugas dan fungsi</div>
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
                            <th class="ps-4 min-w-250px rounded-start">Kegiatan</th>
                            <th class="min-w-150px">Capaian</th>
                            <th class="min-w-150px">Pendukung Kegiatan</th>
                            <th class="min-w-150px">Dokumen</th>
                            <th class="min-w-100px">Keterangan</th>
                            <th class="min-w-80px">Nilai</th>
                            <th class="min-w-50px text-end rounded-end"></th>
                        </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                        <tr>
                            <td>
                                <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">Menjadi Panitia Rekrutmen Pegawai</a>
                                <span class="text-muted fw-bold text-muted d-block fs-7">Kepanitiaan</span>
                                <span class="fw-bolder text-primary fs-8">Apr 15, 2021</span>
                            </td>

                            <td>

                                <a href="#" class="text-dark fw-bolder text-hover-primary d-block mb-1 fs-6">2</a>
                                <span class="text-muted fw-bold text-muted d-block fs-7">Kegiatan</span>
                            </td>
                            <td>
                                <span class="fw-bolder fs-7">Memproses Kartu Pegawai Tenaga Kependidikan</span>

                            </td>
                            <td>
                                <a href="#" class="btn btn-outline btn-sm btn-outline-dashed btn-outline-danger btn-active-light-danger fs-9 p-2">
                                    <i class="bi bi-file-pdf text-danger fs-2"></i>
                                    Upload.PDF
                                </a>
                            </td>
                            <td>
                                <span class="fw-bolder fs-8 text-gray-400">Sudah</span>
                            </td>
                            <td>
                                <span class="text-dark fw-bolder d-block fs-7 mt-1">{{rand(7,10)*10}} %</span>
                            </td>

                            <td class="text-center">
                            </td>
                        </tr>
                        <tr>
                            <td>

                                <textarea name="description" class="form-control form-control-solid h-80px resize-none mb-3"></textarea>
                                <div class="position-relative d-flex align-items-center">
                                {!! getSvgIcon('media/icons/duotune/general/gen014.svg', 'svg-icon position-absolute ms-4 mb-1 svg-icon-2') !!}
                                <!--end::Svg Icon-->
                                    <input class="form-control form-control-solid ps-12 fs-8" name="date" placeholder="Tanggal" id="kt_datepicker_1"/>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="capaian" class="form-control form-control-solid mb-3 fs-8 p-3" placeholder="Capaian">
                                <select class="form-select mb-2" data-control="select2" data-placeholder="Luaran" data-allow-clear="true" multiple="multiple">
                                    <option></option>
                                    <option value="Computers">Kegiatan</option>
                                    <option value="Watches">Dokumen</option>
                                    <option value="Headphones">Laporan</option>
                                    <option value="Footwear">Progress</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-select mb-2" data-control="select2" data-placeholder="Kegiatan" data-allow-clear="true" multiple="multiple">
                                    <option></option>
                                    <option value="Computers">Memproses Kartu Pegawai Tenaga Kependidikan</option>
                                    <option value="Watches">Memproses Pelaksanaan Ujian Eselon</option>
                                    <option value="Headphones">Menjadi Panitia Rekrutmen Pegawai</option>
                                    <option value="Footwear">Mengolah Data Dosen Keperluan Perengkingan Dikti</option>
                                </select>
                            </td>
                            <td>

                                <a href="#" class="btn btn-outline btn-sm btn-outline-dashed btn-outline-primary btn-active-light-primary fs-9 p-2">
                                    <i class="bi bi-file-earmark-arrow-up text-primary fs-2"></i>
                                    Dokumen
                                </a>
                            </td>
                            <td>
                                <textarea name="description" class="form-control form-control-solid h-80px resize-none"></textarea>
                            </td>
                            <td>
                                <span class="text-danger fw-light d-block fs-7 mt-1">Kosong</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                    {!! getSvgIcon('media/icons/duotune/general/gen037.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                </button>
                            </td>
                        </tr>
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

