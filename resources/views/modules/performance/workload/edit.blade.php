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
            <a href="{{route('modules.performance.workload.detail',$personal_workload->id)}}" class="btn btn-bg-light btn-sm btn-icon-primary btn-text-primary my-1">
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
                        </div>
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <div class="card-title flex-column">
                                <label class="fs-7 fw-bold">
                                    <span>Nama Atasan</span>
                                </label>
                                <b>{{$personal_workload->personalLead->name}}</b>
                            </div>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Body-->
                    <div class="card-body py-3">
                        <form action="{{route('modules.performance.workload.update')}}" method="post">
                        @csrf

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
                                                <button type="submit" class="btn btn-icon btn-danger btn-sm delete-activity" data-id="{{$workload_activity->id}}" data-title="{{$workload_activity->activity->activityType->name}}">
                                                    <i class="ph-x-fill fs-4"></i>
                                                </button>

                                            </td>
                                            @php
                                                $total=0;
                                            @endphp
                                            @foreach($months as $month)
                                                @php
                                                    $workload_activity_line = App\Models\Transaction\PersonalWorkloadActivityLine::where('month',$month['value'])->where('personal_workload_activity_id',$workload_activity->id)->first();
                                                @endphp
                                                @if($month['value']>=date('n')||$personal_workload->status=='0')
                                                    <td class="text-center">
                                                        <input type="text" pattern="\d*" maxlength="3" class="form-control fs-8 px-2 py-2 w-45px border-1 border-primary" size="2" name="workload_activity_{{$workload_activity->activity_id}}_{{$month['value']}}" required value="{{$workload_activity_line?$workload_activity_line->workload:0}}">
                                                    </td>
                                                @else
                                                    <td class="text-center">
                                                        <input type="text" pattern="\d*" readonly maxlength="4" class="form-control fs-8 px-2 py-2 w-45px border-1 border-primary" size="2" name="workload_activity_{{$workload_activity->activity_id}}_{{$month['value']}}" required value="{{$workload_activity_line?$workload_activity_line->workload:0}}">
                                                    </td>
                                                @endif
                                                @php
                                                    if($workload_activity_line){
                                                        $total=$total+$workload_activity_line->workload;
                                                    }
                                                    else{
                                                        $total=$total+0;
                                                    }
                                                @endphp
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
                                    @if(count($workload_activities)>0)
                                        <tr>
                                            <td class="text-center" colspan="14">
                                                <input type="hidden" name="personal_workload_id" value="{{$personal_workload->id}}">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_list_activity" class="btn btn-secondary btn-sm"><i class="bi bi-list"></i> Lihat Daftar Kegiatan
                                                </button>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modal_new_activity" class="btn btn-success btn-sm"><i class="bi bi-plus"></i> Tambah Kegiatan
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check2"></i> Simpan</button>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </form>
                    </div>
                    <!--begin::Body-->
                </div>
            </div>
        </div>

    </div>
@endsection


@section('modal')
    <!--begin::Modal - New Target-->
    <div class="modal fade" id="modal_new_activity" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <!--begin::Modal content-->
            <div class="modal-content rounded">
                <!--begin::Modal header-->
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    {!! getSvgIcon('media/icons/duotune/arrows/arr061.svg', 'svg-icon-1 svg-icon-primary m-0') !!}
                    <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--begin::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <!--begin:Form-->
                    <form action="{{route('modules.performance.workload.add-activity')}}" id="modal_new_activity_form" method="post">
                    @csrf
                    <!--begin::Heading-->
                        <div class="mb-13 text-center">
                            <!--begin::Title-->
                            <h1 class="mb-3">Tambah SKP</h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Heading-->

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-5 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-5 fw-bold mb-2">
                                <span class="required">Kegiatan</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Select-->
                            <select name="activity_id" data-control="select2" data-dropdown-parent="#modal_new_activity" data-placeholder="Pilih Kegiatan..." class="form-select form-select-solid">
                                <option value="">Pilih Kegiatan...</option>
                                @foreach($activities as $activity)
                                    <option value="{{$activity->id}}">{{$activity->title}}</option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="text-center">
                            <input type="hidden" name="personal_workload_id" value="{{$personal_workload->id}}">

                            <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan</span>
                                <span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end:Form-->
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - New Target-->
    <!--begin::Modal - List Activity-->
    <div class="modal fade" id="modal_list_activity" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-fullscreen modal-dialog-centered">
            <!--begin::Modal content-->
            <div class="modal-content rounded">
                <!--begin::Modal header-->
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    {!! getSvgIcon('media/icons/duotune/arrows/arr061.svg', 'svg-icon-1 svg-icon-primary m-0') !!}
                    <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--begin::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body scroll-y px-5 px-lg-5 pt-0">
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">Daftar SKP </h1>
                        <!--end::Title-->
                    </div>
                    <!--end::Heading-->
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-3">
                        <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
                    {!! getSvgIcon('media/icons/duotune/general/gen021.svg','svg-icon svg-icon-1 position-absolute ms-4')!!}
                    <!--end::Svg Icon-->
                        <input type="text"  data-kt-table-filter="search" class="form-control form-control-solid ps-14" placeholder="Pencarian"/>
                    </div>
                    <!--end::Search-->
                    <div class="row d-flex">
                        <div class="col-12">
                            <!--begin::Table-->
                            <table style="width: 100%" class="table align-middle table-row-bordered table-striped fs-6 gy-7 gs-7" id="datatable-list-activity">
                                <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-white fw-bolder fs-7 text-uppercase bg-primary align-top">
                                    <th class="rounded-start min-w-250px align-top">Jabatan - Unit Kerja</th>
                                    <th class="align-top  min-w-200px">Kegiatan</th>
                                    <th class="rounded-end  min-w-100px align-top ">Waktu Penyelesaian</th>
                                </tr>
                                <!--end::Table row-->
                                </thead>
                            </table>
                            <!--end::Table-->
                        </div>
                    </div>
                    <!--begin::Actions-->
                    <div class="text-center">

                        <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Tutup</button>
                    </div>
                </div>
                <!--end::Modal body-->
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - List Activity-->
@endsection

@push('scripts')
    <script src="{{asset('js/customs/apps/projects/project/project.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.delete-activity').click(function (e) {
                var title=$(this).attr('data-title');
                var id=$(this).attr('data-id');
                e.preventDefault();
                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    title: 'Konfirmasi',
                    html: "Apakah anda yakin akan menghapus <b>" + title + "</b> ?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya",
                    cancelButtonText: "Batal",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            //method: "delete",
                            type: "POST",
                            url: '{{route('modules.performance.workload.delete-activity')}}',
                            data: 'personal_workload_activity_id=' + id,
                            success: function (resp) {
                                console.log(resp);
                                if (resp.status == '1') {
                                    toastr.success(resp.message);
                                    // delete row data from server and re-draw datatable
                                    window.location.reload();
                                } else {
                                    toastr.error(resp.message);
                                }
                            }
                        })
                    }
                });
            })
        });
    </script>
    <script type="text/javascript">
        "use strict";
        var KTDatatablesServerSide = function () {
            // Shared variables
            var table;
            var dt;
            var initDatatable = function () {
                dt = $('#datatable-list-activity').DataTable({
                    "processing": true,
                    "responsive": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('modules.master.activity.datatable-work-unit',$personal_workload->personal->work_unit_id) }}",
                        "type": "get",
                        "data": function (d) {
                            // d.additional_param = additional_value;
                        }
                    },
                    "lengthMenu": [5, 10, 25, 100],
                    "scrollX": true,
                    "columns": [
                        {data: 'work_position', title: 'Jabatan Unit Kerja', orderable: true, searchable: true},
                        {data: 'activity', title: 'Kegiatan', orderable: true, searchable: true},
                        {data: 'completion_time', title: 'Waktu Penyelesaian', orderable: true, searchable: true},
                    ],
                })

                table = dt.$;

                // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
                dt.on('draw', function () {
                    KTMenu.createInstances();
                });
            }
            // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
            var handleSearchDatatable = function () {
                const filterSearch = document.querySelector('[data-kt-table-filter="search"]');
                filterSearch.addEventListener('keyup', function (e) {
                    dt.search(e.target.value).draw();
                });
            }
            return {
                init: function () {
                    initDatatable();
                    handleSearchDatatable();
                }
            }
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTDatatablesServerSide.init();
        });
    </script>
@endsection

