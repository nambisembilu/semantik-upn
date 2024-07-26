@extends('template.master')

@section('css')
    <link href="{{ asset('plugins/custom/vis-timeline/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css" />
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
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Body-->
            <div class="card-body py-2">
                @livewire('wrp-component', ['wpr_id' => $id])
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
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-top gx-3 gy-5 table-rounded table-striped">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="fw-bolder text-light bg-primary">
                                <th class="ps-4 text-center rounded-start">NO</th>
                                <th class="min-w-250px text-center">SIKAP/PERILAKU</th>
                                <th class="min-w-250px  text-center rounded-end">PENILAIAN</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($behavior_assessments as $behavior_assessment)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <b class="text-gray-800 fs-5">{{ $behavior_assessment->name }}</b>
                                        <div class="d-flex flex-column">
                                            @foreach ($behavior_assessment->criterias as $criteria)
                                                <li class="d-flex align-items-center py-2">
                                                    <span class="bullet me-5"></span> {{ $criteria->title }}
                                                </li>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-10">
                                            <label class="required form-label">Ekspektasi Khusus Pimpinan:</label>
                                        </div>
                                    </td>
                                </tr>
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
        <!--end::Tasks-->
    </div>
    <!--end::Container-->
@endsection

@section('modal')
    <!--begin::Modal -->
    <div wire:ignore.self class="modal fade" id="modal_new_indicator" tabindex="-1" aria-hidden="true">
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
                    <form action="{{ route('modules.performance.result.save-indicator') }}" method="post">
                        @csrf
                        <!--begin::Heading-->
                        <div class="mb-13 text-center">
                            <!--begin::Title-->
                            <h1 class="mb-3">Buat Indikator RHK</h1>
                            <!--end::Title-->
                        </div>
                        <!--end::Heading-->
                        <div class="d-flex flex-stack mb-5 ">
                            <!--begin::Label-->
                            <div class="me-5">
                                <label class=" fs-5 fw-bold">Aspek</label>
                            </div>
                            <!--end::Label-->
                            <!--begin::Checkboxes-->
                            <div class="d-flex">
                                <!--begin::Checkbox-->
                                <label class="form-check form-check-custom form-check-solid m-3">
                                    <!--begin::Input-->
                                    <input class="form-check-input h-20px w-20px" type="radio" value="Kuantitas" checked="checked" name="measurement">
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <span class="form-check-label fw-bold">Kuantitas</span>
                                    <!--end::Label-->
                                </label>
                                <!--end::Checkbox-->
                                <!--begin::Checkbox-->
                                <label class="form-check form-check-custom form-check-solid m-3">
                                    <!--begin::Input-->
                                    <input class="form-check-input h-20px w-20px" type="radio" value="Kualitas" name="measurement">
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <span class="form-check-label fw-bold">Kualitas</span>
                                    <!--end::Label-->
                                </label>
                                <!--end::Checkbox-->
                                <!--begin::Checkbox-->
                                <label class="form-check form-check-custom form-check-solid m-3">
                                    <!--begin::Input-->
                                    <input class="form-check-input h-20px w-20px" type="radio" value="Waktu" name="measurement">
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <span class="form-check-label fw-bold">Waktu</span>
                                    <!--end::Label-->
                                </label>
                                <!--end::Checkbox-->
                                <!--begin::Checkbox-->
                                <label class="form-check form-check-custom form-check-solid m-3">
                                    <!--begin::Input-->
                                    <input class="form-check-input h-20px w-20px" type="radio" value="Biaya" name="measurement">
                                    <!--end::Input-->
                                    <!--begin::Label-->
                                    <span class="form-check-label fw-bold">Biaya</span>
                                    <!--end::Label-->
                                </label>
                                <!--end::Checkbox-->
                            </div>
                            <!--end::Checkboxes-->
                        </div>
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-5 fv-row">
                            <!--begin::Label-->
                            <div class="me-5">
                                <label class="required fs-5 fw-bold">Indikator</label>
                            </div>
                            <!--end::Label-->
                            <textarea required name="title" class="form-control form-control-solid resize-none"></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-5 fv-row">
                            <!--begin::Label-->
                            <div class="me-5">
                                <label class="required fs-5 fw-bold">Target</label>
                            </div>
                            <!--end::Label-->
                            <input type="text" required name="target" class="form-control form-control-solid" />
                        </div>
                        <!--end::Input group-->
                        <!--begin::Actions-->
                        <div class="text-center">
                            <input type="hidden" name="work_result_plan_id" id="work_result_plan_id" value="" />
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
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
    <!--end::Modal-->
@endsection

@push('scripts')
    <script src="{{ asset('js/customs/apps/projects/project/project.js') }}"></script>
    @stack('script')

@endsection
