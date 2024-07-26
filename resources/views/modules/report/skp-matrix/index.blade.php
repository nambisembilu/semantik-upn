@extends('template.master')

@section('title', 'Home Apps')

@section('sidebar')
    @include('template.sidebar')
@endsection

@section('css')
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
    <div id="my_content">
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div style="float:left!important;">
                    <h4 class="m-0 font-weight-bold text-primary">Monitoring & Laporan - Matriks Peran Hasil</h4>
                </div>
                <div style="float:right!important;">
                    <form class="form-inline">
                        @csrf
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="card-body pt-3">
                <select name="work_unit_id" data-placeholder="Pilih..." class="form-control">
                    @foreach($workUnits as $workUnit)
                        <option value="{{$workUnit->id}}">{{$workUnit->name}}</option>
                        @if(count($workUnit->childs)>0&&!empty($workUnit->parent_id))
                            @foreach($workUnit->childs as $work_unit_child1)
                                <option value="{{$work_unit_child1->id}}">{{$workUnit->name}} > {{$work_unit_child1->name}}</option>
                                @if(count($work_unit_child1->childs)>0&&!empty($work_unit_child1->parent_id))
                                    @foreach($work_unit_child1->childs as $work_unit_child2)
                                        <option value="{{$work_unit_child2->id}}">{{$workUnit->name}} > {{$work_unit_child1->name}} > {{$work_unit_child2->name}}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </select> 
                <br><br>   
                <div id="devTableAssignments" class="dataTables_wrapper dt-bootstrap5 no-footer">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        function RefreshMatrixTable(workUnitId) {
            $.ajax({
            url: "{{route('modules.report.skp-matrix.get_skp_matrix_data')}}",
                method: 'POST',
                data: 
                {
                    work_unit_id: workUnitId,
                    _token: $("input[name='_token']").val(),
                },
                async: false,
                success: function (response) {
                    if(response)
                    {
                        GenerateMatrixTable(response);
                    }
                }
            })
        }

        function GenerateMatrixTable(response) {
            $('#devTableAssignments').empty();
            let theadHtml = ``;
            let theadInnerHtml = ``;
            if(response.isJPTInfo == true)
            {
                let theadInnerHtml2 = ``;
                response.personalHeadWorkPlans.skp_work_plans.forEach(function(skp_work_plan) {
                    theadInnerHtml += `<th colspan="${skp_work_plan.skp_work_indicators.length}">${skp_work_plan.title}</th>`;

                    skp_work_plan.skp_work_indicators.forEach(function(skp_work_indicator) {
                        theadInnerHtml2 += `<th>${skp_work_indicator.title}</th>`;
                    });
                });

                let trHtml2 = `<tr class="text-left bg-dark text-white" role="row">
                                        ${theadInnerHtml2}
                                </tr>`;
                theadHtml += `<thead>
                                    <tr class="text-left bg-dark text-white" role="row">
                                        <th rowspan="2" style="width: 5%;">No</th>
                                        <th rowspan="2" style="width: 10%;">${response.personalHead}</th>
                                        ${theadInnerHtml}
                                    </tr>
                                    ${trHtml2}
                                </thead>`;
            }
            else if(response.isJPTInfo == false)
            {
                response.personalHeadWorkPlans.skp_work_plans.forEach(function(skp_work_plan) {
                    theadInnerHtml += `<th>${skp_work_plan.title}</th>`;
                });

                theadHtml += `<thead>
                                    <tr class="text-left bg-dark text-white" role="row">
                                        <th rowspan="2" style="width: 5%;">No</th>
                                        <th rowspan="2" style="width: 10%;">${response.personalHead}</th>
                                        ${theadInnerHtml}
                                    </tr>
                                </thead>`;
            }
            
            let tableHtml = ``;
            let trBodyHtml = ``;
            let trBodyCounter = 1;
            response.personalWorkPlanInfos.forEach(function(personalWorkPlanInfo) {

                let tdBodyHtml = ``;
                personalWorkPlanInfo.personalWorkPlans.forEach(function(personalWorkPlan) {
                    if(personalWorkPlan && personalWorkPlan.length > 0)
                    {
                        let liTdBodyHtml = ``;
                        personalWorkPlan.forEach(function(personalWorkPlanItem) {
                            liTdBodyHtml += `<li>${personalWorkPlanItem.title}</li>`;
                        });
                        tdBodyHtml += `<td>
                                <ul>
                                    ${liTdBodyHtml}
                                </ul>
                            </td>`;
                    }
                    else
                    {
                        tdBodyHtml += `<td></td>`;
                    }
                });

                trBodyHtml += `<tr>
                                <td>${trBodyCounter}</td>
                                <td>${personalWorkPlanInfo.personalInfo}</td>
                                ${tdBodyHtml}
                                </tr>`;
                trBodyCounter++;
                
            });
            let tbodyHtml = `<tbody>
                            ${trBodyHtml}
                            </tbody>`;

                tableHtml = `
            <table class="table table-hover cell-border pt-2 mb-2 dataTable no-footer" id="skpTable"
                        role="grid" aria-describedby="form_skp_info">
                ${theadHtml}
                ${tbodyHtml}
            </table>
            `;

            $('#devTableAssignments').append(`${tableHtml}`);
        }

        $(document).ready(function() {

            RefreshMatrixTable($("select[name='work_unit_id']").val());

            $("select[name='work_unit_id']").on('change', function () {
                RefreshMatrixTable($("select[name='work_unit_id']").val());
            });

        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            dom: '<"datatable-header justify-content-start"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        }); 

            let skpTable = $('#skpTable').DataTable({
                buttons: {            
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons : [
                    {
                        extend: "pdf",
                        title: "Monitoring Matriks Peran Hasil",
                        text: "PDF",
                        orientation: "landscape",
                        pageSize: "A4",
                        exportOptions: {
                            columns: ":not(.not-export)",
                            stripNewlines: false,
                        }
                    },
                    {
                        extend: "excelHtml5",
                        title: "Monitoring Matriks Peran Hasil",
                        exportOptions: {
                            columns: ":not(.not-export)"
                        }
                    }
                ]
                },
                initComplete: function () {
                    var btns = $(".buttons-html5");
                    btns.addClass("btn btn-primary btn-sm px-3 mb-1 mx-1");
                    btns.removeClass("buttons-html5");
                },
            });
        });
    </script>
@endpush
