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
    <!--{{ var_dump(json_encode($pwuSkps)) }}-->
    <div id="my_content">
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div style="float:left!important;">
                    <h4 class="m-0 font-weight-bold text-primary">Evaluasi SKP</h4>
                </div>
                <form class="form-inline">
                @csrf
                    <div style="float:right!important;">
                        <div class="input-group input-group-sm mr-sm-2">
                            <select class="custom-select rounded" id="filterByStatus" style="margin-right:10px;">
                                <option value="">- Filter Status -</option>
                                <option value="Belum Dibuat">Belum Buat</option>
                                <option value="Belum Diajukan">Belum Ajukan Realisasi</option>
                                <option value="Belum Dievaluasi">Belum Dievaluasi</option>
                                <option value="Sudah Dievaluasi">Sudah Dievaluasi</option>
                            </select>
                        </div>
                    </div>
                    <div style="float:right!important;">
                        <div class="input-group input-group-sm mr-sm-2">
                            <select class="custom-select rounded" id="filterByPredicate" style="margin-right:10px;">
                                <option value="">- Filter Predikat -</option>
                                <option value="Sangat Baik">Sangat Baik</option>
                                <option value="Baik">Baik</option>
                                <option value="Butuh Perbaikan">Butuh Perbaikan</option>
                                <option value="Kurang/Miss Conduct">Kurang/Miss Conduct</option>
                                <option value="Sangat Kurang">Sangat Kurang</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="clearfix"></div>
            </div>

            <div class="card-body pt-3">
                    <div class="table-responsive mb-4 px-1">
                        <div id="form_evaluation_wrapper" class="dataTables_wrapper no-footer">
                            <table class="table table-hover cell-border pt-2 mb-2 dataTable no-footer" id="evaluationTable"
                                role="grid" aria-describedby="form_evaluation_info">
                                <thead>
                                    <tr class="text-left bg-dark text-white" role="row">
                                        <th style="width: 5%;" class="sorting" tabindex="0"
                                            aria-controls="form_evaluation" rowspan="1" colspan="1"
                                            aria-label="No: activate to sort column ascending">No</th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="form_evaluation" rowspan="1"
                                        colspan="1" aria-label="NIP: activate to sort column descending"
                                        aria-sort="ascending" style="width: 15%;">NIP</th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="form_evaluation" rowspan="1"
                                            colspan="1" aria-label="Nama: activate to sort column descending"
                                            aria-sort="ascending" style="width: 25%;">Nama</th>
                                        <th class="sorting" tabindex="0" aria-controls="form_evaluation" rowspan="1"
                                            colspan="1" aria-label="Jabatan: activate to sort column ascending"
                                            style="width: 15%;">Jabatan</th>
                                        <th class="sorting" tabindex="0" aria-controls="form_evaluation" rowspan="1"
                                            colspan="1" aria-label="Status: activate to sort column ascending"
                                            style="width: 15%;">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="form_evaluation" rowspan="1"
                                        colspan="1" aria-label="Status: activate to sort column ascending"
                                        style="width: 10%;">Predikat</th>
                                        <th style="width: 15%" class="sorting_disabled not-export" rowspan="1" colspan="1"
                                            aria-label="Aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pwuSkps as $key => $pwuSkp)
                                    <tr style="cursor: pointer">
                                        <td class="dt-body-center">{{$key + 1}}</td>
                                        <td>{{$pwuSkp->work_id_number}}</td>
                                        <td>{{$pwuSkp->name}}</td>
                                        <td>{{$pwuSkp->title}}</td>
                                        <td>
                                            <p>
                                                @if($pwuSkp->status != 'Sudah Dievaluasi')
                                                    <span class="badge bg-danger">{{$pwuSkp->status}}</span>
                                                @else
                                                <span class="badge bg-primary">{{$pwuSkp->status}}</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td>{{$pwuSkp->performance_predicate}}</td>
                                        <td class="px-2 text-center">
                                            @if($pwuSkp->status == 'Sudah Dievaluasi')        
                                            <button type="button" class="btn btn-sm btn-primary" title="Cetak Evaluasi Kerja"
                                                    onclick="AddPrintEvaluationSKPModal('{{$pwuSkp->id}}')"><i class="ph ph-printer"></i></button>
                                            <button type="button" class="btn btn-sm btn-primary" title="Cetak Doc Evaluasi Kerja"
                                                    onclick="AddPrintDocEvaluationSKPModal('{{$pwuSkp->id}}')"><i class="ph ph-printer"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    @include('modules.report.skp-evaluation.modals.print-evaluation-skp')
    @include('modules.report.skp-evaluation.modals.print-doc-evaluation-skp')
@endsection

@push('scripts')
    <script>

    function GetPrintSkpPEvaluationData(pwuId, isDoc) {
        $.ajax({
            url: "{{route('modules.report.skp-evaluation.get_print_skp_evaluation_data')}}",
                method: 'POST',
                data: 
                {
                    pwu_id: pwuId,
                    _token: $("input[name='_token']").val(),
                },
                async: false,
                success: function (response) {
                    if(isDoc)
                    {
                        $('#doc_pwu_id').val(pwuId);
                        $('#date_setting_employee').val('');
                        $('#date_setting_officer').val('');
                        $('#doc_employee_name').val('');
                        $('#doc_employee_nip').val('');
                        $('#doc_employee_rank').val('');
                        $('#doc_employee_position').val('');
                        $('#doc_employee_work_unit').val('');
                        $('#doc_asessor_name').val('');
                        $('#doc_asessor_nip').val('');
                        $('#doc_asessor_rank').val('');
                        $('#doc_asessor_position').val('');
                        $('#doc_asessor_work_unit').val('');
                        $('#doc_upper_asessor_name').val('');
                        $('#doc_upper_asessor_nip').val('');
                        $('#doc_upper_asessor_rank').val('');
                        $('#doc_upper_asessor_position').val('');
                        $('#doc_upper_asessor_work_unit').val('');

                        if(response)
                        {
                            $('#date_setting_employee').val(response.dateSetting);
                            $('#date_setting_officer').val(response.dateSetting);
                            $('#doc_employee_name').val(response.personalWorkUnit ? response.personalWorkUnit.personal.name : '-');
                            $('#doc_employee_nip').val(response.personalWorkUnit ? response.personalWorkUnit.personal.work_id_number : '-');
                            $('#doc_employee_rank').val(response.personalGrade);
                            $('#doc_employee_position').val(response.personalWorkUnit ? response.personalWorkUnit.work_position.name : '-');
                            $('#doc_employee_work_unit').val(response.personalWorkUnit ? response.personalWorkUnit.root_work_unit.name : '-');
                            $('#doc_asessor_name').val(response.officerWorkUnit ? response.officerWorkUnit.personal.name : '-');
                            $('#doc_asessor_nip').val(response.officerWorkUnit ? response.officerWorkUnit.personal.work_id_number : '-');
                            $('#doc_asessor_rank').val(response.officerGrade);
                            $('#doc_asessor_position').val(response.officerWorkUnit ? response.officerWorkUnit.work_position.name : '-');
                            $('#doc_asessor_work_unit').val(response.officerWorkUnit ? response.officerWorkUnit.root_work_unit.name : '-');
                            $('#doc_upper_asessor_name').val(response.upperOfficerWorkUnit ? response.upperOfficerWorkUnit.personal.name : '-');
                            $('#doc_upper_asessor_nip').val(response.upperOfficerWorkUnit ? response.upperOfficerWorkUnit.personal.work_id_number : '-');
                            $('#doc_upper_asessor_rank').val(response.upperOfficerGrade);
                            $('#doc_upper_asessor_position').val(response.upperOfficerWorkUnit ? response.upperOfficerWorkUnit.work_position.name : '-');
                            $('#doc_upper_asessor_work_unit').val(response.upperOfficerWorkUnit ? response.upperOfficerWorkUnit.root_work_unit.name : '-');
                        }
                    }
                    else
                    {
                        $('#pwu_id').val(pwuId);
                        $('#date_setting').val('');
                        $('#employee_name').val('');
                        $('#employee_nip').val('');
                        $('#employee_rank').val('');
                        $('#employee_position').val('');
                        $('#employee_work_unit').val('');
                        $('#asessor_name').val('');
                        $('#asessor_nip').val('');
                        $('#asessor_rank').val('');
                        $('#asessor_position').val('');
                        $('#asessor_work_unit').val('');

                        if(response)
                        {
                            $('#date_setting').val(response.dateSetting);
                            $('#employee_name').val(response.personalWorkUnit ? response.personalWorkUnit.personal.name : '-');
                            $('#employee_nip').val(response.personalWorkUnit ? response.personalWorkUnit.personal.work_id_number : '-');
                            $('#employee_rank').val(response.personalGrade);
                            $('#employee_position').val(response.personalWorkUnit ? response.personalWorkUnit.work_position.name : '-');
                            $('#employee_work_unit').val(response.personalWorkUnit ? response.personalWorkUnit.root_work_unit.name : '-');
                            $('#asessor_name').val(response.officerWorkUnit ? response.officerWorkUnit.personal.name : '-');
                            $('#asessor_nip').val(response.officerWorkUnit ? response.officerWorkUnit.personal.work_id_number : '-');
                            $('#asessor_rank').val(response.officerGrade);
                            $('#asessor_position').val(response.officerWorkUnit ? response.officerWorkUnit.work_position.name : '-');
                            $('#asessor_work_unit').val(response.officerWorkUnit ? response.officerWorkUnit.root_work_unit.name : '-');
                        }
                    }
                }
            })
        }    
    function AddPrintEvaluationSKPModal(pwuId) {
        GetPrintSkpPEvaluationData(pwuId, false);
        $('#modalPrintEvaluationSKP').modal('show');
    }

    function ClosePrintEvaluationSKPModal() {
        $('#modalPrintEvaluationSKP').modal('hide');
    }

    function AddPrintDocEvaluationSKPModal(pwuId) {
        GetPrintSkpPEvaluationData(pwuId, true);
        $('#modalPrintDocEvaluationSKP').modal('show');
    }

    function ClosePrintDocEvaluationSKPModal() {
        $('#modalPrintDocEvaluationSKP').modal('hide');
    }

        $(document).ready(function() {

            $.fn.dataTable.ext.errMode = 'none';
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

            let evaluationTable = $('#evaluationTable').DataTable({
                buttons: {            
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons : [
                    {
                        extend: "pdf",
                        title: "Monitoring Evaluasi Kerja",
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
                        title: "Monitoring Evaluasi Kerja",
                        exportOptions: {
                            columns: ":not(.not-export)",
                            format: {
                                body: function (data, row, column, node) {
                                    if(column === 1)
                                    {
                                        return "'"+data;
                                    }
                                    else if(column === 4)
                                    {
                                        let resultData = data.replace(/(&nbsp;|<([^>]+)>)/ig, "");
                                        resultData = resultData.replace(/\s/g,'');
                                        return resultData.replace(/([A-Z])/g, ' $1').trim();
                                    }
                                    else
                                    {
                                        return data;
                                    }
                                }
                            }
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

            $('#filterByStatus').on('change', function() {
                evaluationTable.column(4).search($('#filterByStatus').val()).draw();
            });

            $('#filterByPredicate').on('change', function() {
                evaluationTable.column(5).search($('#filterByPredicate').val()).draw();
            });
        });
    </script>
@endpush
