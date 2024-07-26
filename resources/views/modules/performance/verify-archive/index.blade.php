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
                    <h4 class="m-0 font-weight-bold text-primary">{{$menu_title}}</h4>
                </div>
                <div style="float:right!important;">
                    <form class="form-inline">
                        @csrf
                        <div class="input-group input-group-sm mr-sm-2">
                            <button type="button" class="btn btn-success btn-sm rounded me-2" data-bs-toggle="modal" data-bs-target="#modalApprove">
                                Setujui
                            </button>
                            <button type="button" class="btn btn-danger btn-sm rounded" data-bs-toggle="modal" data-bs-target="#modalRevision">
                                Revisi
                            </button>
                        </div>
                    </form>
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
                                <th class="text-center sorting_disabled" style="width: 5%" rowspan="1"
                                    colspan="1"
                                    aria-label="">
                                    <input type="checkbox" id="checkAll" style="cursor: pointer">
                                </th>
                                <th style="width: 5%;" class="sorting" tabindex="0"
                                    aria-label="No: activate to sort column ascending">No
                                </th>
                                <th class="sorting_asc" tabindex="0"
                                    colspan="1" aria-label="Pegawai: activate to sort column descending"
                                    aria-sort="ascending" style="width: 30%;">Nama
                                </th>
                                <th class="sorting" tabindex="0"
                                    colspan="1" aria-label="PPK: activate to sort column ascending"
                                    style="width: 25%;">
                                    @if($type=='plan')
                                        File Rencana SKP
                                    @elseif($type=='eval')
                                        File Evaluasi SKP
                                    @elseif($type=='doc')
                                        File Dokumen Evaluasi SKP
                                    @endif
                                </th>

                                <th class="sorting" tabindex="0" aria-controls="form_approval" rowspan="1"
                                    colspan="1" aria-label="PPK: activate to sort column ascending"
                                    style="width: 25%;">Status
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($skp_archives as $key => $skp_archive)
                                <tr style="cursor: pointer">
                                    <td class="text-center">
                                        @if($type=='plan')
                                            @if($skp_archive->plan_status!='2')
                                                <input type="checkbox" id="checkOne{{$skp_archive->id}}" class="check_one"
                                                       style="cursor: pointer" name="checkOne" value="{{$skp_archive->id}}">
                                            @endif
                                        @elseif($type=='eval')
                                            @if($skp_archive->eval_status!='2')
                                                <input type="checkbox" id="checkOne{{$skp_archive->id}}" class="check_one"
                                                       style="cursor: pointer" name="checkOne" value="{{$skp_archive->id}}">
                                            @endif
                                        @elseif($type=='doc')
                                            @if($skp_archive->doc_eval_status!='2')
                                                <input type="checkbox" id="checkOne{{$skp_archive->id}}" class="check_one"
                                                       style="cursor: pointer" name="checkOne" value="{{$skp_archive->id}}">
                                            @endif
                                        @endif
                                    </td>
                                    <td class="dt-body-center" onclick="CheckOne('{{$skp_archive->id}}')">{{$key + 1}}</td>
                                    <td onclick="CheckOne('{{$skp_archive->id}}')" class="sorting_1">
                                        <b>{{$skp_archive->personal->name}}</b> <br>
                                        NIP&nbsp;{{$skp_archive->personal->work_id_number}}
                                    </td>
                                    <td class="sorting_1">
                                        @if($type=='plan')
                                            @if(!empty($skp_archive->plan_file))
                                                <a target="_blank" href="{{asset(Illuminate\Support\Facades\Storage::disk('public')->url($skp_archive->plan_file))}}" class="btn btn-sm btn-danger fs-9 me-2"><i class="icon-file-pdf me-2"></i> Dokumen</a>
                                            @endif
                                        @elseif($type=='eval')
                                            @if(!empty($skp_archive->eval_file))
                                                <a target="_blank" href="{{asset(Illuminate\Support\Facades\Storage::disk('public')->url($skp_archive->eval_file))}}" class="btn btn-sm btn-danger fs-9 me-2"><i class="icon-file-pdf me-2"></i> Dokumen</a>
                                            @endif
                                        @elseif($type=='doc')
                                            @if(!empty($skp_archive->doc_eval_file))
                                                <a target="_blank" href="{{asset(Illuminate\Support\Facades\Storage::disk('public')->url($skp_archive->doc_eval_file))}}" class="btn btn-sm btn-danger fs-9 me-2"><i class="icon-file-pdf me-2"></i> Dokumen</a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($type=='plan')
                                            @if($skp_archive->plan_status=='1')
                                                <span class="badge bg-info">Diajukan</span>
                                            @elseif($skp_archive->plan_status=='2')
                                                <span class="badge bg-success">Sudah disetujui</span>
                                            @elseif($skp_archive->plan_status=='3')
                                                <span class="badge bg-warning">Revisi</span>
                                            @endif
                                        @elseif($type=='eval')
                                            @if($skp_archive->eval_status=='1')
                                                <span class="badge bg-info">Diajukan</span>
                                            @elseif($skp_archive->eval_status=='2')
                                                <span class="badge bg-success">Sudah disetujui</span>
                                            @elseif($skp_archive->eval_status=='3')
                                                <span class="badge bg-warning">Revisi</span>
                                            @endif
                                        @elseif($type=='doc')
                                            @if($skp_archive->doc_eval_status=='1')
                                                <span class="badge bg-info">Diajukan</span>
                                            @elseif($skp_archive->doc_eval_status=='2')
                                                <span class="badge bg-success">Sudah disetujui</span>
                                            @elseif($skp_archive->doc_eval_status=='3')
                                                <span class="badge bg-warning">Revisi</span>
                                            @endif
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
    <div class="modal fade" id="modalApprove" aria-labelledby="modalApproveLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modalApprove-content" class="modal-content">
                <form id="form-approve-archive" action="{{route('modules.performance.verify-archive.changeStatus')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Verifikasi Arsip SKP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>Apakah anda yakin menyetujui arsip ini?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="type" value="{{$type}}">
                        <input type="hidden" id="approve_ids" name="ids">
                        <input type="hidden" name="status" value="2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRevision" aria-labelledby="modalRevisionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modalRevision-content" class="modal-content">
                <form id="form-revision-archive" action="{{route('modules.performance.verify-archive.changeStatus')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Verifikasi Arsip SKP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>Apakah anda yakin revisi arsip ini?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="type" value="{{$type}}">
                        <input type="hidden" name="status" value="3">
                        <input type="hidden" id="revisi_ids" name="ids">
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

            $('#form-approve-archive').submit(function (e) {
                e.preventDefault();
                let ids = []
                $("input:checkbox[name=checkOne]:checked").each(function () {
                    ids.push($(this).val());
                });
                $('#approve_ids').val(ids);
                console.log(ids);
                $(this).unbind('submit').submit();
            });
            $('#form-revision-archive').submit(function (e) {
                e.preventDefault();
                let ids = []
                $("input:checkbox[name=checkOne]:checked").each(function () {
                    ids.push($(this).val());
                });
                $('#revisi_ids').val(ids);
                console.log(ids);
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
