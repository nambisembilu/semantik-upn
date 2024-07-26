@extends('template.master')

@section('title', 'Home Apps')

@section('sidebar')
    @include('template.sidebar')
@endsection

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
    <!--{{ var_dump(json_encode($skp)) }}-->
    <div id="my_content">

        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div style="float:left!important;">
                    <h4 class="m-0 font-weight-bold text-primary">Sasaran Kinerja Pegawai</h4>
                </div>
                <form id="formskp">
                    @csrf
                </form>
                <div style="float:right!important;">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                        <div>
                            @if(!empty($skp))
                                @if(empty($skpRealization))
                                    @if($skp->application_status == 'Sudah Disetujui')
                                    <button type="button" class="btn btn-primary btn-sm rounded"
                                        onclick="CreateRealization()">
                                        Buat Realisasi
                                    </button>
                                    @endif
                                @else
                                    @if($skpRealization->realization_status == 'Belum Diajukan')
                                    <button type="button" class="btn btn-danger btn-sm rounded"
                                    onclick="ResetRealization('{{$skpRealization->id}}')">
                                    Reset Realisasi
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm rounded"
                                    onclick="ApplyRealization('{{$skpRealization->id}}')">
                                    Ajukan Realisasi
                                    </button>
                                    @elseif($skpRealization->realization_status == 'Belum Dievaluasi')
                                    <button type="button" class="btn btn-danger btn-sm rounded" onclick="CancelApplymentRealization('{{$skpRealization->id}}')">
                                        Batalkan Pengajuan SKP
                                    </button>
                                    @elseif($skpRealization->realization_status == 'Sudah Dievaluasi')
                                    <button type="button" class="btn btn-primary btn-sm rounded" onclick="AddPrintEvaluationSKPModal()">
                                        <i class="ph ph-printer"></i>&nbsp;&nbsp;Evaluasi SKP
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm rounded" onclick="AddPrintDocEvaluationSKPModal()">
                                        <i class="ph ph-printer"></i>&nbsp;&nbsp;Dok Evaluasi SKP
                                    </button>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="card-body pt-2">
                <!-- Status SKP -->
                <div class="mb-2">
                    @if(!empty($skp))
                        @if($skp->application_status == 'Belum Diajukan')
                        <span class="badge bg-danger">SKP Belum Diajukan</span>
                        @elseif($skp->application_status == 'Belum Disetujui')
                        <span class="badge bg-danger">SKP Belum Disetujui</span>
                        @elseif($skp->application_status == 'Tidak Disetujui')
                        <span class="badge bg-danger">SKP Tidak Disetujui</span>
                        @endif
                    @endif
                    @if(!empty($skpRealization))
                        @if($skpRealization->realization_status == 'Belum Diajukan')
                        <span class="badge bg-danger">Belum Diajukan</span>
                        @elseif($skpRealization->realization_status == 'Belum Dievaluasi')
                        <span class="badge bg-primary">Belum Dievaluasi</span>
                        @else
                        <span class="badge bg-primary">Sudah Dievaluasi</span>
                        @endif
                    @else
                        <span class="badge bg-danger">Belum Dibuat</span>
                    @endif
                </div>
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
                                            {{$skpPlanRealization->feedback}}
                                        </td>
                                        <td rowspan="3" class="text-center" style="width: 15%;">
                                            @if($skpRealization->realization_status == 'Belum Diajukan')
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="tooltip"
                                                data-placement="top" title="Isi Realisasi" 
                                                onclick="AddUpdateRealizationValueModal('{{$skpPlanRealization->id}}', '{{$skpPlanRealization->skpWorkPlan->title}}', '{{$skpPlanRealization->realization}}', '{{$skpPlanRealization->supporting_evidence}}')">
                                                <i class="ph ph-pencil-simple"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                data-placement="top" title="Kosongkan Realisasi"
                                                onclick="EmptyRealizationValue('{{$skpPlanRealization->id}}')">
                                                <i class="ph ph-prohibit"></i>
                                            </button>
                                            @endif
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
                                <td colspan="2">B. Tambahan</td>
                                <td class="text-left">
                                </td>
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
                                            {{$skpPlanRealization->feedback}}
                                        </td>
                                        <td rowspan="3" class="text-center" style="width: 15%;">
                                            @if($skpRealization->realization_status == 'Belum Diajukan')
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="tooltip"
                                                data-placement="top" title="Isi Realisasi" 
                                                onclick="AddUpdateRealizationValueModal('{{$skpPlanRealization->id}}','{{$skpPlanRealization->skpWorkPlan->title}}','{{$skpPlanRealization->realization}}', '{{$skpPlanRealization->supporting_evidence}}')">
                                                <i class="ph ph-pencil-simple"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                                data-placement="top" title="Kosongkan Realisasi"
                                                onclick="EmptyRealizationValue('{{$skpPlanRealization->id}}')">
                                                <i class="ph ph-prohibit"></i>
                                            </button>
                                            @endif
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
            </div>
        </div>



    </div>
    @include('modules.performance.realization.modals.update-realization-value')
    @include('modules.performance.realization.modals.print-evaluation-skp')
    @include('modules.performance.realization.modals.print-doc-evaluation-skp')
@endsection

@push('scripts')
<script>

function AddPrintEvaluationSKPModal() {
    $('#modalPrintEvaluationSKP').modal('show');
}

function ClosePrintEvaluationSKPModal() {
    $('#modalPrintEvaluationSKP').modal('hide');
}

function AddPrintDocEvaluationSKPModal() {
    $('#modalPrintDocEvaluationSKP').modal('show');
}

function ClosePrintDocEvaluationSKPModal() {
    $('#modalPrintDocEvaluationSKP').modal('hide');
}

function CreateRealization() {
    $.ajax({
        url: "{{route('modules.performance.realization.create_realization')}}",
            method: 'POST',
            data: 
            {
                _token: $("input[name='_token']").val(),
            },
            async: false,
            beforeSend: () => 
            {
                $("#loader").show()
            },
            complete: () => 
            {
                $("#loader").hide()
            },
            success: function (response) {
                if (response.status == '1') 
                {
                    toastr.success(response.message);
                    setTimeout(location.reload.bind(location), 2000);
                } 
                else 
                {
                    toastr.error(response.message);
                }
            }
        })
}
function AddUpdateRealizationValueModal(id, skpPlan, realization, supporting_evidence) {
    $('#modalUpdateRealizationValue').modal('show');
    $('#skp_plan_realization_id').val(id);
    $('#skp_plan').val(skpPlan);
    $('#realization').val(realization);
    $('#supporting_evidence').val(supporting_evidence);
}

function CloseUpdateRealizationValueModal() {
    $('#modalUpdateRealizationValue').modal('hide');
}

function EmptyRealizationValue(skpPlanRealizationId) {
    $.ajax({
    url: "{{route('modules.performance.realization.empty_realization_value')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_plan_realization_id: skpPlanRealizationId
        },
        async: false,
        success: function (response) {
            if(response.status == 1)
            {
                toastr.success(response.message);
                setTimeout(location.reload.bind(location), 2000);
            }
            else
            {
                toastr.error(response.message);
            }
        }
    })
}

function ApplyRealization(skpRealizationId) {
    $.ajax({
    url: "{{route('modules.performance.realization.apply_realization')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_realization_id: skpRealizationId
        },
        async: false,
        success: function (response) {
            if(response.status == 1)
            {
                toastr.success(response.message);
                setTimeout(location.reload.bind(location), 2000);
            }
            else
            {
                toastr.error(response.message);
            }
        }
    })
}

function ResetRealization(skpRealizationId) {
    $.ajax({
    url: "{{route('modules.performance.realization.reset_realization')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_realization_id: skpRealizationId
        },
        async: false,
        beforeSend: () => 
        {
            $("#loader").show()
        },
        complete: () => 
        {
            $("#loader").hide()
        },
        success: function (response) {
        if(response.status == 1)
        {
            toastr.success(response.message);
            setTimeout(location.reload.bind(location), 2000);
        }
        else
        {
            toastr.error(response.message);
        }
        }
    })
}

function CancelApplymentRealization(skpRealizationId) {
    $.ajax({
    url: "{{route('modules.performance.realization.cancel_applyment_realization')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_realization_id: skpRealizationId
        },
        async: false,
        success: function (response) {
            if(response.status == 1)
            {
                toastr.success(response.message);
                setTimeout(location.reload.bind(location), 2000);
            }
            else
            {
                toastr.error(response.message);
            }
        }
    })
}

$(document).ready(function() {
$("#formUpdateRealizationValue").submit(function(e){
        e.preventDefault();

        //clear error before
        var formData = new FormData(this);

        //execute submit
        $.ajax({
            url: "{{route('modules.performance.realization.update_realization_value')}}",
            type:'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                if(data.status == 1){
                    toastr.success(data.message);
                    CloseUpdateRealizationValueModal();
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
