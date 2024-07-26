@extends('template.master')

@section('css')
    <link href="{{ asset('plugins/custom/vis-timeline/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/custom/typeahead/typeahead.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('toolbar-title')
    <!--begin::Page title-->
    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
        class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Rencana Aksi
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Pengisian Rencana Aksi</small>
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
            <a href="{{ route('modules.performance.workplan.assessment-period', $assessmentPeriod->year ) }}" class="btn btn-bg-light btn-sm btn-icon-primary btn-text-primary my-1">
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
            <div class="card-header mt-3">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                    <h3 class="fw-bolder mb-1">Rencana Aksi Tahun {{ $assessmentPeriod->year }}</h3>
                </div>
                <!--end::Card title-->
                <div class="p-1">
                    <div class="btn-group mt-5" role="group" aria-label="Basic example">
                        <a href="{{ route('modules.performance.workplan.export-action-plan', $assessmentPeriod->year ) }}" class="btn btn-sm btn-success">
                        <span class="bi bi-download"></span>
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Body-->
            <div class="card-body py-2">
                <!--begin::Table container-->
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table align-top gx-3 gy-5 table-rounded" style="zoom:80%;">
                        <!--begin::Table head-->
                        <thead>
                            <tr class="fw-bolder text-light bg-primary">
                                <th rowspan="2" class="ps-4 align-middle text-center">NO</th>
                                <th rowspan="2" class="min-w-150px align-middle text-center">RENCANA HASIL KERJA</th>
                                <th rowspan="2" class="min-w-150px align-middle text-center">INDIKATOR KINERJA INDIVIDU</th>
                                <th colspan="3" class="text-center">TRIBULAN 1</th>
                                <th colspan="3" class="text-center">TRIBULAN 2</th>
                                <th colspan="3" class="text-center">TRIBULAN 3</th>
                                <th colspan="3" class="text-center">TRIBULAN 4</th>
                            </tr>
                            <tr class="fw-bolder text-light bg-primary">
                                <th class="min-w-175px text-center">RENCANA AKSI</th>
                                <th class="min-w-150px text-center">TARGET</th>
                                <th class="min-w-100px text-center">#</th>
                                <th class="min-w-175px text-center">RENCANA AKSI</th>
                                <th class="min-w-150px text-center">TARGET</th>
                                <th class="min-w-100px text-center">#</th>
                                <th class="min-w-175px text-center">RENCANA AKSI</th>
                                <th class="min-w-150px text-center">TARGET</th>
                                <th class="min-w-100px text-center">#</th>
                                <th class="min-w-175px text-center">RENCANA AKSI</th>
                                <th class="min-w-150px text-center">TARGET</th>
                                <th class="min-w-100px text-center">#</th>
                            </tr>
                        </thead>
                        <!--end::Table head-->
                        <!--begin::Table body-->
                        <tbody>
                            @foreach ($work_result_plan_mains as $work_result_plan_main)
                                <tr>
                                    <td>{{ $loop->index + 1 }}.</td>
                                    <td>
                                        <span class="fw-bold">{{ $work_result_plan_main->title }}</span>
                                    </td>
                                @if ($work_result_plan_main->workIndicators()->count() > 0)
                                    @foreach ($work_result_plan_main->workIndicators as $work_result_indicator)
                                    <td>
                                        <span class="text-gray-600 fw-bold fs-6">{{ $work_result_indicator->title }}</span>
                                    </td>
                                    @endforeach
                                    @foreach($assessment_periods as $assessment_period)
                                        <td colspan='3'>
                                        @forelse ($work_result_plan_main->workPlainActivities->where('assessment_period_id', $assessment_period->id) as $work_plan_activities)
                                            <div class="row min-w-350px mb-2">
                                                <div class="d-flex w-25px">{{ $loop->index + 1 }}.</div>
                                                <div class='d-flex w-150px'>
                                                    <span class="text-gray-600 fw-bold fs-6">
                                                    {{ $work_plan_activities->activity }}
                                                    </span>
                                                </div>
                                                <div class='align-self-center d-flex justify-content-center w-150px'>
                                                    <span class="text-gray-800 fw-bold fs-5">
                                                        {{ $work_plan_activities->target }}
                                                    </span>
                                                </div>
                                                <div class='align-self-center d-flex justify-content-center w-100px'>
                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <button class="btn btn-sm btn-warning mb-2"
                                                            data-activity="{{ $work_plan_activities->activity }}"
                                                            data-target="{{ $work_plan_activities->target }}"
                                                            data-work_plan_activity_id="{{ $work_plan_activities->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal_edit_renaksi">
                                                            <span class="ph-pencil"></span>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger mb-2"
                                                            onclick="document.getElementById('work_plan_activities_id').value={{ $work_plan_activities->id }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal_delete_renaksi">
                                                            <span class="ph-x"></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                                <p class="text-danger text-center mb-2">Rencana Aksi belum diisi</p>
                                        @endforelse
                                            <div class='d-flex justify-content-center min-w-300  mt-5'>
                                                <button class="btn btn-sm btn-success mb-2" data-work_result_plan_id="{{ $work_result_plan_main->id }}" data-assessment_period_id="{{ $assessment_period->id }}" data-bs-toggle="modal" data-bs-target="#modal_new_renaksi">
                                                    <span class="bi bi-plus-circle"></span> Tambah Rencana Aksi
                                                </button>
                                            </div>
                                        </td>
                                    @endforeach
                                @else
                                        <td colspan="2" class="align-middle text-center">
                                            <p class="text-danger mb-2">Indikator Kinerja Individu belum diisi</p>
                                        </td>
                                @endif

                            @endforeach
                            </tr>
                            <tfoot></tfoot>
                            <tr>
                                <td colspan="6">
                                    <div class="fs-6 text-gray-400">B.TAMBAHAN</div>
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
        <!--end::Tasks-->
    </div>
    <!--end::Container-->
@endsection

@section('modal')
    <div class="modal fade" id="modal_new_renaksi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        {!! getSvgIcon('media/icons/duotune/arrows/arr061.svg', 'svg-icon-1 svg-icon-primary m-0') !!}
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form action="{{ route('modules.performance.workplan.save') }}" method="post">
                        @csrf
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Buat Rencana Aksi</h1>
                        </div>
                        <div class="d-flex flex-column mb-5 fv-row">
                            <div class="me-5">
                                <label class="required fs-5 fw-bold">Rencana Aksi</label>
                            </div>
                            <textarea required name="activity" style="resize: none;overflow: hidden" class="form-control typeahead-activity" rows="5"></textarea>
                        </div>
                        <div class="d-flex flex-column mb-5 fv-row">
                            <div class="me-5">
                                <label class="required fs-5 fw-bold">Target</label>
                            </div>
                            <input type="text" required name="target" class="form-control form-control-solid" />
                        </div>
                        <div class="text-center">
                            <input type="hidden" name="work_result_plan_id" id="work_result_plan_id" value="" />
                            <input type="hidden" name="assessment_period_id" id="assessment_period_id" value="" />
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_edit_renaksi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        {!! getSvgIcon('media/icons/duotune/arrows/arr061.svg', 'svg-icon-1 svg-icon-primary m-0') !!}
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form action="{{ route('modules.performance.workplan.update-plan-activity') }}" method="post">
                        @csrf
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Update Rencana Aksi</h1>
                        </div>
                        <div class="d-flex flex-column mb-5 fv-row">
                            <div class="me-5">
                                <label class="required fs-5 fw-bold">Rencana Aksi</label>
                            </div>
                            <textarea required id="activity" name="activity" style="resize: none;overflow: hidden" class="form-control typeahead-activity" value=""  rows="5"></textarea>
                        </div>
                        <div class="d-flex flex-column mb-5 fv-row">
                            <div class="me-5">
                                <label class="required fs-5 fw-bold">Target</label>
                            </div>
                            <input type="text" required id="target" name="target" class="form-control form-control-solid" value="" />
                        </div>
                        <div class="text-center">
                            <input type="hidden" name="work_plan_activity_id" id="work_plan_activity_id" value="" />
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal_delete_renaksi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header pb-0 border-0 justify-content-end">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        {!! getSvgIcon('media/icons/duotune/arrows/arr061.svg', 'svg-icon-1 svg-icon-primary m-0') !!}
                    </div>
                </div>
                <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    <form action="{{ route('modules.performance.workplan.delete-plan-activity') }}" method="post">
                        @csrf
                        <div class="mb-13 text-center">
                            <h1 class="mb-3">Hapus Rencana Aksi</h1>
                        </div>
                        <div class="d-flex flex-column mb-5 fv-row">
                            <p>Apakah anda yakin ingin menghapus data ini?</p>
                        </div>
                        <div class="d-flex justify-content-end">
                            <input type="hidden" name="work_plan_activities_id" id="work_plan_activities_id" value="" />
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Batal</button>
                            <button type="submit" class="btn btn-danger">
                                <span class="indicator-label">Hapus</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/customs/apps/projects/project/project.js') }}"></script>
    <script src="{{ asset('plugins/custom/typeahead/typeahead.bundle.min.js') }}"></script>
    <script>
    $(function () {
        $('#modal_new_renaksi').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var result = button.data('work_result_plan_id');
            var period = button.data('assessment_period_id');
            var modal = $(this);
            modal.find('#work_result_plan_id').val(result);
            modal.find('#assessment_period_id').val(period);
        });
    });
    $(function () {
        $('#modal_edit_renaksi').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var activity = button.data('activity');
            var target = button.data('target');
            var activity_id = button.data('work_plan_activity_id');
            var modal = $(this);

            modal.find('#activity').val(activity);
            modal.find('#target').val(target);
            modal.find('#work_plan_activity_id').val(activity_id);
        });
    });
    </script>
    @stack('script')
    <script>
        var substringMatcher = function (strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function (i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                });

                cb(matches);
            };
        };
        var activities = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: '{{route('modules.performance.workplan.ajax-list-activities')}}',
            remote: {
                url: '{{route('modules.performance.workplan.ajax-list-activities').'?query=%QUERY'}}',
                wildcard: '%QUERY'
            }
        });

        $('.typeahead-activity').typeahead(null, {
            display: 'value',
            source: activities
        });
    </script>
@endsection
