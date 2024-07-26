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
    <div id="my_content">
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div style="float:left!important;">
                    <h4 class="m-0 font-weight-bold text-primary">Pejabat Rerata Bulanan</h4>
                </div>
                {{--                <div style="float:right!important;">--}}
                {{--                    <form class="form-inline">--}}
                {{--                        @csrf--}}
                {{--                        <div class="input-group input-group-sm mr-sm-2">--}}
                {{--                            <button type="button" style="margin-right:10px;" class="btn btn-primary btn-sm rounded" onclick="ApproveSelectedSKP()">--}}
                {{--                                Set Atasan PPK--}}
                {{--                            </button>--}}
                {{--                            <button type="button" class="btn btn-danger btn-sm rounded" onclick="RejectSelectedSKP()">--}}
                {{--                                Kosongkan Atasan PPK--}}
                {{--                            </button>--}}
                {{--                        </div>--}}
                {{--                    </form>--}}
                {{--                </div>--}}
                <div class="clearfix"></div>
            </div>

            <div class="card-body pt-3">
                <div class="table-responsive mb-4 px-1">
                    <div id="form_approval_wrapper" class="dataTables_wrapper no-footer">
                        <table class="table table-hover cell-border pt-2 mb-2 dataTable no-footer" id="approvalTable"
                               role="grid" aria-describedby="form_approval_info">
                            <thead>
                            <tr class="text-left bg-dark text-white" role="row">
                                {{--                                <th class="text-center sorting_disabled" style="width: 5%" rowspan="1"--}}
                                {{--                                    colspan="1"--}}
                                {{--                                    aria-label="">--}}
                                {{--                                    <input type="checkbox" id="checkAll" style="cursor: pointer">--}}
                                {{--                                </th>--}}
                                <th class="sorting_asc" tabindex="0" aria-controls="form_approval" rowspan="1"
                                    colspan="1" aria-label="Pegawai: activate to sort column descending"
                                    aria-sort="ascending" style="width: 30%;">Pegawai
                                </th>
                                @foreach($months as $month)
                                    <th class="sorting" tabindex="0" aria-controls="form_approval" rowspan="1"
                                        colspan="1" aria-label="{{$month['value']}}: activate to sort column ascending"
                                        style="width: 25%;">{{substr($month['name'],0,3)}}
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($assess_staffs as $key => $assess_staff)
                                <tr style="cursor: pointer">
                                    {{--                                    <td class="text-center">--}}
                                    {{--                                        <input type="checkbox" id="checkOne{{$assess_staff->id}}" class="check_one"--}}
                                    {{--                                               style="cursor: pointer" name="checkOne" value="{{$assess_staff->id}}">--}}
                                    {{--                                    </td>--}}
                                    <td onclick="CheckOne('{{$assess_staff->id}}')" class="sorting_1">
                                        <b>{{$assess_staff->name}}</b> <br>
                                        NIP&nbsp;{{$assess_staff->work_id_number}}<br>
                                        <b>{{$assess_staff->work_position}}</b>
                                    </td>
                                    @foreach($months as $month)
                                        <td onclick="CheckOne('{{$assess_staff->id}}')" class="sorting_1">
                                            <b>{{$assess_staff->name_lead}}</b> <br>
                                            NIP&nbsp;{{$assess_staff->work_id_number_lead}}<br>
                                            <b>{{$assess_staff->work_position_lead}}</b>
                                        </td>
                                    @endforeach
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
        function CheckOne(pwuSkpId) {
            $("#checkOne" + pwuSkpId).prop('checked', true)
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
                    if (response.status == 1) {
                        toastr.success(response.message);
                        setTimeout(location.reload.bind(location), 2000);
                    } else {
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
                    if (response.status == 1) {
                        toastr.success(response.message);
                        setTimeout(location.reload.bind(location), 2000);
                    } else {
                        toastr.error(response.message);
                    }
                }
            })
        }

        function ApproveSelectedSKP() {
            let pwuIds = []
            $("input:checkbox[name=checkOne]:checked").each(function () {
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
                    if (response.status == 1) {
                        toastr.success(response.message);
                        setTimeout(location.reload.bind(location), 2000);
                    } else {
                        toastr.error(response.message);
                    }
                }
            })
        }

        function RejectSelectedSKP() {
            let pwuIds = []
            $("input:checkbox[name=checkOne]:checked").each(function () {
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
                    if (response.status == 1) {
                        toastr.success(response.message);
                        setTimeout(location.reload.bind(location), 2000);
                    } else {
                        toastr.error(response.message);
                    }
                }
            })
        }

        $(document).ready(function () {

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
