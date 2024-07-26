<div>
    @if (\Session::has('alert-class'))
        <!--begin::Alert-->
        <div class="alert alert-dismissible alert-{{ \Session::get('alert-class') }} d-flex flex-column flex-sm-row p-5 mb-10">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column">
                <!--begin::Title-->
                <h4 class="mb-1 text-{{ \Session::get('alert-class') }}">Info</h4>
                <!--end::Title-->
                <!--begin::Content-->
                <span>{{ \Session::get('message') }}</span>
                <!--end::Content-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Close-->
            <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                <span class="svg-icon svg-icon-2x svg-icon-{{ \Session::get('alert-class') }}">{!! getSvgIcon('media/icons/duotune/abstract/abs012.svg', 'svg-icon svg-icon-5 m-0') !!}</span>
            </button>
            <!--end::Close-->
        </div>
        <!--end::Alert-->
    @endif
    <!--begin::Table container-->
    <div class="table-responsive">
        <div class="d-flex justify-content-center">

            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
                @foreach ($periods as $period)
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 @if($period->id == $assessment_period->id) active @endif" aria-current="page"
                           href="{{route('modules.performance.result.detail',['year'=>session('current_year')]).'?period='.$period->id}}">
                            {{ $period->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="separator border-3 my-10"></div>
        <!--begin::Table-->
        <table class="table align-top gx-3 gy-5 fs-7 table-rounded table-striped">
            <!--begin::Table head-->
            <thead>
            <tr class="fw-bolder text-light bg-primary">
                <th class="ps-4 text-center rounded-start" style="vertical-align: middle">NO <br/>(1)</th>
                <th class="min-w-250px text-center" style="vertical-align: middle">RHK PIMPINAN YANG DIINTERVENSI</th>
                <th class="min-w-100px text-center" style="vertical-align: middle">RENCANA HASIL KERJA<br/>(2)</th>
                <th class="min-w-80px text-center" style="vertical-align: middle">ASPEK</th>
                <th class="min-w-150px text-center" style="vertical-align: middle">INDIKATOR KINERJA INDIVIDU<br/>(3)</th>
                <th class="min-w-50px  text-center " style="vertical-align: middle">TARGET<br/>(4)</th>
                <th class="min-w-50px  text-center " style="vertical-align: middle">REALISASI BERDASARKAN BUKTI DUKUNG<br/>(5)</th>
                <th class="min-w-50px  text-center rounded-end" style="vertical-align: middle">UMPAN BALIK BERKELANJUTAN BERDASARKAN BUKTI DUKUNG <br/>(6)</th>
            </tr>
            </thead>
            <!--end::Table head-->
            <!--begin::Table body-->
            <tbody>
            @forelse ($work_result_plan_mains as $work_result_plan_main)
                <tr>
                    <td @if ($work_result_plan_main->workIndicators()->count() > 0) rowspan="{{ $work_result_plan_main->workIndicators()->count() + 1 }}" @endif>{{ $loop->index + 1 }}</td>
                    <td @if ($work_result_plan_main->workIndicators()->count() > 0) rowspan="{{ $work_result_plan_main->workIndicators()->count() + 1 }}" @endif>
                        <span class="text-gray-800 fw-bolder ">{{ !empty($work_result_plan_main->workPlanParent) ? $work_result_plan_main->workPlanParent->title : '-' }}</span>
                    </td>
                    <td @if ($work_result_plan_main->workIndicators()->count() > 0) rowspan="{{ $work_result_plan_main->workIndicators()->count() + 1 }}" @endif>
                            <span href="#" class="text-gray-600 text-hover-primary fw-bold">
                                {{ $work_result_plan_main->title }}
                            </span>
                    </td>
                    @if ($work_result_plan_main->workIndicators()->count() == 0)
                        <td colspan="3" class="text-center">
                            <p class="text-danger mb-2">Indikator belum diisi</p>
                        </td>
                    @endif
                </tr>
                @foreach ($work_result_plan_main->workIndicators as $work_result_indicator)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $work_result_indicator->measurement }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600 fw-bold">
                                {{ $work_result_indicator->title }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-gray-800 fw-bold fs-5">
                                {{ $work_result_indicator->target }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-gray-800 fw-bold fs-5">
                                @php
                                    $work_indicator_period=$work_result_indicator->WorkIndicatorPeriod->where('assessment_period_id', $assessment_period->id)->first();
                                @endphp
                                @if($work_indicator_period)
                                    {{$work_indicator_period->realized}}
                                @else
                                    <span class="text-danger">Belum diisi</span>
                                @endif
                            </span>
                        </td>
                        <td class="text-left">
                            @if($work_indicator_period)
                                <label class="required form-label fs-7">Pimpinan</label>
                                <p class="text-muted">{{!empty($work_indicator_period->feedback)?$work_indicator_period->feedback:''}}</p>
                            @else
                                <span class="text-danger">-</span>
                            @endif

                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6">
                        <div class="d-flex justify-content-center mb-20">
                            <div class="d-flex flex-column align-items-center">
                                <span class="text-danger">Data masih kosong</span>
                            </div>
                        </div>
                    </td>
                </tr>

            @endforelse
            <tr>
                <td colspan="6">
                    <div class="fs-6 text-gray-400">B.TAMBAHAN</div>
                </td>
            </tr>

            @forelse ($work_result_plan_secondaries as $work_result_plan_secondary)
                <tr>
                    <td @if ($work_result_plan_secondary->workIndicators()->count() > 0) rowspan="{{ $work_result_plan_secondary->workIndicators()->count() + 1 }}" @endif>{{ $loop->index + 1 }}</td>
                    <td @if ($work_result_plan_secondary->workIndicators()->count() > 0) rowspan="{{ $work_result_plan_secondary->workIndicators()->count() + 1 }}" @endif>
                        <span class="text-gray-800 fw-bolder fs-6">{{ !empty($work_result_plan_secondary->workPlanParent) ? $work_result_plan_secondary->workPlanParent->title : '-' }}</span>
                    </td>
                    <td @if ($work_result_plan_secondary->workIndicators()->count() > 0) rowspan="{{ $work_result_plan_secondary->workIndicators()->count() + 1 }}" @endif>
                            <span class="text-gray-600 text-hover-primary fs-6 fw-bold">
                                {{ $work_result_plan_secondary->title }}
                            </span>
                    </td>
                    @if ($work_result_plan_secondary->workIndicators()->count() == 0)
                        <td colspan="3" class="text-center">
                            <p class="text-danger mb-2">Indikator belum diisi</p>
                        </td>
                    @endif
                </tr>
                @foreach ($work_result_plan_secondary->workIndicators as $work_result_indicator)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $work_result_indicator->measurement }}</span>
                        </td>
                        <td>
                            <span class="text-gray-600 fw-bold fs-6">
                                {{ $work_result_indicator->title }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-gray-800 fw-bold fs-5">
                                {{ $work_result_indicator->target }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="text-gray-800 fw-bold fs-5">
                                @php
                                    $work_indicator_period=$work_result_indicator->WorkIndicatorPeriod->where('assessment_period_id', $assessment_period->id)->first();
                                @endphp
                                @if($work_indicator_period)
                                    {{$work_indicator_period->realized}}
                                @else
                                    <span class="text-danger">Belum diisi</span>
                                @endif
                            </span>
                        </td>
                        <td class="text-center">
                            @if($work_indicator_period)
                                <label class="required form-label">Pimpinan</label>
                                <p class="text-muted">{{!empty($work_indicator_period->feedback)?$work_indicator_period->feedback:''}}</p>
                            @else
                                <span class="text-danger">-</span>
                            @endif

                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="8">
                        <div class="d-flex justify-content-center mb-20">
                            <div class="d-flex flex-column align-items-center">
                                <span class="text-danger">Data kosong</span>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforelse

            </tbody>
            <!--end::Table body-->
            <tfoot>
            <tr class="bg-opacity-75 bg-secondary">
                <td colspan="8" class="rounded">
                    <b>RATING HASIL KERJA*</b>
                    <div class="separator separator-dotted border-gray-600 my-5"></div>
                    @if(!empty($work_performance_period))
                        @if($work_performance_period->result_work==1)
                            <p class="text-warning fw-bolder fs-5"> {{$work_performance_period->result_work_text}} </p>
                        @elseif($work_performance_period->result_work==2)
                            <p class="text-primary fw-bolder fs-5"> {{$work_performance_period->result_work_text}} </p>
                        @else
                            <p class="text-success fw-bolder fs-5"> {{$work_performance_period->result_work_text}} </p>
                        @endif
                    @else
                        -
                    @endif
                </td>
            </tr>
            </tfoot>
        </table>
        <!--end::Table-->
    </div>
    <!--end::Table container-->
</div>
