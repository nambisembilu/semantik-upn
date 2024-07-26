@extends('template.master')

@section('title', 'Home Apps')

@section('sidebar')
    @include('template.sidebar')
@endsection

<!--{{var_dump(json_encode($skpBehaviorRealizations))}}-->
@section('page-head')
    <div class="page-header">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Kinerja - <span class="fw-normal">Home</span>
                </h4>

                <a href="#page_header"
                    class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                    data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
    <div id="my_content">
    <!--{{ var_dump(json_encode($feedbackBehaviorCategories)) }}-->
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div style="float:left!important;">
                    <h4 class="m-0 font-weight-bold text-primary">Sasaran Kinerja Pegawai</h4>
                </div>
                
            </div>

            <div class="card-body pt-2">
                <form id="formEvaluation">
                    @csrf
                    <input type="hidden" name="id" value="{{$skpRealization->id}}"/>
                                            
                <!-- Header Data Pegawai -->
                <div class="table-responsive">
                    <!-- Data Pegawai -->
                    <table class="table table-bordered table-hover table-blue table-sm mb-4">
                        <tbody>
                            <tr class="table-primary text-primary mpdf-table-header-gray">
                                <td class="text-center">No</td>
                                <td class="text-center" colspan="2">Pegawai yang Dinilai</td>
                                <td class="text-center">No</td>
                                <td class="text-center" colspan="2">Pejabat Penilai Kinerja</td>
                            </tr>
                            <tr>
                                <td class="td-data-no text-center">1.</td>
                                <td class="td-data-attr">Nama</td>
                                <td class="td-data-val">{{ !empty($personalWorkUnit) ? $personalWorkUnit->personal->name : '-' }}</td>
                                <td class="td-data-no text-center">1.</td>
                                <td class="td-data-attr">Nama</td>
                                <td class="td-data-val">{{ !empty($officerWorkUnit) ? $officerWorkUnit->personal->name : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">2.</td>
                                <td>NIP</td>
                                <td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->personal->work_id_number : '-' }}</td>
                                <td class="text-center">2.</td>
                                <td>NIP</td>
                                <td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->personal->work_id_number : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">3.</td>
                                <td>Pangkat&nbsp;/&nbsp;Gol.</td>
                                <td>{{ !empty($personalWorkUnit) ? $helper->getGradeValue($personalWorkUnit->personal->employee_type, $personalWorkUnit->personal->workRank->grade_name, $personalWorkUnit->personal->workRank->name) : '-' }}</td>
                                <td class="text-center">3.</td>
                                <td>Pangkat&nbsp;/&nbsp;Gol.</td>
                                <td>{{ !empty($officerWorkUnit) ? $helper->getGradeValue($officerWorkUnit->personal->employee_type, $officerWorkUnit->personal->workRank->grade_name, $officerWorkUnit->personal->workRank->name) : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">4.</td>
                                <td>Jabatan</td>
                                <td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->workPosition->name : '-' }}</td>
                                <td class="text-center">4.</td>
                                <td>Jabatan</td>
                                <td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->workPosition->name : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">5.</td>
                                <td>Unit Kerja</td>
                                <td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->rootWorkUnit->name : '-' }}</td>
                                <td class="text-center">5.</td>
                                <td>Unit Kerja</td>
                                <td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->rootWorkUnit->name : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Hasil Kerja -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-blue table-sm mb-4">
                        <tbody>
                            <tr class="table-primary text-primary">
                                <td colspan="5">HASIL KERJA</td>
                            </tr>

                            <!-- Hasil Kerja Utama -->
                            <tr class="table-primary text-primary">
                                <td colspan="5">A. Utama</td>
                            </tr>
                            @if(!empty($mainSkp) && count($mainSkp) > 0)
                                    @foreach($mainSkp as $key => $skpPlanRealization)
                                    @if(empty($skpPlanRealization->realization))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif    
                                        <td class="text-center" style="width: 5%">
                                            {{$key + 1}}. </td>
                                        <td style="width: 40%">
                                            &nbsp;<i class="fas fa-user-circle" data-toggle="tooltip" data-placement="top"
                                                title=""></i>&nbsp;
                                            {{$skpPlanRealization->skpWorkPlan->title}} (Penugasan Dari : {{$skpPlanRealization->skpWorkPlan->get_task_from}})
                                        </td>
                                        <td rowspan="3" style="width: 20%;" class="p-2">
                                            Realisasi:
                                            <br>
                                            {{$skpPlanRealization->realization}}
                                            <br><br>
                                            Bukti Link Pendukung:
                                            <br>
                                            {{$skpPlanRealization->supporting_evidence}}
                                        </td>
                                        <td rowspan="3" style="width: 20%;" class="p-2">
                                            Umpan Balik:
                                            <br>
                                            <input type="hidden" name="main_plan_id[]" value="{{$skpPlanRealization->id}}"/>
                                            <div class="input-group">
                                                <select id="main_plan_work_category_id_{{$skpPlanRealization->id}}" name="main_plan_work_category_id[]" class="form-control work-plan">
                                                    @foreach($feedbackWorkCategories as $feedbackWorkCategory)
                                                    @if($feedbackWorkCategory->id == $skpPlanRealization->feedback_work_category_id)
                                                    <option value="{{$feedbackWorkCategory->id}}" selected>{{$feedbackWorkCategory->name}}</option>
                                                    @else
                                                    <option value="{{$feedbackWorkCategory->id}}">{{$feedbackWorkCategory->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <button onclick="OpenWorkTextTemplateModal('{{$skpPlanRealization->id}}',true)" class="btn btn-primary" type="button" title="Isi Dengan Template"><i class="ph ph-copy"></i></button>
                                            </div>
                                            
                                            <br>
                                            <textarea id="main_plan_value_{{$skpPlanRealization->id}}" name="main_plan_value[]" class="form-control" cols="40" rows="5" required>{{$skpPlanRealization->feedback}}</textarea>
                                        </td>
                                    </tr>
        
                                    <!-- Indikator Hasil Kerja Utama -->
                                    @if(empty($skpPlanRealization->realization))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td rowspan="3">&nbsp;</td>
                                        <td>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</td>
                                    </tr>
                                    @if(empty($skpPlanRealization->realization))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td>
                                            <ul class="mb-0" style="margin-left: -15px">
                                                @foreach($skpPlanRealization->skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
                                                <li>
                                                    {{$skpWorkIndicator->title}} 
                                                </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>
                                    @endforeach
                            @endif
                             <!-- Hasil Kerja Tambahan -->
                             <tr class="table-primary text-primary">
                                <td colspan="5">B. Tambahan</td>
                            </tr>
                            @if(!empty($additionalSkp) && count($additionalSkp) > 0)
                                @foreach($additionalSkp as $key => $skpPlanRealization)
                                    @if(empty($skpPlanRealization->realization))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif 
                                        <td class="text-center" style="width: 5%;">
                                            {{$key + 1}}. </td>
                                        <td style="width: 40%;">
                                            &nbsp;<i class="fas fa-user-circle" data-toggle="tooltip" data-placement="top"
                                                title=""></i>&nbsp;
                                            {{$skpPlanRealization->skpWorkPlan->title}} (Penugasan Dari : {{$skpPlanRealization->skpWorkPlan->get_task_from}})
                                        </td>
                                        <td rowspan="3" style="width: 20%;" class="p-2">
                                            Realisasi:
                                            <br>
                                            {{$skpPlanRealization->realization}}
                                            <br><br>
                                            Bukti Link Pendukung:
                                            <br>
                                            {{$skpPlanRealization->supporting_evidence}}
                                        </td>
                                        <td rowspan="3" style="width: 20%;" class="p-2">
                                            Umpan Balik:
                                            <br>
                                            <input type="hidden" name="additional_plan_id[]" value="{{$skpPlanRealization->id}}"/>
                                            <div class="input-group">
                                                <select id="additional_plan_work_category_id_{{$skpPlanRealization->id}}" name="additional_plan_work_category_id[]" class="form-control work-plan">
                                                    @foreach($feedbackWorkCategories as $feedbackWorkCategory)
                                                    @if($feedbackWorkCategory->id == $skpPlanRealization->feedback_work_category_id)
                                                    <option value="{{$feedbackWorkCategory->id}}" selected>{{$feedbackWorkCategory->name}}</option>
                                                    @else
                                                    <option value="{{$feedbackWorkCategory->id}}">{{$feedbackWorkCategory->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <button onclick="OpenWorkTextTemplateModal('{{$skpPlanRealization->id}}',false)" class="btn btn-primary" type="button" title="Isi Dengan Template"><i class="ph ph-copy"></i></button>
                                            </div>
                                            <br>
                                            <textarea id="additional_plan_value_{{$skpPlanRealization->id}}" name="additional_plan_value[]" class="form-control" cols="40" rows="5" required>{{$skpPlanRealization->feedback}}</textarea>
                                        </td>
                                    </tr>
        
                                    <!-- Indikator Hasil Kerja Utama -->
                                    @if(empty($skpPlanRealization->realization))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td rowspan="3">&nbsp;</td>
                                        <td>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</td>
                                    </tr>
                                    @if(empty($skpPlanRealization->realization))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td>
                                            <ul class="mb-0" style="margin-left: -15px">
                                                @foreach($skpPlanRealization->skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
                                                <li>
                                                    {{$skpWorkIndicator->title}} 
                                                </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td colspan="4">&nbsp;</td>
                                    </tr>
                                    @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Perilaku Kerja -->
                <div class="table-responsive">
                        <table class="table table-bordered table-hover table-blue table-sm mb-4">
                            <tbody>
                                <tr class="table-primary text-primary">
                                    <td colspan="4">PERILAKU KERJA</td>
                                </tr>
                                @if(!empty($skpBehaviorRealizations) && count($skpBehaviorRealizations) > 0)
                                    @foreach($skpBehaviorRealizations as $key => $skpBehaviorRealization)
                                    <tr>
                                        <td style="width: 5%" class="text-center align-top">
                                        {{$key + 1}}.
                                        </td>
                                        <td style="width: 45%" class="align-top">
                                            {{$skpBehaviorRealization->skpBehavior->behaviorCategory->name}} <br>
                                            <ul class="mb-2 pl-4">
                                                @foreach($skpBehaviorRealization->skpBehavior->behaviorCategory->behaviorCriterias as $key => $behaviorCriteria)
                                                <li>{{$behaviorCriteria->name}}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td style="min-width: 20%; max-width: 20%;">
                                            Ekspektasi Khusus Pimpinan: {{$skpBehaviorRealization->skpBehavior->notes}}
                                            <br>
                                        </td>
                                        <td style="min-width: 20%; max-width: 20%;">
                                            Umpan Balik
                                            <br>
                                            <input type="hidden" name="behavior_plan_id[]" value="{{$skpBehaviorRealization->id}}"/>
                                            <div class="input-group">
                                                <select id="behavior_plan_category_id_{{$skpBehaviorRealization->id}}" name="behavior_plan_category_id[]" class="form-control behavior-plan">
                                                    @foreach($feedbackBehaviorCategories as $feedbackBehaviorCategory)
                                                    @if($feedbackBehaviorCategory->id == $skpBehaviorRealization->feedback_behavior_category_id)
                                                    <option value="{{$feedbackBehaviorCategory->id}}" selected>{{$feedbackBehaviorCategory->name}}</option>
                                                    @else
                                                    <option value="{{$feedbackBehaviorCategory->id}}">{{$feedbackBehaviorCategory->name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <button onclick="OpenBehaviorTextTemplateModal('{{$skpBehaviorRealization->id}}')" class="btn btn-primary" type="button" title="Isi Dengan Template"><i class="ph ph-copy"></i></button>
                                            </div>
                                            <br>
                                            <textarea id="behavior_plan_value_{{$skpBehaviorRealization->id}}" name="behavior_plan_value[]" class="form-control" cols="40" rows="5" required>{{$skpBehaviorRealization->feedback}}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Evaluasi Kerja -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-blue table-sm mb-4">
                        <tbody>
                            <tr class="table-primary text-primary">
                                <td colspan="2">EVALUASI HASIL KERJA</td>
                            </tr>
                            <tr>
                                <td style="width: 50%" class="align-top">
                                    Rekomendasi
                                </td>
                                <td style="min-width: 50%; max-width: 50%;">
                                    <input style="background-color: #e8e8e8;" class="form-control" type="text" readonly id="recommendation_plan"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%" class="align-top">
                                    Rating Hasil Kerja
                                </td>
                                <td style="min-width: 50%; max-width: 50%;">
                                    <!--
                                    <select name="work_category_id" class="form-control" readonly style="background-color: #e8e8e8;">
                                        @foreach($feedbackWorkCategories as $feedbackWorkCategory)
                                        @if($feedbackWorkCategory->id == $skpRealization->feedback_work_category_id)
                                        <option value="{{$feedbackWorkCategory->id}}" selected>{{$feedbackWorkCategory->name}}</option>
                                        @else
                                        <option value="{{$feedbackWorkCategory->id}}">{{$feedbackWorkCategory->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    -->
                                    <!-- temporary -->
                                    <input style="background-color: #e8e8e8;" class="form-control" type="text" readonly id="recommendation_plan_2"/>
                                    <input type="hidden" name="work_category_id" value="{{$skpRealization->feedback_work_category_id}}" />

                                    <br>
                                    <textarea name="feedback_work_summary" class="form-control" cols="40" rows="5">{{$skpRealization->feedback_work_summary}}</textarea>
                                </td>
                            </tr>
                            <tr class="table-primary text-primary">
                                <td colspan="2">EVALUASI PERILAKU</td>
                            </tr>
                            <tr>
                                <td style="width: 50%" class="align-top">
                                    Rekomendasi
                                </td>
                                <td style="min-width: 50%; max-width: 50%;">
                                    <input class="form-control" style="background-color: #e8e8e8;" type="text" readonly id="recommendation_behavior" />
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%" class="align-top">
                                    Rating Hasil Kerja
                                </td>
                                <td style="min-width: 50%; max-width: 50%;">
                                    <!--
                                    <select name="behavior_category_id" class="form-control" readonly style="background-color: #e8e8e8;">
                                        @foreach($feedbackBehaviorCategories as $feedbackBehaviorCategory)
                                        @if($feedbackBehaviorCategory->id == $skpRealization->feedback_behavior_category_id)
                                        <option value="{{$feedbackBehaviorCategory->id}}" selected>{{$feedbackBehaviorCategory->name}}</option>
                                        @else
                                        <option value="{{$feedbackBehaviorCategory->id}}">{{$feedbackBehaviorCategory->name}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    -->
                                    <!-- temporary -->
                                    <input style="background-color: #e8e8e8;" class="form-control" type="text" readonly id="recommendation_behavior_2"/>
                                    <input type="hidden" name="behavior_category_id" value="{{$skpRealization->feedback_behavior_category_id}}"/>
                                    <textarea name="feedback_behavior_summary" class="form-control" cols="40" rows="5">{{$skpRealization->feedback_behavior_summary}}</textarea>
                                </td>
                            </tr>
                            <tr class="table-primary text-primary">
                                <td colspan="2">PREDIKAT KINERJA PEGAWAI</td>
                            </tr>
                            <tr>
                                <td style="width: 50%" class="align-top">
                                    Rekomendasi
                                </td>
                                <td style="min-width: 50%; max-width: 50%;">
                                    <input style="background-color: #e8e8e8;" class="form-control" type="text" readonly id="recommendation_predicate"/>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%" class="align-top">
                                    Rating Hasil Kerja
                                </td>
                                <td style="min-width: 50%; max-width: 50%;">
                                    <!--
                                    <select name="predicate_work" class="form-control" readonly style="background-color: #e8e8e8;">
                                        @if($skpRealization->performance_predicate == "Sangat Baik")
                                        <option value="Sangat Baik" selected>SANGAT BAIK</option>
                                        @else
                                        <option value="Sangat Baik">SANGAT BAIK</option>
                                        @endif
                                        @if($skpRealization->performance_predicate == "Baik")
                                        <option value="Baik" selected>BAIK</option>
                                        @else
                                        <option value="Baik">BAIK</option>
                                        @endif
                                        @if($skpRealization->performance_predicate == "Butuh Perbaikan")
                                        <option value="Butuh Perbaikan" selected>BUTUH PERBAIKAN</option>
                                        @else
                                        <option value="Butuh Perbaikan">BUTUH PERBAIKAN</option>
                                        @endif
                                        @if($skpRealization->performance_predicate == "Kurang/Miss Conduct")
                                        <option value="Kurang/Miss Conduct" selected>KURANG/MISS CONDUCT</option>
                                        @else
                                        <option value="Kurang/Miss Conduct">KURANG/MISS CONDUCT</option>
                                        @endif
                                        @if($skpRealization->performance_predicate == "Sangat Kurang")
                                        <option value="Sangat Kurang" selected>SANGAT KURANG</option>
                                        @else
                                        <option value="Sangat Kurang">SANGAT KURANG</option>
                                        @endif
                                    </select>
                                    -->
                                    <!-- temporary --><!-- temporary -->
                                    <input style="background-color: #e8e8e8;" class="form-control" type="text" readonly id="recommendation_predicate_2"/>
                                    <input type="hidden" name="predicate_work" value="{{$skpRealization->performance_predicate}}" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary ms-3" id="btn_submit_evaluasi">Simpan Evaluasi</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    
@include('modules.performance.skp-evaluation.modals.work-text-template')
@include('modules.performance.skp-evaluation.modals.behavior-text-template')

@endsection

@push('scripts')
<script>
function OpenWorkTextTemplateModal(key, isMain) {
    $('#plan_realization_key').val(key);
    $('#plan_realization_is_main').val(isMain);
    $("select[name='work_template']").val('');

    let isAny = false;
    let searchOption = '';
    if(isMain)
    {
        searchOption = $("#main_plan_value_"+key).val();
    }
    else
    {
        searchOption = $("#additional_plan_value_"+key).val();
    }

    if(searchOption)
    {
        $("select[name='work_template'] option").each(function() {
            if ($(this).val() == searchOption)
            {
                isAny = true;
            }
        })

        if(isAny)
        {
            $("select[name='work_template']").val(searchOption);
        }
    }
    
    $('#modalWorkTextTemplate').modal('show');
}

function CloseWorkTextTemplateModal() {
    $('#modalWorkTextTemplate').modal('hide');
}

function OpenBehaviorTextTemplateModal(key) {
    $('#behavior_realization_key').val(key);
    $("select[name='behavior_template']").val('');

    let isAny = false;
    let searchOption = '';
    searchOption = $("#behavior_plan_value_"+key).val();

    if(searchOption)
    {
        $("select[name='behavior_template'] option").each(function() {
            if ($(this).val() == searchOption)
            {
                isAny = true;
            }
        })

        if(isAny)
        {
            $("select[name='behavior_template']").val(searchOption);
        }
    }
    $('#modalBehaviorTextTemplate').modal('show');
}

function CloseBehaviorTextTemplateModal() {
    $('#modalBehaviorTextTemplate').modal('hide');
}

function RefreshWorkRecommendation(isChangeAll)
{
    var mainPlan = $("select[name='main_plan_work_category_id[]']")
              .map(function(){return $(this).val();}).get();

    var addPlan = $("select[name='additional_plan_work_category_id[]']")
              .map(function(){return $(this).val();}).get();

    var plans = mainPlan.concat(addPlan);
    let sumPoint = 0;
    plans.forEach(function(plan) {
        switch (plan) {
        case '1': // di atas ekspektasi
            sumPoint += 3;
            break;    
        case '2': // sesuai ekspektasi
            sumPoint += 2;
        break;    
        case '3': // di bawah ekspektasi
            sumPoint += 1;
        break;    
        }  
    });

    let avgPoint = Math.round(sumPoint / plans.length);
    let recommendation = '';
    switch (avgPoint) {
        case 1: // di bawah ekspektasi
            recommendation = 'Di Bawah Ekspektasi'
            break;    
        case 2: // sesuai ekspektasi
            recommendation = 'Sesuai Ekspektasi'
        break;    
        case 3: // di atas ekspektasi
            recommendation = 'Di Atas Ekspektasi'
        break;    
    }
    $("#recommendation_plan").val(recommendation);  
    $("#recommendation_plan_2").val(recommendation);      

    if(isChangeAll)
    {
        switch (avgPoint) {
        case 1: // di bawah ekspektasi
            $("input[name='work_category_id']").val('3');
            break;    
        case 2: // sesuai ekspektasi
            $("input[name='work_category_id']").val('2');
        break;    
        case 3: // di atas ekspektasi
            $("input[name='work_category_id']").val('1');
        break;    
        }
    }
}   

function RefreshBehaviorRecommendation(isChangeAll)
{
    var behaviors = $("select[name='behavior_plan_category_id[]']")
              .map(function(){return $(this).val();}).get();

    let sumPoint = 0;
    behaviors.forEach(function(behavior) {
        switch (behavior) {
        case '1': // di atas ekspektasi
            sumPoint += 3;
            break;    
        case '2': // sesuai ekspektasi
            sumPoint += 2;
        break;    
        case '3': // di bawah ekspektasi
            sumPoint += 1;
        break;    
        }  
    });

    let avgPoint = Math.round(sumPoint / behaviors.length);
    let recommendation = '';
    switch (avgPoint) {
        case 1: // di bawah ekspektasi
            recommendation = 'Di Bawah Ekspektasi'
            break;    
        case 2: // sesuai ekspektasi
            recommendation = 'Sesuai Ekspektasi'
        break;    
        case 3: // di atas ekspektasi
            recommendation = 'Di Atas Ekspektasi'
        break;    
    }
    $("#recommendation_behavior").val(recommendation);
    $("#recommendation_behavior_2").val(recommendation);
    
    if(isChangeAll)
    {
        switch (avgPoint) {
        case 1: // di bawah ekspektasi
            $("input[name='behavior_category_id']").val('3');
            break;    
        case 2: // sesuai ekspektasi
            $("input[name='behavior_category_id']").val('2');
        break;    
        case 3: // di atas ekspektasi
            $("input[name='behavior_category_id']").val('1');
        break;    
        }
    }
}

function RefreshPredicatePerformanceRecommendation(isChangeAll)
{
   let planRecom = $("input[name='work_category_id']").val();
   let behaviorRecom = $("input[name='behavior_category_id']").val();   
   let recommendation = '';

   switch (planRecom) {
        case '3': // di bawah ekspektasi
            if(behaviorRecom == '3') // di bawah ekspektasi
            {
                recommendation = 'Sangat Kurang';
            }
            else if(behaviorRecom == '2') // sesuai ekspektasi
            {
                recommendation = 'Butuh Perbaikan';
            }
            else if(behaviorRecom == '1') // di atas ekspektasi
            {
                recommendation = 'Butuh Perbaikan';
            }
            break;    
        case '2': // sesuai ekspektasi
            if(behaviorRecom == '3') // di bawah ekspektasi
            {
                recommendation = 'Kurang/Miss Conduct';
            }
            else if(behaviorRecom == '2') // sesuai ekspektasi
            {
                recommendation = 'Baik';
            }
            else if(behaviorRecom == '1') // di atas ekspektasi
            {
                recommendation = 'Baik';
            }
        break;    
        case '1': // di atas ekspektasi
            if(behaviorRecom == '3') // di bawah ekspektasi
            {
                recommendation = 'Kurang/Miss Conduct';
            }
            else if(behaviorRecom == '2') // sesuai ekspektasi
            {
                recommendation = 'Baik';
            }
            else if(behaviorRecom == '1') // di atas ekspektasi
            {
                recommendation = 'Sangat Baik';
            }
        break;    
    }
    
    $("#recommendation_predicate").val(recommendation);  
    $("#recommendation_predicate_2").val(recommendation); 

    if(isChangeAll)
    {
        $("input[name='predicate_work']").val(recommendation);
    }
}

function SubmitWorkTextTemplate()
{
    $('#modalWorkTextTemplate').modal('hide');
    if(!$("select[name='work_template']").val())
    {
        return;
    }
    let key = $("#plan_realization_key").val();
    let isMain = $("#plan_realization_is_main").val();
        let selectedId = '1';
        var selectedCategory = $("select[name='work_template'] :selected").parent().attr('label');
        switch (selectedCategory) {
            case 'Di Bawah Ekspektasi': // di bawah ekspektasi
                selectedId = '3';
                break;    
            case 'Sesuai Ekspektasi': // sesuai ekspektasi
                selectedId = '2';
            break;    
            case 'Di Atas Ekspektasi': // di atas ekspektasi
                selectedId = '1';
            break;    
        }

        if(isMain == 'true')
        {
            $("#main_plan_work_category_id_"+key).val(selectedId);
            $("#main_plan_value_"+key).val($("select[name='work_template']").val());
        }
        else if(isMain == 'false')
        {
            $("#additional_plan_work_category_id_"+key).val(selectedId);
            $("#additional_plan_value_"+key).val($("select[name='work_template']").val());
        }
        RefreshWorkRecommendation(true);
        RefreshPredicatePerformanceRecommendation(true);
}

function SubmitBehaviorTextTemplate()
{
    $('#modalBehaviorTextTemplate').modal('hide');
    if(!$("select[name='behavior_template']").val())
    {
        return;
    }
    let key = $("#behavior_realization_key").val();
        let selectedId = '1';
        var selectedCategory = $("select[name='behavior_template'] :selected").parent().attr('label');
        switch (selectedCategory) {
            case 'Di Bawah Ekspektasi': // di bawah ekspektasi
                selectedId = '3';
                break;    
            case 'Sesuai Ekspektasi': // sesuai ekspektasi
                selectedId = '2';
            break;    
            case 'Di Atas Ekspektasi': // di atas ekspektasi
                selectedId = '1';
            break;    
        }
        $("#behavior_plan_category_id_"+key).val(selectedId);
        $("#behavior_plan_value_"+key).val($("select[name='behavior_template']").val());

        RefreshBehaviorRecommendation(true);
        RefreshPredicatePerformanceRecommendation(true);
}


$(document).ready(function() {
    RefreshBehaviorRecommendation(false);
    RefreshWorkRecommendation(false);
    RefreshPredicatePerformanceRecommendation(false);

    $('.work-plan').on('change', function () {
        RefreshWorkRecommendation(true);
        RefreshPredicatePerformanceRecommendation(true);
    });
    
    $('.behavior-plan').on('change', function () {
        RefreshBehaviorRecommendation(true);
        RefreshPredicatePerformanceRecommendation(true);
    });

    $("input[name='work_category_id']").on('change', function () {
        RefreshPredicatePerformanceRecommendation(true);
    });

    $("input[name='behavior_category_id']").on('change', function () {
        RefreshPredicatePerformanceRecommendation(true);
    });

    $("#formEvaluation").submit(function(e){
        e.preventDefault();

        var formData = new FormData(this);

        //execute submit
        $.ajax({
            url: "{{route('modules.performance.skp-evaluation.save_evaluation')}}",
            type:'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                if(data.status == 1){
                    toastr.success(data.message);
                    setTimeout(location.reload.bind(location), 2000);
                }
                else
                {
                    if(typeof data.error === 'string')
                    {
                        toastr.error(data.error);
                    }
                    else
                    {
                        window.printErrorMsg(data.error);
                    }
                }
            }
        });
    });
});
</script>
@endpush
