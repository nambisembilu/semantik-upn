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
                <div style="float:right!important;">
                    <form class="form-inline">
                        @csrf
                        <div class="input-group input-group-sm mr-sm-2">
                            <select class="custom-select rounded" id="filterByStatus" style="margin-right:10px;">
                                <option value="">- Filter Status -</option>
                                <option value="Belum Dibuat">Belum Buat</option>
                                <option value="Belum Diajukan">Belum Ajukan Realisasi</option>
                                <option value="Belum Dievaluasi">Belum Dievaluasi</option>
                                <option value="Sudah Dievaluasi">Sudah Dievaluasi</option>
                            </select>
                        </div>
                    </form>
                </div>
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
                                            colspan="1" aria-label="Nama: activate to sort column descending"
                                            aria-sort="ascending" style="width: 25%;">Nama</th>
                                        <th class="sorting" tabindex="0" aria-controls="form_evaluation" rowspan="1"
                                            colspan="1" aria-label="Jabatan: activate to sort column ascending"
                                            style="width: 20%;">Jabatan</th>
                                        <th class="sorting" tabindex="0" aria-controls="form_evaluation" rowspan="1"
                                            colspan="1" aria-label="Status: activate to sort column ascending"
                                            style="width: 20%;">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="form_evaluation" rowspan="1"
                                        colspan="1" aria-label="Status: activate to sort column ascending"
                                        style="width: 20%;">Predikat</th>
                                        <th style="width: 10%" class="sorting_disabled" rowspan="1" colspan="1"
                                            aria-label="Aksi">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pwuSkps as $key => $pwuSkp)
                                    <tr style="cursor: pointer">
                                        <td class="dt-body-center">{{$key + 1}}</td>
                                        <td class="sorting_1">
                                            {{$pwuSkp->name}} <br>
                                            NIP&nbsp;{{$pwuSkp->work_id_number}}</td>
                                        <td>
                                            {{$pwuSkp->title}}<br>
                                            {{$pwuSkp->period_description}} </td>
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
                                            @if($pwuSkp->status == 'Belum Dievaluasi' || $pwuSkp->status == 'Sudah Dievaluasi')        
                                                <a href="{{route('modules.performance.skp-evaluation.edit_evaluation',$pwuSkp->id)}}"><button type="button" class="btn btn-sm btn-primary" title="Isi Evaluasi"
                                                ><i class="ph ph-pencil-simple"></i></button></a>
                                            @endif
                                            @if($pwuSkp->status == 'Sudah Dievaluasi')        
                                            <button type="button" class="btn btn-sm btn-danger" title="Kembalikan ke Pengaju"
                                                    onclick="RevertToApplymentProcess('{{$pwuSkp->id}}')"><i class="ph ph-prohibit"></i></button>
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

        function RevertToApplymentProcess(realizationId) {
            $.ajax({
                url: "{{route('modules.performance.skp-evaluation.revert_to_applyment_process')}}",
                    method: 'POST',
                    data: 
                    {
                        _token: $("input[name='_token']").val(),
                        realization_id: realizationId,
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

            let evaluationTable = $('#evaluationTable').DataTable({
                "responsive": true,
                "paging": true,
            });

            $('#filterByStatus').on('change', function() {
                evaluationTable.search($('#filterByStatus').val()).draw();
            });
        });
    </script>
@endpush
