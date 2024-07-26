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
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
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
                    <h4 class="m-0 font-weight-bold text-primary">Rencana Kegiatan</h4>
                </div>
            </div>

            <div class="card-body pt-2">
                <table class="table">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th>Hasil Kerja</th>
                        <th>Kegiatan</th>
                        @foreach($months as $month)
                            <th style="font-size: 0.8em;padding: 8px!important;">{{$month['value']}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($skp_plans as $skp_plan)
                        @if(!empty($skp_plan->skpActivity))
                            @if(count($skp_plan->skpActivity)>1)
                                <tr>
                                    <td style="max-width: 200px;vertical-align: top" rowspan="{{count($skp_plan->skpActivity)+1}}">
                                        <span class="fs-6">{{$skp_plan->title}}</span><br/><br/>
                                        <button class="btn btn-sm btn-success btn-icon" onclick="$('#skp_plan_id').val({{$skp_plan->id}})" data-bs-toggle="modal" data-bs-target="#modalActivity"><i class="ph-plus"></i></button>
                                    </td>
                                </tr>
                                @foreach($skp_plan->skpActivity as $skp_activity)
                                    <tr>
                                        <td style="max-width: 200px;min-width: 150px">
                                            <p>{{$skp_activity->activity}}</p>
                                            <button class="btn btn-sm btn-danger btn-icon" onclick="$('#skp_activity_id').val({{$skp_activity->id}})" data-bs-toggle="modal" data-bs-target="#modalDeleteActivity"><i class="ph-x"></i></button>
                                        </td>
                                        @foreach($months as $month)
                                            @php
                                                $result=\Illuminate\Support\Facades\DB::connection('pgsql-seskom')->select("
                                                select pwal.workload from personal_workload_activity_lines pwal
                                                join personal_workload_activities pwa on pwa.id=pwal.personal_workload_activity_id
                                                join personal_workloads pw on pw.id=pwa.personal_workload_id
                                                join personals p on p.id=pw.personal_id
                                                where p.work_id_number='{$personal->work_id_number}'
                                                and pw.year='{$period->year}'
                                                and pwa.activity_id='{$skp_activity->ref_external_id}'
                                                and pwal.month='{$month['value']}'
                                                ");
                                            @endphp
                                            <td style="font-size: 0.8em;padding: 3px!important;min-width: 40px">@if(!empty($result)) {{$result[0]->workload}} @else - @endif</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td  style="max-width: 200px;vertical-align: top">
                                        <span class="fs-6">{{$skp_plan->title}}</span><br/><br/>
                                        <button class="btn btn-sm btn-success btn-icon" onclick="$('#skp_plan_id').val({{$skp_plan->id}})" data-bs-toggle="modal" data-bs-target="#modalActivity"><i class="ph-plus"></i></button>
                                    </td>
                                    @foreach($skp_plan->skpActivity as $skp_activity)
                                        <td style="max-width: 200px;min-width: 150px">
                                            <p>{{$skp_activity->activity}}</p>
                                            <button class="btn btn-sm btn-danger btn-icon" onclick="$('#skp_activity_id').val({{$skp_activity->id}})" data-bs-toggle="modal" data-bs-target="#modalDeleteActivity"><i class="ph-x"></i></button>
                                        </td>
                                        @foreach($months as $month)
                                            @php
                                                $result=\Illuminate\Support\Facades\DB::connection('pgsql-seskom')->select("
                                                select pwal.workload from personal_workload_activity_lines pwal
                                                join personal_workload_activities pwa on pwa.id=pwal.personal_workload_activity_id
                                                join personal_workloads pw on pw.id=pwa.personal_workload_id
                                                join personals p on p.id=pw.personal_id
                                                where p.work_id_number='{$personal->work_id_number}'
                                                and pw.year='{$period->year}'
                                                and pwa.activity_id='{$skp_activity->ref_external_id}'
                                                and pwal.month='{$month['value']}'
                                                ");
                                            @endphp
                                            <td style="font-size: 0.8em;padding: 3px!important;min-width: 40px">@if(!empty($result)) {{$result[0]->workload}} @else - @endif</td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td style="max-width: 200px">
                                    <span class="fs-6">{{$skp_plan->title}}</span><br/><br/>
                                    <button class="btn btn-sm btn-success btn-icon" onclick="$('#skp_plan_id').val({{$skp_plan->id}})" data-bs-toggle="modal" data-bs-target="#modalActivity"><i class="ph-plus"></i></button>
                                </td>
                                <td style="max-width: 200px;min-width: 150px"></td>
                                @foreach($months as $month)
                                    <td style="font-size: 0.8em;padding: 3px!important;min-width: 40px"><input size="4" type="text" class="form-control form-control-sm"/></td>
                                @endforeach
                            </tr>
                        @endif
                    @empty
                        <td colspan="{{count($months)+2}}" class="text-center text-danger"><b>Data Rencana Hasil Kerja Kosong</b></td>
                    @endforelse
                    </tbody>
                </table>

            </div>
        </div>


    </div>
@endsection


@push('modals')
    <div class="modal fade" id="modalActivity" aria-labelledby="modalActivityLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="modalActivity-content" class="modal-content">
                <form id="form-ppk" action="{{route('modules.performance.skp-activity.addActivity')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="activity" class="col-form-label">Nama Kegiatan:</label>
                            @if(!empty($activities))
                                <select class="form-control select" name="activity">
                                    @foreach($activities as $activity)
                                        <option value="{{$activity->id}}#{{$activity->title}}">{{$activity->title}}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="alert bg-danger text-white">Data aktivitas tidak ditemukan disistem <b>seskom</b></div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="skp_plan_id" name="skp_plan_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        @if(!empty($activities))
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDeleteActivity" aria-labelledby="modalDeleteActivityLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="modalDeleteActivity-content" class="modal-content">
                <form id="form-ppk" action="{{route('modules.performance.skp-activity.deleteActivity')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>Apakah anda yakin menghapus data ini?</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="skp_activity_id" name="skp_activity_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush


@push('scripts')
    <script>
        $(document).ready(function () {

            $('.select').select2({
                dropdownParent: $("#modalActivity"),
            });
        });
    </script>
@endpush
