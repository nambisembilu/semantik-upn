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
                    <h4 class="m-0 font-weight-bold text-primary">Persetujuan SKP</h4>
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
                            <button type="button" style="margin-right:10px;" class="btn btn-primary btn-sm rounded" onclick="ApproveSelectedSKP()">
                                Setujui Terpilih
                            </button>
                            <button type="button" class="btn btn-danger btn-sm rounded" onclick="RejectSelectedSKP()">
                                Tolak Terpilih
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
                                            aria-controls="form_approval" rowspan="1" colspan="1"
                                            aria-label="No: activate to sort column ascending">No</th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="form_approval" rowspan="1"
                                            colspan="1" aria-label="Nama: activate to sort column descending"
                                            aria-sort="ascending" style="width: 30%;">Nama</th>
                                        <th class="sorting" tabindex="0" aria-controls="form_approval" rowspan="1"
                                            colspan="1" aria-label="Jabatan: activate to sort column ascending"
                                            style="width: 25%;">Jabatan</th>
                                        <th class="sorting" tabindex="0" aria-controls="form_approval" rowspan="1"
                                            colspan="1" aria-label="Status: activate to sort column ascending"
                                            style="width: 20%;">Status</th>
                                        <th style="width: 15%" class="sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pwuSkps as $key => $pwuSkp)
                                    <tr style="cursor: pointer">
                                        <td class="text-center">
                                            <input type="checkbox" id="checkOne{{$pwuSkp->id}}" class="check_one"
                                                style="cursor: pointer" name="checkOne" value="{{$pwuSkp->id}}">
                                        </td>
                                        <td class="dt-body-center" onclick="CheckOne('{{$pwuSkp->id}}')">{{$key + 1}}</td>
                                        <td onclick="CheckOne('{{$pwuSkp->id}}')" class="sorting_1">
                                            {{$pwuSkp->name}} <br>
                                            NIP&nbsp;{{$pwuSkp->work_id_number}}</td>
                                        <td onclick="CheckOne('{{$pwuSkp->id}}')">
                                            {{$pwuSkp->title}}<br>
                                            {{$pwuSkp->period_description}} </td>
                                        <td onclick="CheckOne('{{$pwuSkp->id}}')">
                                            <p>
                                                @if($pwuSkp->status != 'Sudah Disetujui')
                                                    <span class="badge bg-danger">{{$pwuSkp->status}}</span>
                                                @else
                                                <span class="badge bg-primary">{{$pwuSkp->status}}</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="px-2 text-center">
                                            @if($pwuSkp->status != 'Belum Dibuat')
                                            <a href="{{route('modules.performance.skp-approval.detail',$pwuSkp->id)}}">
                                                <button type="button" class="btn btn-sm btn-success" title="Lihat SKP"
                                                ><i class="ph ph-magnifying-glass"></i></button></a>
                                            @endif
                                            @if($pwuSkp->status == 'Belum Disetujui' || $pwuSkp->status == 'Tidak Disetujui')        
                                                <button type="button" class="btn btn-sm btn-primary" title="Setujui"
                                                    onclick="ApproveSKP('{{$pwuSkp->id}}')"><i class="ph ph-check"></i></button>
                                            @endif
                                            @if($pwuSkp->status == 'Belum Disetujui' || $pwuSkp->status == 'Sudah Disetujui')
                                                <button type="button" class="btn btn-sm btn-danger" title="Tolak"
                                                    onclick="RejectSKP('{{$pwuSkp->id}}')"><i class="ph ph-prohibit"></i></button>
                                            @endif
                                            @if(!empty($pwuSkp) && !empty($pwuSkp->skp_id))
                                            <a href="{{route('modules.performance.skp-approval.edit_behavior_note', $pwuSkp->id) }}">
                                                <button type="button" class="btn btn-sm btn-primary" title="Ubah Ekpektasi Pemimpin"
                                                ><i class="ph ph-pencil-simple"></i></button></a>
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

@push('scripts')
    <script>
        function CheckOne(pwuSkpId)
        {
            $("#checkOne"+pwuSkpId).prop('checked', true)
        }

        function ApproveSKP(pwuSkpId) {
            $.ajax({
                url: "{{route('modules.performance.skp-approval.approve_skp')}}",
                    method: 'POST',
                    data: 
                    {
                        _token: $("input[name='_token']").val(),
                        pwu_id: pwuSkpId,
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

        function RejectSKP(pwuSkpId) {
            $.ajax({
                url: "{{route('modules.performance.skp-approval.reject_skp')}}",
                    method: 'POST',
                    data: 
                    {
                        _token: $("input[name='_token']").val(),
                        pwu_id: pwuSkpId,
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

        function ApproveSelectedSKP(){
            let pwuIds = []
            $("input:checkbox[name=checkOne]:checked").each(function(){
                pwuIds.push($(this).val());
            });
            $.ajax({
                url: "{{route('modules.performance.skp-approval.approve_bulk_skp')}}",
                    method: 'POST',
                    data: 
                    {
                        _token: $("input[name='_token']").val(),
                        pwu_ids: pwuIds,
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

        function RejectSelectedSKP() {
            let pwuIds = []
            $("input:checkbox[name=checkOne]:checked").each(function(){
                pwuIds.push($(this).val());
            });
            $.ajax({
                url: "{{route('modules.performance.skp-approval.reject_bulk_skp')}}",
                    method: 'POST',
                    data: 
                    {
                        _token: $("input[name='_token']").val(),
                        pwu_ids: pwuIds,
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

            $('#filterByStatus').on('change', function() {
                approvalTable.search($('#filterByStatus').val()).draw();
            });
        });
    </script>
@endpush
