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
                    <i class="ph-caret-down coallapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
<!--{{var_dump(json_encode($skp))}}-->
    <div id="my_content">
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div class="float-left">
                    <h4 class="m-0 font-weight-bold text-primary">Matriks Peran Hasil</h4>
                </div>
                <div class="float-right">&nbsp;</div>
                <div class="clearfix"></div>
            </div>

            <div class="card-body pt-4">
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
                    @else
                        <span class="badge bg-danger">SKP Belum Dibuat</span>
                    @endif
                </div>
                <div class="mb-3">
                    <form id="formAssignment">
                        @csrf
                        @if (!empty($skp))
                            <select class="form-control" name="work_plan" id="work_plan" style="width: 100%">
                                @foreach ($skp->skpWorkPlans as $skpWorkPlan)
                                    <option value="{{ $skpWorkPlan->id }}">{{ $skpWorkPlan->title }}</option>
                                @endforeach
                            </select>
                        @endif
                    </form>
                </div>

                <div class="table-responsive" id="devTableAssignments">
                </div>
            </div>
        </div>

    <div class="span-5 last">
        <div id="sidebar">
        </div><!-- sidebar -->
    </div>
</div><!-- content -->
@include('modules.performance.skp-matrix.modals.create-internal-assignment')
@include('modules.performance.skp-matrix.modals.assignment-on-person')
@endsection

@push('scripts')
    <script>
        var tableCreateAssignment;
        function GenerateTable(response) {
            if (response && response.skp_work_indicators && response.skp_work_indicators.length > 0) {
                //for JPT   
                $('#devTableAssignments').empty();
                let tableHtml = ``;
                for (let i = 0; i < response.skp_work_indicators.length; i++) {
                    let trPersonalHtml = ``;
                    for (let j = 0; j < response.skp_work_indicators[i].skpWorkAssignments.length; j++) {
                        let liWorkPlanHtml = ``;
                        for (let k = 0; k < response.skp_work_indicators[i].skpWorkAssignments[j].skp_work_plans.length; k++) {
                            liWorkPlanHtml += `
                            <li>${response.skp_work_indicators[i].skpWorkAssignments[j].skp_work_plans[k].title}</li>
                            `;
                        }
                        trPersonalHtml += `
                <tr>
                    <td class="pt-2 pl-3" style="width: 30%">
                        <span class="badge bg-primary">internal</span>
                        <span class="text-primary" style="cursor:pointer;" onclick="AddAssignmentOnPersonModal('${response.skp_work_indicators[i].skpWorkAssignments[j].assigned_to.id}')">${response.skp_work_indicators[i].skpWorkAssignments[j].assigned_to.personal.name}</span>
                         <small>
                            <ul style="margin-left: -20px;" class="pt-1 mb-2">
                                <li>NIP&nbsp;${response.skp_work_indicators[i].skpWorkAssignments[j].assigned_to.personal.work_id_number}</li>
                                <li>${response.skp_work_indicators[i].skpWorkAssignments[j].assigned_to.personal.work_title.name}</li>
                                <li>${response.skp_work_indicators[i].skpWorkAssignments[j].assigned_to.work_unit.name}</li>
                                <li>${response.skp_work_indicators[i].skpWorkAssignments[j].assigned_to.work_position.name}</li>
                            </ul>
                        </small>
                    </td>
                    <td class="pt-2 pl-3">${liWorkPlanHtml}</td>
                    <td class="pt-2">
                        <button type="button" class="btn btn-sm btn-danger" title="Hapus Penugasan Kerja" 
                            onclick="DeleteInternalAssignment('','${response.skp_work_indicators[i].id}','${response.skp_work_indicators[i].skpWorkAssignments[j].assigned_to_personal_work_unit_id}')">
                                <i class="ph ph-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
                    }

                    tableHtml += `
            <table class="table table-bordered table-hover table-blue table-sm mb-4">
                <tbody>
                    <tr class="table-primary text-primary">
                        <td class="py-2 pl-3" colspan="2">
                            ${response.skp_work_indicators[i].title}</td>
                        <td style="width: 85px">
                            <button type="button" class="btn btn-sm btn-primary" title="Tambah Cascading Internal" 
                            onclick="AddInternalAssignmentModal(${response.skp_work_indicators[i].id},'${response.skp_work_indicators[i].title}',true)">
                                <i class="ph ph-sort-ascending"></i>
                            </button>
                        </td>
                    </tr>
                    ${trPersonalHtml}
                </tbody>
            </table>
            `;
                }

                $('#devTableAssignments').append(`${tableHtml}`);
            }
            else
            {
             //for JAJF   
             $('#devTableAssignments').empty();
                let tableHtml = ``;
                let trPersonalHtml = ``;
                    for (let j = 0; j < response.skpWorkAssignments.length; j++) {
                        let liWorkPlanHtml = ``;
                        for (let k = 0; k < response.skpWorkAssignments[j].skp_work_plans.length; k++) {
                            liWorkPlanHtml += `
                    <li>${response.skpWorkAssignments[j].skp_work_plans[k].title}</li>
                    `;
                        }
                        trPersonalHtml += `
                        <tr>
                            <td class="pt-2 pl-3" style="width: 30%">
                                <span class="badge bg-primary">internal</span>
                                <span class="text-primary" style="cursor:pointer;" onclick="AddAssignmentOnPersonModal('${response.skpWorkAssignments[j].assigned_to.id}')">${response.skpWorkAssignments[j].assigned_to.personal.name}</span>
                                <small>
                                    <ul style="margin-left: -20px;" class="pt-1 mb-2">
                                        <li>NIP&nbsp;${response.skpWorkAssignments[j].assigned_to.personal.work_id_number}</li>
                                        <li>${response.skpWorkAssignments[j].assigned_to.personal.work_title.name}</li>
                                        <li>${response.skpWorkAssignments[j].assigned_to.work_unit.name}</li>
                                        <li>${response.skpWorkAssignments[j].assigned_to.work_position.name}</li>
                                    </ul>
                                </small>
                            </td>
                            <td class="pt-2 pl-3">${liWorkPlanHtml}</td>
                            <td class="pt-2">
                                <button type="button" class="btn btn-sm btn-danger" title="Hapus Penugasan Kerja" 
                                    onclick="DeleteInternalAssignment('${response.id}','','${response.skpWorkAssignments[j].assigned_to_personal_work_unit_id}')">
                                        <i class="ph ph-trash"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    }

                    tableHtml += `
                    <table class="table table-bordered table-hover table-blue table-sm mb-4">
                        <tbody>
                            <tr class="table-primary text-primary">
                                <td class="py-2 pl-3" colspan="2">
                                    ${response.title}</td>
                                <td style="width: 85px">
                                    <button type="button" class="btn btn-sm btn-primary" title="Tambah Cascading Internal" 
                                    onclick="AddInternalAssignmentModal(${response.id},'${response.title}',false)">
                                        <i class="ph ph-sort-ascending"></i>
                                    </button>
                                </td>
                            </tr>
                            ${trPersonalHtml}
                        </tbody>
                    </table>
                    `;

                $('#devTableAssignments').append(`${tableHtml}`);
            }
        }

        function GenerateAssigmentOnPersonTable(response)
        {
            let tableHtml = ``;
            let trHtml = ``;
            $('#devTableAssignmentOnPerson').empty();
            if (response && response.length > 0) 
            {
                for (let i = 0; i < response.length; i++) {
                    if(response[i].skp_work_indicator)
                    {   
                        trHtml += `
                        <tr class="">
                            <td>
                                ${(i+1)}</td>
                            <td>
                                ${response[i].skp_work_indicator.title}
                            </td>
                        </tr>
                        `;
                    }
                    else if(response[i].skp_work_plan)
                    {
                        trHtml += `
                        <tr class="">
                            <td>
                                ${(i+1)}</td>
                            <td>
                                ${response[i].skp_work_plan.title}
                            </td>
                        </tr>
                        `;
                    }
                }
                
                $("#assignmentOnPersonName").text(response[0].assigned_to.personal.name);
            }
            tableHtml += `
                    <table class="table table-bordered table-hover table-blue table-sm mb-4">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Peran Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${trHtml}
                        </tbody>
                    </table>
                    `;

            $('#devTableAssignmentOnPerson').append(`${tableHtml}`);
        }

        function AddAssignmentOnPersonModal(pwuId)
        {
            $('#modalAssignmentOnPerson').modal('show');

            $.ajax({
                    url: "{{ route('modules.performance.skp-matrix.get_assignments_on_person') }}",
                    type: 'POST',
                    data: {
                        _token: $("input[name='_token']").val(),
                        personal_work_unit_id: pwuId
                    },
                    success: function(response) {
                        GenerateAssigmentOnPersonTable(response);
                    }
            });
        }

        function CloseAssignmentOnPersonModal() {
            $('#modalAssignmentOnPerson').modal('hide');
        }

        function AddInternalAssignmentModal(id, title, isJPT) {
            $('#modalCreateInternalAssignment').modal('show');
            $('#work_indicator_title').text(title)
            GetAssignmentOptions(id, isJPT);
            
            if(isJPT)
            {
                $(`#btn_save_assignment`).attr("data-indiid", id);
            }
            else
            {
                $(`#btn_save_assignment`).attr("data-planid", id);
            }
        }

        function CloseInternalAssignmentModal() {
            $('#modalCreateInternalAssignment').modal('hide');
        }

        function CheckAllAssignee() {
            if(tableCreateAssignment)
            {
                //tableCreateAssignment.rows('.selected').data();
                tableCreateAssignment.rows().select();
                $("#btn_uncheck_all").show();
                $("#btn_check_all").hide();
                console.log(tableCreateAssignment.rows('.selected').data());
            }
        }

        function UncheckAllAssignee() {
            if(tableCreateAssignment)
            {
                //tableCreateAssignment.rows('.selected').data();
                tableCreateAssignment.rows().deselect();
                $("#btn_uncheck_all").hide();
                $("#btn_check_all").show();
            }
        }

        function SaveInternalAssignment() {
            let skpwWorkIndicatorId = $(`#btn_save_assignment`).attr("data-indiid");
            let skpwWorkPlanId = $(`#btn_save_assignment`).attr("data-planid");

            let selectedIds = jQuery.map(tableCreateAssignment.rows('.selected').data(), function(n, i){
                return n.id;
            });
            $.ajax({
                url: "{{route('modules.performance.skp-matrix.create_skp_assignment')}}",
                    method: 'POST',
                    data: 
                    {
                        _token: $("input[name='_token']").val(),
                        skp_work_indicator_id: skpwWorkIndicatorId,
                        skp_work_plan_id: skpwWorkPlanId,
                        pwu: selectedIds
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

        function DeleteInternalAssignment(skpWorkPlanId, skpWorkIndicatorId, pwuId) {
            
            $.ajax({
                url: "{{route('modules.performance.skp-matrix.delete_internal_assignment')}}",
                    method: 'POST',
                    data: 
                    {
                        _token: $("input[name='_token']").val(),
                        skp_work_indicator_id: skpWorkIndicatorId,
                        skp_work_plan_id: skpWorkPlanId,
                        pwu_id: pwuId
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

        function GetAssignmentOptions(id, isJPT)
        {
            let skpWorkIndicatorId = null;
            let skpWorkPlanId = null;
            if(isJPT)
            {
                skpWorkIndicatorId = id;
            }
            else
            {
                skpWorkPlanId = id;
            }

            if ( $.fn.DataTable.isDataTable('#tableCreateAssignment') ) {
            $('#tableCreateAssignment').DataTable().destroy();
            }

            $('#tableCreateAssignment tbody').empty();

            tableCreateAssignment = $('#tableCreateAssignment').DataTable({
                pageLength: 10,
                processing: true,
                select: {
                    style: 'multi'
                },   
                ajax: {
                    url: "{{ route('modules.performance.skp-matrix.get_internal_assignment_options') }}",
                    type: "GET",
                    data: {
                        skp_work_indicator_id: skpWorkIndicatorId,
                        skp_work_plan_id: skpWorkPlanId
                    },
                },
                columns: [
                { data: 'no', name:'no', render: function (data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
                }},
                { data: null, render: function (data, type, row, meta) {
                  return `<b>${row.name}</b><br>${row.work_id_number}`;
                }},
                { data: null, render: function (data, type, row, meta) {
                  return `${row.title}<br>${row.unit}`;
                }},
                { data: null, render: function (data, type, row, meta) {
                  return `${row.position}`;
                }},
            ]
            });
        }

        $(document).ready(function() {
            $('#work_plan').on('change', function() {
                $.ajax({
                    url: "{{ route('modules.performance.skp-matrix.get_assignments') }}",
                    type: 'POST',
                    data: {
                        _token: $("input[name='_token']").val(),
                        skp_work_plan_id: $("#work_plan").val()
                    },
                    success: function(response) {
                        GenerateTable(response);
                    }
                });
            });

            $('#work_plan').trigger("change");


            $.fn.dataTable.ext.errMode = 'none';
            // Setting datatable defaults
            $.extend( $.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 100,
                }],
                dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
                }
            });
            
            
        });
    </script>
@endpush
