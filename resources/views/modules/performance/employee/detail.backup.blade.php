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
        <div class="row g-5 g-xl-10 mb-5" id="main-activity-section">
            <div class="col-12">
                <!--begin::Card-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header mt-6">
                        <!--begin::Card title-->
                        <div class="card-title flex-column">
                            <h3 class="fw-bolder mb-1">Kegiatan Utama</h3>
                            <div class="fs-6 text-gray-400">Semua kegiatan berdasarkan jabatan dan fungsi</div>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_new_activity">Tambah</a>
                        </div>
                        <!--end::Card toolbar-->
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
                                    <th class="min-w-100px">SKP</th>
                                    <th class="min-w-80px">Capaian</th>
                                    <th class="min-w-175px">Dokumen</th>
                                    <th class="min-w-100px">Keterangan</th>
                                    @if($personal_assessment)
                                        @if($personal_assessment->status>=3)
                                            <th class="min-w-80px">Nilai</th>
                                        @endif
                                    @endif
                                    <th class="min-w-50px text-end rounded-end"></th>
                                </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                @foreach($personal_workload_activities as $personal_workload_activity)
                                    @php
                                        if($personal_assessment)
                                        {
                                            $personal_activity = App\Models\Transaction\PersonalActivity::where('personal_assessment_id',$personal_assessment->id)->where('activity_id',$personal_workload_activity->activity->id)->first();
                                        }
                                        else
                                        {
                                            $personal_activity = null;
                                        }
                                    @endphp
                                    @if(!$personal_activity)
                                        <form action="{{route('modules.performance.employee.create-activity')}}"  method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="activity_id" value="{{$personal_workload_activity->activity->id}}">
                                            <input type="hidden" name="personal_id" value="{{$personal->id}}">
                                            <input type="hidden" name="month" value="{{$month}}">
                                            <input type="hidden" name="year" value="{{$year}}">
                                            <tr id="row-activity-{{$personal_workload_activity->activity->id}}">
                                                <td>
                                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$personal_workload_activity->activity->title}}</a>
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">{{$personal_workload_activity->activity->activityType->name}}</span>
                                                </td>
                                                <td>
                                                    <input type="number" required name="target" class="form-control form-control-solid mb-3 fs-8 mb-lg-0 p-3" value="{{$personal_workload_activity->activity->default_result}}" placeholder="Capaian">
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">{{$personal_workload_activity->activity->unit_measure}}</span>
                                                </td>
                                                <td>
                                                    <input type="number" required name="achieve" class="form-control form-control-solid mb-3 fs-8 mb-lg-0 p-3" placeholder="Capaian">
                                                </td>
                                                <td>
                                                    <input type="file" name="document" accept=".pdf, .png, .jpg, .jpeg" class="form-control form-control-solid fs-9">
                                                </td>
                                                <td>
                                                    <textarea name="notes" class="form-control form-control-solid fs-8 h-80px resize-none"></textarea>
                                                </td>
                                                <td class="text-center">
                                                    @if($personal_assessment)
                                                        @if($personal_assessment->status<1)
                                                            <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                                                {!! getSvgIcon('media/icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                                            {!! getSvgIcon('media/icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </form>
                                    @else
                                        <form action="{{route('modules.performance.employee.update-activity')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="personal_activity_id" value="{{$personal_activity->id}}">
                                            <tr id="row-activity-{{$personal_workload_activity->activity->id}}">
                                                <td>
                                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$personal_workload_activity->activity->title}}</a>
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">{{$personal_workload_activity->activity->activityType->name}}</span>
                                                </td>
                                                <td>
                                                    <input type="number" required name="target" class="form-control form-control-solid mb-3 fs-8 mb-lg-0 p-3" value="{{$personal_activity->target}}" placeholder="Capaian">
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">{{$personal_workload_activity->activity->unit_measure}}</span>
                                                </td>
                                                <td>
                                                    <input type="number" required name="achieve" class="form-control form-control-solid mb-3 fs-8 mb-lg-0 p-3" value="{{$personal_activity->achieve}}" placeholder="Capaian">
                                                </td>
                                                <td>
                                                    @if(!empty($personal_activity->attachment))
                                                        <a href="{{Illuminate\Support\Facades\Storage::disk('public')->url($personal_activity->attachment)}}" target="_blank" class="btn btn-outline btn-sm btn-outline-dashed btn-outline-primary btn-active-light-primary fs-9 p-2">
                                                            {!! getSvgIcon('media/icons/duotune/files/fil022.svg', 'svg-icon-2x svg-icon-primary') !!}
                                                            Dokumen
                                                        </a>
                                                    @else
                                                        <input type="file" name="document" accept=".pdf, .png, .jpg, .jpeg" class="form-control form-control-solid fs-9">
                                                    @endif

                                                </td>
                                                <td>
                                                    <textarea name="notes" class="form-control form-control-solid fs-8 h-80px resize-none">{{$personal_activity->notes}}</textarea>
                                                </td>

                                                @if($personal_assessment)
                                                    @if($personal_assessment->status>=3)
                                                        <td>
                                                            <span class="text-dark fw-bolder d-block fs-7 mt-1">{{$personal_activity->result}} %</span>
                                                        </td>
                                                    @endif
                                                @endif
                                                <td class="text-center">
                                                    @if($personal_assessment)
                                                        @if($personal_assessment->status<1)
                                                            <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                                                {!! getSvgIcon('media/icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                                            {!! getSvgIcon('media/icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </form>
                                    @endif
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
                <!--end::Card-->
            </div>
        </div>
        <div class="row g-5 g-xl-10 mb-5 d-none">
            <div class="col-12">
                <!--begin::Card-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header mt-6">
                        <!--begin::Card title-->
                        <div class="card-title flex-column">
                            <h3 class="fw-bolder mb-1">Penugasan & Kegiatan Tambahan</h3>
                            <div class="fs-6 text-gray-400">Semua kegiatan diluar jabatan dan fungsi</div>
                        </div>
                        <!--end::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_new_activity">Tambah</a>
                        </div>
                        <!--end::Card toolbar-->
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
                                    <th class="min-w-100px">SKP</th>
                                    <th class="min-w-80px">Capaian</th>
                                    <th class="min-w-150px">Dokumen</th>
                                    <th class="min-w-100px">Keterangan</th>
                                    @if($personal_assessment)
                                        @if($personal_assessment->status>=3)
                                            <th class="min-w-80px">Nilai</th>
                                        @endif
                                    @endif
                                    <th class="min-w-50px text-end rounded-end"></th>
                                </tr>
                                </thead>
                                <!--end::Table head-->
                                <!--begin::Table body-->
                                <tbody>
                                @foreach($secondary_activities as $personal_workload_activity)
                                    @php
                                        if($personal_assessment)
                                        {
                                            $personal_activity = App\Models\Transaction\PersonalActivity::where('personal_assessment_id',$personal_assessment->id)->where('activity_id',$personal_workload_activity->activity->id)->first();
                                        }
                                        else
                                        {
                                            $personal_activity = null;
                                        }
                                    @endphp
                                    @if(!$personal_activity)
                                        <form action="{{route('modules.performance.employee.create-activity')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="activity_id" value="{{$personal_workload_activity->activity->id}}">
                                            <input type="hidden" name="personal_id" value="{{$personal->id}}">
                                            <input type="hidden" name="month" value="{{$month}}">
                                            <input type="hidden" name="year" value="{{$year}}">
                                            <tr>
                                                <td>
                                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$personal_workload_activity->activity->title}}</a>
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">{{$personal_workload_activity->activity->activityType->name}}</span>
                                                </td>
                                                <td>
                                                    <input type="number" required name="target" class="form-control form-control-solid mb-3 fs-8 mb-lg-0 p-3" value="{{$personal_workload_activity->activity->default_result}}" placeholder="Capaian">
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">{{$personal_workload_activity->activity->unit_measure}}</span>
                                                </td>
                                                <td>
                                                    <input type="number" required name="achieve" class="form-control form-control-solid mb-3 fs-8 mb-lg-0 p-3" placeholder="Capaian">
                                                </td>
                                                <td>
                                                    <input type="file" name="document" accept=".pdf, .png, .jpg, .jpeg" class="form-control form-control-solid fs-9">
                                                </td>
                                                <td>
                                                    <textarea name="notes" class="form-control form-control-solid fs-8 h-80px resize-none"></textarea>
                                                </td>
                                                <td class="text-center">
                                                    @if($personal_assessment)
                                                        @if($personal_assessment->status<1)
                                                            <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                                                {!! getSvgIcon('media/icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                                            {!! getSvgIcon('media/icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </form>
                                    @else
                                        <form action="{{route('modules.performance.employee.update-activity')}}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="personal_activity_id" value="{{$personal_activity->id}}">
                                            <tr>
                                                <td>
                                                    <a href="#" class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$personal_workload_activity->activity->title}}</a>
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">{{$personal_workload_activity->activity->activityType->name}}</span>
                                                </td>
                                                <td>
                                                    <input type="number" required name="target" class="form-control form-control-solid mb-3 fs-8 mb-lg-0 p-3" value="{{$personal_activity->target}}" placeholder="Capaian">
                                                    <span class="text-muted fw-bold text-muted d-block fs-7">{{$personal_workload_activity->activity->unit_measure}}</span>
                                                </td>
                                                <td>
                                                    <input type="number" required name="achieve" class="form-control form-control-solid mb-3 fs-8 mb-lg-0 p-3" value="{{$personal_activity->achieve}}" placeholder="Capaian">
                                                </td>
                                                <td>
                                                    @if(!empty($personal_activity->attachment))
                                                        <a href="{{Illuminate\Support\Facades\Storage::disk('public')->url($personal_activity->attachment)}}" target="_blank" class="btn btn-outline btn-sm btn-outline-dashed btn-outline-primary btn-active-light-primary fs-9 p-2">
                                                            {!! getSvgIcon('media/icons/duotune/files/fil022.svg', 'svg-icon-2x svg-icon-primary') !!}
                                                            Dokumen
                                                        </a>
                                                    @else
                                                        <input type="file" name="document" accept=".pdf, .png, .jpg, .jpeg" class="form-control form-control-solid fs-9">
                                                    @endif

                                                </td>
                                                <td>
                                                    <textarea name="notes" class="form-control form-control-solid fs-8 h-80px resize-none">{{$personal_activity->notes}}</textarea>
                                                </td>

                                                @if($personal_assessment)
                                                    @if($personal_assessment->status>=3)
                                                        <td>
                                                            <span class="text-dark fw-bolder d-block fs-7 mt-1">{{$personal_activity->result}} %</span>
                                                        </td>
                                                    @endif
                                                @endif
                                                <td class="text-center">
                                                    @if($personal_assessment)
                                                        @if($personal_assessment->status<1)
                                                            <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                                                {!! getSvgIcon('media/icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-light-primary btn-sm p-2 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Simpan">
                                                            {!! getSvgIcon('media/icons/duotune/general/gen043.svg', 'svg-icon-2x svg-icon-primary m-0') !!}
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </form>
                                    @endif
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
                <!--end::Card-->
            </div>
        </div>
    </div>
    <!--end::Container-->
@endsection

@section('modal')
    <!--begin::Modal - New Target-->
    <div class="modal fade" id="modal_new_activity" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
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
                    <form id="modal_new_activity_form" class="form" action="#">
                        <!--begin::Heading-->
                        <div class="mb-13 text-center">
                            <!--begin::Title-->
                            <h1 class="mb-3">Tambah SKP</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="text-muted fw-bold fs-5">Kegiatan utama/tambahan</div>
                            <!--end::Description-->
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
                            <select name="country" data-control="select2" data-dropdown-parent="#modal_new_activity" data-placeholder="Pilih Kegiatan..." class="form-select form-select-solid">
                                <option value="">Pilih Kegiatan...</option>
                                @foreach($activities as $activity)
                                    <option value="{{$activity->id}}">{{$activity->type}} - {{$activity->title}}</option>
                                @endforeach
                            </select>
                            <!--end::Select-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row g-9 mb-8">
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Target</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control form-control-solid" placeholder="Jumlah Target" name="target_title"/>
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    Parameter Hasil
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control form-control-solid" readonly placeholder="Enter Target Title" name="target_title"/>
                                <!--end::Input-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Capaian</span>
                                </label>
                                <!--end::Label-->
                                <input type="text" class="form-control form-control-solid" placeholder="Jumlah Capaian" name="target_title"/>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-8 fv-row">
                            <!--begin::Label-->
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Dokumen</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i>
                            </label>
                            <!--end::Label-->
                            <!--begin::Dropzone-->
                            <div class="dropzone" id="activity_document">
                                <!--begin::Message-->
                                <div class="dz-message needsclick">
                                    <!--begin::Icon-->
                                    <i class="bi bi-file-earmark-arrow-up text-primary fs-3x"></i>
                                    <!--end::Icon-->
                                    <!--begin::Info-->
                                    <div class="ms-4">
                                        <h3 class="fs-5 fw-bolder text-gray-900 mb-1">Drop file disini atau klik untuk memilih file.</h3>
                                        <span class="fs-7 fw-bold text-gray-400">Tipe dokumen (jpg,png dan PDF)</span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                            </div>
                            <!--end::Dropzone-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-8">
                            <label class="fs-6 fw-bold mb-2">Keterangan</label>
                            <textarea class="form-control form-control-solid" rows="3" name="target_details" placeholder="Keterangan tambahan"></textarea>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="text-center">
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
@endsection

@push('scripts')

    <script src="{{asset('js/customs/apps/projects/project/project.js')}}"></script>
    @if(Session::has('scroll-element'))
        <script type="text/javascript">
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#{{Session::get('scroll-element')}}").offset().top-150
            }, 1000);
        </script>

    @endif
@endsection

