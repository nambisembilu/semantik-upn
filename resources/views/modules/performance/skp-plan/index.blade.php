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

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
<!--{{var_dump(json_encode($attachmentCategories))}}-->
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
                            @if(empty($skp))
                            <button type="button" class="btn btn-primary btn-sm rounded" onclick="CreateSKP()">
                                Buat SKP
                            </button>
                            @else
                                <input type="hidden" id="skpstatus" value="{{$skp->application_status}}" />
                                @if(!empty($skp->skpWorkPlans) && ($skp->application_status == 'Belum Diajukan' || $skp->application_status == 'Tidak Disetujui'))
                                <button type="button" class="btn btn-danger btn-sm rounded" onclick="ResetSKP({{$skp->id}})">
                                    Reset SKP
                                </button>
                                <button type="button" class="btn btn-primary btn-sm rounded" onclick="ApplySKP({{$skp->id}})">
                                    Ajukan SKP
                                </button>
                                @elseif(!empty($skp->skpWorkPlans) && $skp->application_status == 'Belum Disetujui')
                                <button type="button" class="btn btn-danger btn-sm rounded" onclick="CancelApplymentSKP({{$skp->id}})">
                                    Batalkan Pengajuan SKP
                                </button>
                                @elseif(!empty($skp->skpWorkPlans) && $skp->application_status == 'Sudah Disetujui')
                                <button type="button" class="btn btn-primary btn-sm rounded" onclick="AddPrintSKPModal()">
                                    <i class="ph ph-printer"></i>&nbsp;&nbsp;Rencana SKP
                                </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body pt-2">
                    <!-- Status SKP -->
                    <div class="mb-2">
                        @if(!empty($skp))
                            @if($skp->application_status == 'Tidak Disetujui')
                            <span class="badge bg-primary">Sudah Diajukan</span>
                            <span class="badge bg-danger">Tidak Disetujui</span>
                            @elseif($skp->application_status == 'Belum Diajukan')
                            <span class="badge bg-danger">Belum Diajukan</span>
                            @elseif($skp->application_status == 'Belum Disetujui')
                            <span class="badge bg-primary">Sudah Diajukan</span>
                            <span class="badge bg-danger">Belum Disetujui</span>
                            @else
                            <span class="badge bg-primary">Sudah Diajukan</span>
                            <span class="badge bg-primary">Sudah Disetujui</span>
                            @endif
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
                                    <td colspan="3">HASIL KERJA</td>
                                </tr>
                                <!-- Hasil Kerja Utama -->
                                <tr class="table-primary text-primary">
                                    <td colspan="2">A. Utama</td>
                                    <td class="text-left" style="width: 85px">
                                        @if(!empty($skp))
                                            @if($skp->application_status == 'Belum Diajukan' || $skp->application_status == 'Tidak Disetujui')
                                            <button type="button" class="btn btn-sm btn-primary" title="Tambah Hasil Kerja Utama"
                                             onclick="AddCascadingWorkplanModal(0)">
                                                <i class="ph-sort-ascending"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" title="Tambah Hasil Kerja Tambahan  / Utama Non Cascading" 
                                            onclick="AddWorkplanModal(0)">
                                                <i class="ph ph-plus"></i>
                                            </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @if(!empty($mainSkp) && count($mainSkp) > 0)
                                    @foreach($mainSkp[0]->skpWorkPlans as $key => $skpWorkPlan)
                                    <!-- Hasil Kerja Utama -->
                                    @if(empty($skpWorkPlan->get_task_from))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td class="text-center" style="width:5%;">
                                            {{$key + 1}}. </td>
                                        <td style="width:80%;">
                                            <!-- <span class="badge badge-pill badge-primary"><small>Cascading</small></span> -->
                                            &nbsp;<i class="fas fa-user-circle" data-toggle="tooltip" data-placement="top"
                                                title=""></i>&nbsp;
                                            {{$skpWorkPlan->title}} (Penugasan Dari : {{$skpWorkPlan->get_task_from}})
                                        </td>
                                        <td class="text-left" style="width:20%;">
                                                <button type="button" class="btn btn-sm btn-success" title="Edit Penugasan Dari"
                                                onclick="AddUpdateTaskFromModal('{{$skpWorkPlan->intervention_assignment_id}}', {{$skpWorkPlan->id}}, '{{$skpWorkPlan->title}}', '{{ !empty($officerWorkUnit) ? $officerWorkUnit->workPosition->name : '-' }}', '{{$skpWorkPlan->get_task_from}}')"
                                                >
                                                    <i class="ph-star"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success" title="Edit Hasil Kerja Utama" 
                                                onclick="OpenMainWorkPlanModal({{$skpWorkPlan->id}},'{{!empty($skpWorkPlan->intervention_assignment_id)}}')">
                                                    <i class="ph ph-check"></i>
                                                </button>
                                            @if($skp->application_status != 'Sudah Disetujui')
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Penugasan Dari" 
                                                onclick="DeleteGetTaskFrom({{$skpWorkPlan->id}})">
                                                    <i class="ph ph-prohibit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Hasil Kerja" 
                                                onclick="DeleteWorkPlan({{$skpWorkPlan->id}})">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Indikator Hasil Kerja Utama -->
                                    <tr>
                                        <td rowspan="3">&nbsp;</td>
                                        <td>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</td>
                                        <td class="text-left"></td>
                                    </tr>
                                    @if(empty($skpWorkPlan->get_task_from))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td>
                                            <ul class="mb-0" style="margin-left: -15px">
                                            @foreach($skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
                                                <li>
                                                    {{$skpWorkIndicator->title}} 
                                                </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-left"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
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
                                    @foreach($additionalSkp[0]->skpWorkPlans as $key => $skpWorkPlan)
                                    <!-- Hasil Kerja Utama -->
                                    @if(empty($skpWorkPlan->get_task_from))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td class="text-center" style="width:5%;">
                                            {{$key + 1}}. </td>
                                        <td style="width:80%;">
                                            <!-- <span class="badge badge-pill badge-primary"><small>Cascading</small></span> -->
                                            &nbsp;<i class="fas fa-user-circle" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="Direktorat Sumber Daya Manusia"></i>&nbsp;
                                            {{$skpWorkPlan->title}} (Penugasan Dari : {{$skpWorkPlan->get_task_from}})
                                        </td>
                                        <td class="text-left" style="width:20%;">
                                                <button type="button" class="btn btn-sm btn-success" title="Edit Penugasan Dari"
                                                onclick="AddUpdateTaskFromModal('{{$skpWorkPlan->intervention_assignment_id}}', {{$skpWorkPlan->id}}, '{{$skpWorkPlan->title}}', '{{ !empty($officerWorkUnit) ? $officerWorkUnit->workPosition->name : '-' }}', '{{$skpWorkPlan->get_task_from}}')"
                                                >
                                                    <i class="ph-star"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success" title="Edit Hasil Kerja Utama" 
                                                onclick="AddWorkplanModal({{$skpWorkPlan->id}})">
                                                    <i class="ph ph-check"></i>
                                                </button>
                                            @if($skp->application_status != 'Sudah Disetujui')
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Penugasan Dari" 
                                                onclick="DeleteGetTaskFrom({{$skpWorkPlan->id}})">
                                                    <i class="ph ph-prohibit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Hasil Kerja" 
                                                onclick="DeleteWorkPlan({{$skpWorkPlan->id}})">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Indikator Hasil Kerja Utama -->
                                    <tr>
                                        <td rowspan="3">&nbsp;</td>
                                        <td>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</td>
                                        <td class="text-left"></td>
                                    </tr>
                                    @if(empty($skpWorkPlan->get_task_from))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td>
                                            <ul class="mb-0" style="margin-left: -15px">
                                            @foreach($skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
                                                <li>
                                                    {{$skpWorkIndicator->title}} 
                                                </li>
                                            @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-left"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    @endforeach
                                @endif    
                                <tr>
                                    <td style="width: 50px">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- Perilaku Kerja -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-blue table-sm mb-4">
                            <tbody>
                                <tr class="table-primary text-primary">
                                    <td colspan="3">PERILAKU KERJA</td>
                                </tr>
                                @if(!empty($skpBehaviors) && count($skpBehaviors) > 0)
                                    @foreach($skpBehaviors as $key => $skpBehavior)
                                    <tr>
                                        <td style="width: 50px" class="text-center align-top">
                                        {{$key + 1}}.
                                        </td>
                                        <td class="align-top">
                                            {{$skpBehavior->behaviorCategory->name}} <br>
                                            <ul class="mb-2 pl-4">
                                                @foreach($skpBehavior->behaviorCategory->behaviorCriterias as $key => $behaviorCriteria)
                                                <li>{{$behaviorCriteria->name}}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td style="min-width: 300px; max-width: 400px;">
                                            Ekspektasi Khusus Pimpinan: {{$skpBehavior->notes}}
                                            <br>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- Lampiran -->
                    <div class="table-responsive mb-2">
                        <table class="table table-bordered table-hover table-blue table-sm mb-4">
                            <tbody>
                                <tr class="table-primary text-primary">
                                    <td colspan="3">LAMPIRAN</td>
                                </tr>
                                @if(!empty($attachmentCategories) && count($attachmentCategories) > 0)
                                    @foreach($attachmentCategories as $attachmentCategory)
                                    <tr class="table-primary text-primary mpdf-table-header-gray">
                                        <td colspan="2">{{$attachmentCategory->name}}</td>
                                        <td class="text-left" style="width: 85px">
                                            @if($skp->application_status != 'Sudah Disetujui')
                                            <button type="button" class="btn btn-sm btn-primary" title="Tambah Lampiran" 
                                            onclick="AddAttachmentModal({{$attachmentCategory->id}}, '{{$attachmentCategory->name}}')">
                                                <i class="ph ph-plus"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                        @foreach($attachmentCategory->skpWorkAttachments as $key => $skpWorkAttachment)
                                        <tr>
                                            <td class="text-center" style="width: 50px">{{$key+1}}.</td>
                                            <td>{{$skpWorkAttachment->description}}</td>
                                            <td class="text-left"></td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>


        </div><!-- content -->
    </div>
    <div class="span-5 last">
        <div id="sidebar">
        </div><!-- sidebar -->
    </div>
</div>
@include('modules.performance.skp-plan.modals.create-cascading-work-plan')
@include('modules.performance.skp-plan.modals.create-work-plan')
@include('modules.performance.skp-plan.modals.create-attachment')
@include('modules.performance.skp-plan.modals.update-task-from')
@include('modules.performance.skp-plan.modals.print-skp-plan')
@include('modules.performance.skp-plan.modals.notification')
@include('modules.performance.skp-plan.modals.attachment-text-template')

@endsection

@push('scripts')
<script>
function SubmitAttachmentTextTemplate()
{
    $('#modalAttachmentTextTemplate').modal('hide');
    if(!$("select[name='attachment_template']").val())
    {
        return;
    }
    let attachmentKey = $('#attachment_key').val();
    $('#work_attachment_'+attachmentKey).val($("select[name='attachment_template']").val());
}

function OpenAttachmentTextTemplateModal(key) {
    $('#modalAttachmentTextTemplate').modal('show');
    $('#attachment_key').val(key);
    let attachmentCategoryId = $('#attachment_category_id').val();
    GetAttachmentTextTemplateByCategory(attachmentCategoryId);
}

function CloseAttachmentTextTemplateModal() {
    $('#modalAttachmentTextTemplate').modal('hide');
}

function GetAttachmentTextTemplateByCategory(attachmentCategoryId) {
    $.ajax({
        url: "{{route('modules.performance.skp-plan.get_attachment_text_template_by_category')}}",
            method: 'POST',
            data: 
            {
                attachment_category_id: attachmentCategoryId,
                _token: $("input[name='_token']").val(),
            },
            async: false,
            success: function (response) {
                $('#attachment_template').empty();
                $('#attachment_template').append(new Option('== Pilih Template ==', ''))
                response.forEach(function(item) {
                    $('#attachment_template').append(new Option(item.name, item.name))
                });

                /*
                if($('#attachment_template').val())
                {
                    $('#attachment_template').val($('#work_unit_id').val());
                }
                */
            }
        })
}

function OpenNotificationModal() {
    $('#modalNotification').modal('show');
}

function CloseNotificationModal() {
    $('#modalNotification').modal('hide');
}

function OpenMainWorkPlanModal(id, isCascading)
{
    if(isCascading)
    {
        AddCascadingWorkplanModal(id);
    }
    else
    {
        AddWorkplanModal(id);
    }
}    
//create skp section    
function CreateSKP() {
    $.ajax({
        url: "{{route('modules.performance.skp-plan.create_skp')}}",
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
//end of create skp section  

//add cascading workplan section  
function AddCascadingWorkplanModal(id) {
    $('#modalAddCascadingWorkPlan').modal('show');
    $('#cascading_skp_plan_id').val(id);
    $('#cascading_work_plan_title').val('');
    $('#intervention_indicator').val('');
    $('#cascading_work_plan_indicator_container').empty();
    
    RefreshInterventionAssignment();

    if(id > 0)
    {
        GetWorkPlanById(id, true);
    }
}

function CloseCascadingWorkplanModal() {
    $('#modalAddCascadingWorkPlan').modal('hide');
}

function RefreshInterventionAssignment() {
    $.ajax({
        url: "{{route('modules.performance.skp-plan.get_intervention_assignment')}}",
            method: 'POST',
            data: 
            {
                _token: $("input[name='_token']").val(),
            },
            async: false,
            success: function (response) {
                $('#intervention_indicator').empty();
                response.forEach(function(item) {
                    $('#intervention_indicator').append(new Option(item.title, item.id))
                });
            }
        })
}

function GetInterventionAssigner(swaId) {
    $.ajax({
        url: "{{route('modules.performance.skp-plan.get_intervention_assignment_assigner')}}",
            method: 'POST',
            data: 
            {
                _token: $("input[name='_token']").val(),
                swa_id: swaId,
            },
            async: false,
            success: function (response) {
                $('#task_from_default').val(response[0].name);
            }
        })
}

function CopyWorkIndicatorTitle()
{
    $("#cascading_work_plan_title").val($('#intervention_indicator option:selected').text());
}

function AddCascadingIndicator()
{
    let count = parseInt($("#cascading_work_plan_indicator_container").attr("data-count"));
    
    $("#cascading_work_plan_indicator_container").append(`
    <div class="input-group cascading-indicator-group" data-position="${count}">
        <input id="cascading_work_plan_indicator_id_${count}" name="cascading_work_plan_indicator_id[${count}]" type="hidden" value="0">  
        <input id="cascading_work_plan_indicator_${count}" name="cascading_work_plan_indicator[${count}]" type="text" class="form-control" data-position="${count}">  
        <span id="span_cascading_work_plan_indicator_${count}" class="text-danger error-text cascading_work_plan_indicator_${count}_err"></span>
        <button id="cascading_delete_indicator_${count}" class="btn btn-danger btn-delete-cascading-indicator" type="button" data-position="${count}">
            <i class="ph ph-trash-simple"></i>
        </button>
    </div>
    `)
    $("#cascading_work_plan_indicator_container").attr("data-count", count+1);

}
//end of add cascading workplan section 

//add workplan section  
function AddWorkplanModal(id) {
    $('#modalAddWorkPlan').modal('show');

    $('#skp_plan_id').val(id);
    $('#work_plan_title').val('');
    $('#is_main').prop('checked', false);
    
    $('#work_plan_indicator_container').empty();

    if(id > 0)
    {
        GetWorkPlanById(id, false);
    }
}

function CloseWorkplanModal() {
    $('#modalAddWorkPlan').modal('hide');
}

function AddIndicator()
{
    let count = parseInt($("#work_plan_indicator_container").attr("data-count"));
    
    $("#work_plan_indicator_container").append(`
    <div class="input-group indicator-group" data-position="${count}">
        <input id="work_plan_indicator_id_${count}" name="work_plan_indicator_id[${count}]" type="hidden" value="0">  
        <input id="work_plan_indicator_${count}" name="work_plan_indicator[${count}]" type="text" class="form-control" data-position="${count}">  
        <span id="span_work_plan_indicator_${count}" class="text-danger error-text work_plan_indicator_${count}_err"></span>
        <button id="delete_indicator_${count}" class="btn btn-danger btn-delete-indicator" type="button" data-position="${count}">
            <i class="ph ph-trash-simple"></i>
        </button>
    </div>
    `)
    $("#work_plan_indicator_container").attr("data-count", count+1);

}
//end of add workplan section 

//add attachment section  
function AddAttachmentModal(id, name) {
    $('#modalAddAttachment').modal('show');
    $('#attachment_category_id').val(id);
    $('#attachment_category_name_label').text(name);

    GetWorkAttachmentBySKPAndCategory();
}

function CloseAttachmentModal() {
    $('#modalAddAttachment').modal('hide');
}

function PopulateWorkAttachment(attachments)
{
    $("#work_attachment_container").attr("data-count", attachments.length);
    for (let i = 0; i < attachments.length; i++) {
        $("#work_attachment_container").append(`
        <div class="input-group attachment-group" data-position="${i}">
            <button onclick="OpenAttachmentTextTemplateModal(${i})" class="btn btn-primary" type="button" title="Isi Dengan Template"><i class="ph ph-copy"></i></button>
            <input id="work_attachment_${i}" name="work_attachment[${i}]" type="text" class="form-control" value="${attachments[i].description}" data-position="${i}">  
            <span id="span_work_attachment_${i}" class="text-danger error-text work_attachment_${i}_err"></span>
            <button id="delete_attachment_${i}" class="btn btn-danger btn-delete-attachment" type="button" data-position="${i}">
                <i class="ph ph-trash-simple"></i>
            </button>
        </div>
        `)
    }
}

function GetWorkAttachmentBySKPAndCategory() {
    $.ajax({
        url: "{{route('modules.performance.skp-plan.get_work_attachment_by_id_and_category')}}",
            method: 'POST',
            data: 
            {
                _token: $("input[name='_token']").val(),
                skp_id: $("#skp_id").val(),
                attachment_category_id: $("#attachment_category_id").val(),
            },
            async: false,
            success: function (response) {
                $('#work_attachment_container').empty();
                PopulateWorkAttachment(response);
            }
        })
}

function PopulateWorkPlanIndicator(indicators)
{
    $("#work_plan_indicator_container").attr("data-count", indicators.length);
    for (let i = 0; i < indicators.length; i++) {
        $("#work_plan_indicator_container").append(`
        <div class="input-group indicator-group" data-position="${i}">
            <input id="work_plan_indicator_id_${i}" name="work_plan_indicator_id[${i}]" type="hidden" value="${indicators[i].id}">  
            <input id="work_plan_indicator_${i}" name="work_plan_indicator[${i}]" type="text" class="form-control" data-position="${i}" value="${indicators[i].title}">  
            <span id="span_work_plan_indicator_${i}" class="text-danger error-text work_plan_indicator_${i}_err"></span>
            <button id="delete_indicator_${i}" class="btn btn-danger btn-delete-indicator" type="button" data-position="${i}">
                <i class="ph ph-trash-simple"></i>
            </button>
        </div>
        `)
    }
}

function PopulateCascadingWorkPlanIndicator(indicators)
{
    $("#cascading_work_plan_indicator_container").attr("data-count", indicators.length);
    for (let i = 0; i < indicators.length; i++) {
        $("#cascading_work_plan_indicator_container").append(`
        <div class="input-group cascading-indicator-group" data-position="${i}">
            <input id="cascading_work_plan_indicator_id_${i}" name="cascading_work_plan_indicator_id[${i}]" type="hidden" value="${indicators[i].id}">  
            <input id="cascading_work_plan_indicator_${i}" name="cascading_work_plan_indicator[${i}]" type="text" class="form-control" data-position="${i}" value="${indicators[i].title}">  
            <span id="span_cascading_work_plan_indicator_${i}" class="text-danger error-text cascading_work_plan_indicator_${i}_err"></span>
            <button id="cascading_delete_indicator_${i}" class="btn btn-danger btn-delete-cascading-indicator" type="button" data-position="${i}">
                <i class="ph ph-trash-simple"></i>
            </button>
        </div>
        `)
    }
}

function GetWorkPlanById(id, isCascading) {
    $.ajax({
        url: "{{route('modules.performance.skp-plan.get_work_plan_by_id')}}",
            method: 'POST',
            data: 
            {
                _token: $("input[name='_token']").val(),
                skp_plan_id: id,
            },
            async: false,
            success: function (response) {
                if(!isCascading)
                {
                    $('#skp_plan_id').val(response.id);
                    $('#work_plan_title').val(response.title);
                    if(response.is_main)
                    {
                        $('#is_main').prop('checked', true);
                    }
                    $('#work_plan_indicator_container').empty();
                    PopulateWorkPlanIndicator(response.skp_work_indicators);
                }
                else
                {
                    $('#cascading_skp_plan_id').val(response.id);
                    $('#cascading_work_plan_title').val(response.title);
                    $('#intervention_indicator').val(response.intervention_assignment_id);
                    $('#cascading_work_plan_indicator_container').empty();
                    PopulateCascadingWorkPlanIndicator(response.skp_work_indicators);
                }
            }
        })
}

function AddAttachment()
{
    let count = parseInt($("#work_attachment_container").attr("data-count"));
    
    $("#work_attachment_container").append(`
    <div class="input-group attachment-group" data-position="${count}">
        <button onclick="OpenAttachmentTextTemplateModal(${count})" class="btn btn-primary" type="button" title="Isi Dengan Template"><i class="ph ph-copy"></i></button>
        <input id="work_attachment_${count}" name="work_attachment[${count}]" type="text" class="form-control" data-position="${count}">  
        <span id="span_work_attachment_${count}" class="text-danger error-text work_attachment_${count}_err"></span>
        <button id="delete_attachment_${count}" class="btn btn-danger btn-delete-attachment" type="button" data-position="${count}">
            <i class="ph ph-trash-simple"></i>
        </button>
    </div>
    `)
    $("#work_attachment_container").attr("data-count", count+1);

}
//end of add attachment section 

//update get task from section  
function AddUpdateTaskFromModal(interventionAssignmentId, id, skpPlan, defaultTaskFrom, getTaskFrom) {
    if(interventionAssignmentId)
    {
        GetInterventionAssigner(interventionAssignmentId);
    }
    else
    {
        $('#task_from_default').val(defaultTaskFrom);
    }

    $('#modalUpdateTaskFrom').modal('show');
    $('#skp_work_plan_id').val(id);
    $('#task_from_skp_plan').val(skpPlan);
    $('#get_task_from').val(getTaskFrom);
}

function CloseUpdateTaskFromModal() {
    $('#modalUpdateTaskFrom').modal('hide');
}

function CopyTaskFromDefault()
{
    $("#get_task_from").val($('#task_from_default').val());
}
//end of update get task from section 

//delete section
function DeleteWorkPlan(skpWorkPlanId) {
    $.ajax({
    url: "{{route('modules.performance.skp-plan.delete_work_plan')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_work_plan_id: skpWorkPlanId
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

function DeleteGetTaskFrom(skpWorkPlanId) {
    $.ajax({
    url: "{{route('modules.performance.skp-plan.delete_get_task_from')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_work_plan_id: skpWorkPlanId
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

//end delete section

function ApplySKP(skpId) {
    $.ajax({
    url: "{{route('modules.performance.skp-plan.apply_skp')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_id: skpId
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

function ResetSKP(skpId) {
    $.ajax({
    url: "{{route('modules.performance.skp-plan.reset_skp')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_id: skpId
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

function CancelApplymentSKP(skpId) {
    $.ajax({
    url: "{{route('modules.performance.skp-plan.cancel_applyment_skp')}}",
        method: 'POST',
        data: 
        {
            _token: $("input[name='_token']").val(),
            skp_id: skpId
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

//add print skp plan section  
function AddPrintSKPModal() {
    $('#modalPrintSKP').modal('show');
}

function ClosePrintSKPModal() {
    $('#modalPrintSKP').modal('hide');
}

$(document).on("click", ".btn-delete-cascading-indicator", function() {
        let count = parseInt($("#cascading_work_plan_indicator_container").attr("data-count"));
        $("#cascading_work_plan_indicator_container").attr("data-count", count-1);
        let position = parseInt($(this).attr("data-position"));
        $(`.cascading-indicator-group[data-position=${position}]`).remove();

        for (let i = position+1; i <= count-1; i++) {
            $(`.cascading-indicator-group[data-position=${i}]`).attr("data-position", i-1);
            $(`#cascading_work_plan_indicator_id_${i}`).attr("name", `cascading_work_plan_indicator_id[${i-1}]`);
            $(`#cascading_work_plan_indicator_id_${i}`).attr("id", `cascading_work_plan_indicator_id_${i-1}`);
            $(`#cascading_work_plan_indicator_${i}`).attr("data-position", i-1);
            $(`#cascading_work_plan_indicator_${i}`).attr("name", `cascading_work_plan_indicator[${i-1}]`);
            $(`#cascading_work_plan_indicator_${i}`).attr("id", `cascading_work_plan_indicator_${i-1}`);
            $(`#span_cascading_work_plan_indicator_${i}`).attr("class", `text-danger error-text cascading_work_plan_indicator_${i-1}_err`);
            $(`#span_cascading_work_plan_indicator_${i}`).attr("id", `span_cascading_work_plan_indicator_${i-1}_err`);
            $(`#cascading_delete_indicator_${i}`).attr("data-position", i-1);
            $(`#cascading_delete_indicator_${i}`).attr("id", `delete_indicator_${i-1}`);
        }
});

$(document).on("click", ".btn-delete-indicator", function() {
        let count = parseInt($("#work_plan_indicator_container").attr("data-count"));
        $("#work_plan_indicator_container").attr("data-count", count-1);
        let position = parseInt($(this).attr("data-position"));
        $(`.indicator-group[data-position=${position}]`).remove();

        for (let i = position+1; i <= count-1; i++) {
            $(`.indicator-group[data-position=${i}]`).attr("data-position", i-1);
            $(`#work_plan_indicator_id_${i}`).attr("name", `work_plan_indicator_id[${i-1}]`);
            $(`#work_plan_indicator_id_${i}`).attr("id", `work_plan_indicator_id_${i-1}`);
            $(`#work_plan_indicator_${i}`).attr("data-position", i-1);
            $(`#work_plan_indicator_${i}`).attr("name", `work_plan_indicator[${i-1}]`);
            $(`#work_plan_indicator_${i}`).attr("id", `work_plan_indicator_${i-1}`);
            $(`#span_work_plan_indicator_${i}`).attr("class", `text-danger error-text work_plan_indicator_${i-1}_err`);
            $(`#span_work_plan_indicator_${i}`).attr("id", `span_work_plan_indicator_${i-1}_err`);
            $(`#delete_indicator_${i}`).attr("data-position", i-1);
            $(`#delete_indicator_${i}`).attr("id", `delete_indicator_${i-1}`);
        }
});
 
$(document).on("click", ".btn-delete-attachment", function() {
        let count = parseInt($("#work_attachment_container").attr("data-count"));
        $("#work_attachment_container").attr("data-count", count-1);
        let position = parseInt($(this).attr("data-position"));
        $(`.attachment-group[data-position=${position}]`).remove();

        for (let i = position+1; i <= count-1; i++) {
            $(`.attachment-group[data-position=${i}]`).attr("data-position", i-1);
            $(`#work_attachment_${i}`).attr("data-position", i-1);
            $(`#work_attachment_${i}`).attr("name", `work_attachment[${i-1}]`);
            $(`#work_attachment_${i}`).attr("id", `work_attachment_${i-1}`);
            $(`#span_work_attachment_${i}`).attr("class", `text-danger error-text work_attachment_${i-1}_err`);
            $(`#delete_attachment_${i}`).attr("data-position", i-1);
            $(`#delete_attachment_${i}`).attr("id", `delete_attachment_${i-1}`);
        }
});

$(document).ready(function() {

    if($("#skpstatus").val() && ($("#skpstatus").val() == "Tidak Disetujui" || $("#skpstatus").val() == "Sudah Disetujui"))
    {
        OpenNotificationModal();
    }
    $("#formAddCascadingWorkPlan").submit(function(e){
        e.preventDefault();

        //clear error before
        $('.intervention_indicator_err').text("");
        $('.work_plan_title_err').text("");

        var formData = new FormData(this);

        //execute submit
        $.ajax({
            url: "{{route('modules.performance.skp-plan.create_cascading_skp_work_indicator_plan')}}",
            type:'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                if(data.status == 1){
                    toastr.success(data.message);
                    CloseCascadingWorkplanModal();
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

    $("#formAddWorkPlan").submit(function(e){
        e.preventDefault();

        //clear error before
        $('.work_plan_title_err').text("");

        let is_main_checked = $("input[name='is_main']:checked").length > 0;;
        var formData = new FormData(this);
        formData.append("is_main_checked",is_main_checked)
        //execute submit
        $.ajax({
            url: "{{route('modules.performance.skp-plan.create_skp_work_indicator_plan')}}",
            type:'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                if(data.status == 1){
                    toastr.success(data.message);
                    CloseWorkplanModal();
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

    $("#formAddAttachment").submit(function(e){
        e.preventDefault();

        //clear error before
        var formData = new FormData(this);

        //execute submit
        $.ajax({
            url: "{{route('modules.performance.skp-plan.create_skp_work_attachment')}}",
            type:'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                if(data.status == 1){
                    toastr.success(data.message);
                    CloseAttachmentModal();
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

    $("#formUpdateTaskFrom").submit(function(e){
        e.preventDefault();

        //clear error before
        var formData = new FormData(this);

        //execute submit
        $.ajax({
            url: "{{route('modules.performance.skp-plan.update_get_task_from')}}",
            type:'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                if(data.status == 1){
                    toastr.success(data.message);
                    CloseUpdateTaskFromModal();
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



