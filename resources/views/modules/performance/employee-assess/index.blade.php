@extends('template.master')

@section('title', 'Home Apps')

@section('sidebar')
    @include('template.sidebar')
@endsection

@push('styles')
    <style>
        .select2-drop li {
            white-space: pre-line;
        }
    </style>
@endpush

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
                    <h4 class="m-0 font-weight-bold text-primary">Pejabat Penilai Kerja</h4>
                </div>
                <div style="float:right!important;">
                    @if(session('role_name')!='JAJF')
                        <form class="form-inline">
                            @csrf
                            <div class="input-group input-group-sm mr-sm-2">
                                <button type="button" class="btn btn-info btn-sm rounded me-2" data-bs-toggle="modal" data-bs-target="#modalLead">
                                    Set Atasan PPK
                                </button>
                                <button type="button" class="btn btn-danger btn-sm rounded" data-bs-toggle="modal" data-bs-target="#modalClearLead">
                                    Kosongkan Atasan PPK
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="card-body pt-3">
                <div class="table-responsive mb-4 px-1">
                    <div id="form_approval_wrapper" class="dataTables_wrapper no-footer">
                        <table class="table table-hover cell-border pt-2 mb-2 dataTable no-footer" id="approvalTable"
                               role="grid" aria-describedby="form_approval_info">
                            <thead>
                            <tr class="text-left bg-dark text-white" role="row">
                                @if(session('role_name')!='JAJF')
                                    <th class="text-center sorting_disabled" style="width: 5%" rowspan="1"
                                        colspan="1"
                                        aria-label="">
                                        <input type="checkbox" id="checkAll" style="cursor: pointer">
                                    </th>
                                @endif
                                <th style="width: 5%;" class="sorting" tabindex="0"
                                    aria-controls="form_approval" rowspan="1" colspan="1"
                                    aria-label="No: activate to sort column ascending">No
                                </th>
                                <th class="sorting_asc" tabindex="0" aria-controls="form_approval" rowspan="1"
                                    colspan="1" aria-label="Pegawai: activate to sort column descending"
                                    aria-sort="ascending" style="width: 30%;">Pegawai Yang Dinilai
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="form_approval" rowspan="1"
                                    colspan="1" aria-label="PPK: activate to sort column ascending"
                                    style="width: 25%;">Pejabat Penilai Kerja
                                </th>
                                <th class="sorting" tabindex="0" aria-controls="form_approval" rowspan="1"
                                    colspan="1" aria-label="AtasanPPK: activate to sort column ascending"
                                    style="width: 20%;">Atasan Pejabat Penilai Kerja
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($assess_staffs as $key => $assess_staff)
                                <tr style="cursor: pointer">
                                    @if(session('role_name')!='JAJF')
                                        <td class="text-center">
                                            <input type="checkbox" id="checkOne{{$assess_staff->id}}" class="check_one"
                                                   style="cursor: pointer" name="checkOne" value="{{$assess_staff->id}}">
                                        </td>
                                    @endif
                                    <td class="dt-body-center" onclick="CheckOne('{{$assess_staff->id}}')">{{$key + 1}}</td>
                                    <td onclick="CheckOne('{{$assess_staff->id}}')" class="sorting_1">
                                        <b>{{$assess_staff->name}}</b> <br>
                                        NIP&nbsp;{{$assess_staff->work_id_number}}<br>
                                        <b>{{$assess_staff->work_position}}</b><br>
                                        {{$current_period->description}}
                                    </td>
                                    <td onclick="CheckOne('{{$assess_staff->id}}')" class="sorting_1">
                                        @if(!empty($assess_staff->name_lead))
                                            <b>{{$assess_staff->name_lead}}</b> <br>
                                            NIP&nbsp;{{$assess_staff->work_id_number_lead}}<br>
                                            <b>{{$assess_staff->work_position_lead}}</b><br>
                                            {{$current_period->description}}
                                        @else
                                            <span class="text-danger">Belum belum dipilih</span>
                                        @endif
                                    </td>
                                    <td onclick="CheckOne('{{$assess_staff->id}}')" class="sorting_1">
                                        @if(!empty($assess_staff->name_lead_lead))
                                            <b>{{$assess_staff->name_lead_lead}}</b> <br>
                                            NIP&nbsp;{{$assess_staff->work_id_number_lead_lead}}<br>
                                            <b>{{$assess_staff->work_position_lead_lead}}</b><br>
                                            {{$current_period->description}}
                                        @else
                                            <span class="text-danger">Belum belum dipilih</span>
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
@endsection


@push('modals')
    <div class="modal fade" id="modalLead" aria-labelledby="modalLeadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modalLead-content" class="modal-content">
                <form id="form-ppk" action="{{route('modules.performance.employee-assess.saveLead')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pejabat Penilai Kerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="pwu_parent_id" class="col-form-label">Nama PPK:</label>
                            <select class="form-control select" name="pwu_parent_id">
                                @foreach($assess_staffs as $assess_staff)
                                    <option value="{{$assess_staff->id}}">{{$assess_staff->work_id_number}} - {{$assess_staff->name}} [br]
                                        {{$assess_staff->work_position}} [br]
                                        {{$assess_staff->period_desc}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="pwu_ids" name="pwu_ids">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalClearLead" aria-labelledby="modalClearLeadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modalLead-content" class="modal-content">
                <form id="form-clear-ppk" action="{{route('modules.performance.employee-assess.clearLead')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pejabat Penilai Kerja</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>Apakah anda yakin menghapus PPK ini?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="pwu_clear_ids" name="pwu_ids">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush


@push('scripts')
    <script>
        function CheckOne(id) {
            if ($("#checkOne" + id).is(':checked')) {
                $("#checkOne" + id).prop('checked', false);
            } else {
                $("#checkOne" + id).prop('checked', true);
            }

        }


        $(document).ready(function () {

            $('.select').select2({
                dropdownParent: $("#modalLead"),
                templateResult: templateResult,
                templateSelection: templateSelection
            });

            function templateResult(item, container) {
                if (item.text != '') {
                    var selectionText = item.text.split("[br]");
                    // replace the placeholder with the break-tag and put it into an jquery object
                    return $('<span>' + selectionText[0] + "<br/>" + selectionText[1] + "<br/>" + selectionText[2] + '</span>');
                }
            }

            function templateSelection(item, container) {
                if (item.text != '') {
                    var selectionText = item.text.split("[br]");
                    // replace the placeholder with the break-tag and put it into an jquery object
                    return selectionText[0];
                }
            }

            $('#form-ppk').submit(function (e) {
                e.preventDefault();
                let pwuIds = []
                $("input:checkbox[name=checkOne]:checked").each(function () {
                    pwuIds.push($(this).val());
                });
                $('#pwu_ids').val(pwuIds);
                console.log(pwuIds);
                $(this).unbind('submit').submit();
            });
            $('#form-clear-ppk').submit(function (e) {
                e.preventDefault();
                let pwuIds = []
                $("input:checkbox[name=checkOne]:checked").each(function () {
                    pwuIds.push($(this).val());
                });
                $('#pwu_clear_ids').val(pwuIds);
                console.log(pwuIds);
                $(this).unbind('submit').submit();
            });

            $.fn.dataTable.ext.errMode = 'none';
            // Setting datatable defaults
            $.extend($.fn.dataTable.defaults, {
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
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': document.dir == "rtl" ? '&larr;' : '&rarr;',
                        'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'
                    }
                }
            });

            let approvalTable = $('#approvalTable').DataTable({
                "responsive": true,
                "paging": true,
            });

            $('#checkAll').click(function () {
                $('input:checkbox').prop('checked', this.checked);
            });

            $('#filterByStatus').on('change', function () {
                approvalTable.search($('#filterByStatus').val()).draw();
            });
        });
    </script>
@endpush
