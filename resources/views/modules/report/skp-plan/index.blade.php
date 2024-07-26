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
    <!--{{ var_dump(json_encode($pwuSkps)) }}-->
    <div id="my_content">
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div style="float:left!important;">
                    <h4 class="m-0 font-weight-bold text-primary">Monitoring & Laporan - Rencana Kerja</h4>
                </div>
                <div style="float:right!important;">
                    <form class="form-inline">
                        @csrf
                        <div class="input-group input-group-sm mr-sm-2">
                            <select class="custom-select rounded" id="filterByStatus" style="margin-right:10px;">
                                <option value="">- Filter Status -</option>
                                <option value="Belum Dibuat">Belum Buat SKP</option>
                                <option value="Belum Diajukan">Belum Ajukan SKP</option>
                                <option value="Belum Disetujui">Belum Disetujui</option>
                                <option value="Sudah Disetujui">Sudah Disetujui</option>
                                <option value="Tidak Disetujui">Tidak Disetujui</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="card-body pt-3">
                    
                <div id="form_skp_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <table class="table table-hover cell-border pt-2 mb-2 dataTable no-footer" id="skpTable"
                        role="grid" aria-describedby="form_skp_info">
                        <thead>
                            <tr class="text-left bg-dark text-white" role="row">
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">NIP</th>
                                <th style="width: 25%;">Nama</th>
                                <th style="width: 15%;">Jabatan</th>
                                <th style="width: 20%;">Periode</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 5%" class="sorting_disabled not-export"
                                    aria-label="Aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pwuSkps as $key => $pwuSkp)
                            <tr>
                                <td class="dt-body-center">{{$key + 1}}</td>
                                <td>{{$pwuSkp->work_id_number}}</td>
                                <td>{{$pwuSkp->name}}</td>
                                <td>{{$pwuSkp->title}}</td>
                                <td>{{$pwuSkp->period_description}}</td>
                                <td>
                                    <p>
                                        @if($pwuSkp->status != 'Sudah Disetujui')
                                            <span class="badge bg-danger">{{$pwuSkp->status}}</span>
                                        @else
                                        <span class="badge bg-primary">{{$pwuSkp->status}}</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="px-2 text-center">
                                    @if($pwuSkp->status == 'Sudah Disetujui')        
                                        <button type="button" class="btn btn-sm btn-primary" title="Cetak Rencana Kerja"
                                            onclick="AddPrintSKPModal('{{$pwuSkp->id}}')"><i class="ph ph-printer"></i></button>
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
@endsection
@include('modules.report.skp-plan.modals.print-skp-plan')

@push('scripts')
    <script>

        function GetPrintSkpPlanData(pwuId) {
            $.ajax({
                url: "{{route('modules.report.skp-plan.get_print_skp_plan_data')}}",
                    method: 'POST',
                    data: 
                    {
                        pwu_id: pwuId,
                        _token: $("input[name='_token']").val(),
                    },
                    async: false,
                    success: function (response) {
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
                })
        }

        function AddPrintSKPModal(pwuId) {
            GetPrintSkpPlanData(pwuId);
            $('#modalPrintSKP').modal('show');
        }

        function ClosePrintSKPModal() {
            $('#modalPrintSKP').modal('hide');
        }

        $(document).ready(function() {

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
                        title: "Monitoring Rencana Kerja",
                        text: "PDF",
                        orientation: "landscape",
                        pageSize: "A4",
                        exportOptions: {
                            columns: ":not(.not-export)",
                            stripNewlines: false
                        }
                    },
                    {
                        extend: "excelHtml5",
                        title: "Monitoring Rencana Kerja",
                        exportOptions: {
                            columns: ":not(.not-export)",
                            format: {
                                body: function (data, row, column, node) {
                                    if(column === 1)
                                    {
                                        return "'"+data;
                                    }
                                    else if(column === 5)
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
                skpTable.column(5).search($('#filterByStatus').val()).draw();
            });
        });
    </script>
@endpush
