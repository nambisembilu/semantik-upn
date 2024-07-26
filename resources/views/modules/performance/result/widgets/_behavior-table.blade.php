<!--begin::Table container-->
<div class="table-responsive">
    <!--begin::Table-->
    <table class="table align-top gx-3 gy-5 fs-7 table-rounded table-striped">
        <!--begin::Table head-->
        <thead>
        <tr class="fw-bolder text-light bg-primary">
            <th class="ps-4 text-center rounded-start">NO</th>
            <th class="min-w-250px text-center">SIKAP/PERILAKU</th>
            <th class="min-w-250px  text-center"></th>
            <th class="min-w-250px  text-center rounded-end">UMPAN BALIK BERKELANJUTAN BERDASARKAN BUKTI DUKUNG</th>
        </tr>
        </thead>
        <!--end::Table head-->
        <!--begin::Table body-->
        <tbody>
        @foreach ($behavior_categories as $behavior_category)
            @php
                $work_behavior_period=$behavior_category->workBehaviorPeriod->where('work_performance_result_id',$work_performance_result->id)
                 ->where('assessment_period_id',$assessment_period->id)
                 ->first();
            @endphp
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>
                    <b class="text-gray-800 fs-7">{{ $behavior_category->name }}</b>
                    <div class="d-flex flex-column">
                        @foreach ($behavior_category->behaviorCriterias as $criteria)
                            <li class="d-flex align-items-center py-2">
                                <span class="bullet me-5"></span> {{ $criteria->title }}
                            </li>
                        @endforeach
                    </div>
                </td>
                <td>
                    <label class="required form-label fs-7">Ekspektasi Khusus Pimpinan:</label>
                    <p class="text-muted">{{$work_behavior_period?$work_behavior_period->notes:''}}</p>
                </td>
                <td>
                    <label class="required form-label fs-7">Pimpinan</label>
                    <p class="text-muted">{{$work_behavior_period?$work_behavior_period->feedback:''}}</p>

                </td>
            </tr>
        @endforeach
        <tfoot>
        <tr class="bg-opacity-75 bg-secondary">
            <td colspan="8" class="rounded">
                <div class="d-flex flex-column">
                    <b>RATING PERILAKU KERJA*</b>
                    <div class="separator separator-dotted border-gray-600 my-5"></div>
                    @if(!empty($work_performance_period))
                        @if($work_performance_period->result_behavior==1)
                            <p class="text-danger fw-bolder fs-5"> {{$work_performance_period->result_behavior_text}} </p>
                        @elseif($work_performance_period->result_behavior==2)
                            <p class="text-primary fw-bolder fs-5"> {{$work_performance_period->result_behavior_text}} </p>
                        @else
                            <p class="text-success fw-bolder fs-5"> {{$work_performance_period->result_behavior_text}} </p>
                        @endif
                    @else
                        -
                    @endif
                    <b class="mt-5">PREDIKAT KINERJA PEGAWAI*</b>
                    <div class="separator separator-dotted border-gray-600 my-5"></div>
                    @if(!empty($work_performance_period))
                        @if($work_performance_period->final_result==1)
                            <p class="text-danger fw-bolder fs-5"> {{$work_performance_period->final_result_text}} </p>
                        @elseif($work_performance_period->final_result>=2&&$work_performance_period->final_result<4)
                            <p class="text-warning fw-bolder fs-5"> {{$work_performance_period->final_result_text}} </p>
                        @else
                            <p class="text-success fw-bolder fs-5"> {{$work_performance_period->final_result_text}} </p>
                        @endif
                    @else
                        -
                    @endif
                </div>
            </td>
        </tr>
        </tfoot>
        </tbody>
        <!--end::Table body-->
    </table>
    <!--end::Table-->
</div>
<!--end::Table container-->
