@extends('template.master')

@section('css')
    <link href="{{ asset('plugins/custom/vis-timeline/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('toolbar-title')
    <!--begin::Page title-->
    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Evaluasi Kinerja
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">{{ $assessmentPeriod->name }}</small>
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
            <a href="#" class="btn btn-bg-light btn-sm btn-icon-primary btn-text-primary my-1">
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
        @include('modules.performance.workplan.widgets._detail-header')
        <!--begin::Tasks-->
        <div class="card card-flush mb-3">
            <!--begin::Card header-->
            <div class="card-header justify-content-center">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
                        @foreach ($assessment_periods as $assessment_period)
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-5 @if($assessment_period->id == $assessmentPeriod->id) active @endif" aria-current="page"
                                    href="{{ route('modules.performance.workplan.eviden', [$assessment_period->year, $assessment_period->id] ) }}">
                                    {{ $assessment_period->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!--end::Card title-->
            </div>
            <!--end::Card header-->
            <!--begin::Body-->
            <div class="card-body py-2">
            <form action="{{ route('modules.performance.workplan.save-work-result-activities') }}" method="post">
                @csrf
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-top gx-3 gy-5 table-rounded" style="zoom:80%;">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="fw-bolder align-middle text-center text-light bg-primary">
                                <th class="ps-4 align-middle text-center">NO</th>
                                <th class="min-w-150px">RHK PIMPINAN YANG DIINTERVENSI</th>
                                <th class="min-w-150px">RENCANA HASIL KERJA</th>
                                <th class="min-w-100px">ASPEK</th>
                                <th class="min-w-150px">INDIKATOR KINERJA INDIVIDU</th>
                                <th class="min-w-175px">RENCANA AKSI</th>
                                <th class="min-w-150px">TARGET</th>
                                <th class="min-w-150px">REALISASI</th>
                                <th class="min-w-150px">CAPAIAN</th>
                                <th class="min-w-250px">CATATAN MONEV</th>
                                <th class="min-w-250px">TINDAK LANJUT</th>
                                <th class="min-w-250px">BUKTI DUKUNG</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($work_result_plan_mains as $work_result_plan_main)
                                <tr>
                                    @php
                                        $countActivities = $work_result_plan_main->workPlainActivities()->where('assessment_period_id', $assessmentPeriod->id)->count() + 2;
                                    @endphp
                                    <td rowspan="{{ $countActivities }}">{{ $loop->index + 1 }}.</td>
                                    <td @if ($work_result_plan_main->workIndicators()->count() > 0) rowspan="{{ $countActivities }}" @endif>
                                        <span class="text-gray-800 fw-bolder fs-6">{{ !empty($work_result_plan_main->workPlanParent) ? $work_result_plan_main->workPlanParent->title : '-' }}</span>
                                    </td>
                                    <td rowspan="{{ $countActivities }}">
                                        <span class="fw-bold">{{ $work_result_plan_main->title }}</span>
                                    </td>
                                </tr>
                                @foreach ($work_result_plan_main->workIndicators as $work_result_indicator)
                                    <tr>
                                        <td class="text-center" rowspan="{{ $countActivities }}">
                                            <span class="fw-bold">{{ $work_result_indicator->measurement }}</span>
                                        </td>
                                        <td rowspan="{{ $countActivities }}">
                                            <span class="text-gray-600 fw-bold fs-6">{{ $work_result_indicator->title }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach ($work_result_plan_main->workPlainActivities->where('assessment_period_id', $assessmentPeriod->id) as $work_plan_activities)
                                    <tr>
                                        <td>
                                            <div class="row min-w-175px mb-2">
                                                <div class="d-flex w-25px">{{ $loop->index + 1 }}.</div>
                                                <div class='d-flex w-150px'>
                                                    <span class="text-gray-600 fw-bold fs-6">
                                                    {{ $work_plan_activities->activity }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center align-top">
                                            <span class="text-gray-800 fw-bold fs-5">{{ $work_plan_activities->target }}</span>
                                        </td>
                                        @forelse ($work_plan_activities->workResultActivities as $work_result_activities)
                                            <input type="hidden" name="work_result_activity_id[]" value="{{ $work_result_activities->id }}" />
                                            <input type="hidden" name="work_plan_activity_id[]" value="{{ $work_plan_activities->id }}" />
                                            <td>
                                                <input type="text" name="realized[]"  value="{{ $work_result_activities->realized }}" class="form-control" />
                                            </td>
                                            <td>
                                                <input type="text" name="achieve[]"  value="{{ $work_result_activities->achieve }}" class="form-control" />
                                            </td>
                                            <td>
                                                <textarea type="text" style="resize: none" name="result_notes[]"  rows="3" class="form-control">{{ $work_result_activities->result_notes }}</textarea>
                                            </td>
                                            <td>
                                                <textarea type="text" style="resize: none" name="followup_notes[]" rows="3" class="form-control">{{ $work_result_activities->followup_notes }}</textarea>
                                            </td>
                                            <td>
                                                @if($work_result_activities->workResultActivityFiles()->count() > 0)
                                                    @php
                                                        $work_result_files  = $work_result_activities->workResultActivityFiles()->first();
                                                        $wrf_id = $work_result_files->id;
                                                        $link  = $work_result_files->link;
                                                        $title = $work_result_files->title;
                                                    @endphp
                                                        <input type="hidden" name="work_result_files_id[]"  value="{{ $wrf_id }}"/>
                                                    <div class="d-flex flex-column mb-5 fv-row">
                                                        <input type="text" name="title[]"  value="{{ $title }}" class="form-control" />
                                                    </div>
                                                    <div class="d-flex flex-column mb-5 fv-row">
                                                        <textarea type="text" name="link[]" style="resize: none" rows="3" class="form-control" >{{ $link }}</textarea>
                                                    </div>
                                                    @if(!empty($link))
                                                    <div class="min-w-200px text-center">
                                                        <a href="{{ $link }}" target="_blank" class="btn btn-sm btn-info mb-2 w-200px">
                                                            <i class="bi bi-eye-fill"></i> lihat bukti dukung
                                                        </a>
                                                    </div>
                                                    @endif
                                                @else
                                                    <input type="hidden" name="work_result_files_id[]"  value=""/>
                                                    <div class="d-flex flex-column mb-5 fv-row">
                                                        <input type="text" name="title[]"  value="" class="form-control" placeholder="nama bukti dukung" />
                                                    </div>
                                                    <div class="d-flex flex-column mb-5 fv-row">
                                                        <textarea style="resize: none" type="text" name="link[]" rows="3" class="form-control" placeholder="contoh : https://drive.google.com/" ></textarea>
                                                    </div>
                                                @endif

                                            </td>
                                        @empty
                                            <input type="hidden" name="work_result_activity_id[]" value="" />
                                            <input type="hidden" name="work_plan_activity_id[]" value="{{ $work_plan_activities->id }}" />
                                            <td>
                                                <input type="text" name="realized[]"  value="" class="form-control" />
                                            </td>
                                            <td>
                                                <input type="text" name="achieve[]"  value="" class="form-control" />
                                            </td>
                                            <td>
                                                <textarea style="resize: none" type="text" name="result_notes[]"  rows="3" class="form-control"></textarea>
                                            </td>
                                            <td>
                                                <textarea style="resize: none" type="text" name="followup_notes[]" rows="3" class="form-control"></textarea>
                                            </td>
                                            <td class="align-middle" >
                                                <input type="hidden" name="work_result_files_id[]"  value=""/>
                                                <div class="d-flex flex-column mb-5 fv-row">
                                                    <input type="text" name="title[]"  value="" class="form-control" placeholder="nama bukti dukung" />
                                                </div>
                                                <div class="d-flex flex-column mb-5 fv-row">
                                                    <textarea type="text" name="link[]"  style="resize: none" rows="3" class="form-control" placeholder="contoh : https://drive.google.com/" ></textarea>
                                                </div>
                                            </td>
                                        @endforelse
                                    </tr>
                                @endforeach
                                <tfoot></tfoot>
                            @endforeach
                            <tr>
                                <td colspan="5">
                                    <div class="fs-6 text-gray-400">B.TAMBAHAN</div>
                                </td>
                            </tr>
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Table container-->
                <div class="card-footer">
                    <div class="d-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Simpan</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
            </div>
            <!--begin::Body-->
        </div>
        <!--end::Tasks-->
    </div>
    <!--end::Container-->
@endsection


