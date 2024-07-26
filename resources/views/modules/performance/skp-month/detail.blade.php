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
                    Kinerja - <span class="fw-normal">Penilaian Bulanan</span>
                </h4>

                <a href="#page_header"
                   class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                   data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>
            <div class="collapse d-lg-block my-lg-auto ms-lg-auto" id="page_header">
                <div class="d-sm-flex align-items-center mb-3 mb-lg-0 ms-lg-3">
                    <div class="d-inline-flex align-items-center">
                        <a  href="{{route($route.'index')}}"  class="btn btn-primary btn-icon w-32px h-32px rounded-pill">
                            <i class="ph-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
    <div id="my_content">
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center py-3">
                <h4 class="m-0 font-weight-bold text-primary">Data Pegawai</h4>
            </div>
            <div class="card-body pt-2">
                <!-- Header Data Pegawai -->
                <div class="table-responsive">
                    <!-- Data Pegawai -->
                    <table class="table table-striped table-hover table-sm mb-4">
                        <tbody>
                        <tr>
                            <td style="width: 200px">Nama</td>
                            <td style="width: 10px">:</td>
                            <td class=""><b>{{ !empty($personal) ? $personal->name : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($personal) ? $personal->work_id_number : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Pangkat&nbsp;/&nbsp;Gol.</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($personal) ? $personal->workRank->grade_name : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($personal)&&!empty($personal->lastUnitPosition) ? $personal->lastUnitPosition->workPosition->name : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Unit Kerja</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($personal)&&!empty($personal->lastUnitPosition) ? $personal->lastUnitPosition->workUnit->name : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Jenis Pegawai</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($personal) ? $personal->employee_type : '-' }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </div><!-- content -->
        </div>
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div style="float:left!important;">
                    <h4 class="m-0 font-weight-bold text-primary">Penilaian Bulanan</h4>
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
                                    </td>
                                </tr>
                                @foreach($skp_plan->skpActivity as $skp_activity)
                                    <tr>
                                        <td style="max-width: 200px;min-width: 150px">
                                            <p>{{$skp_activity->activity}}</p>
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
                                            <td style="font-size: 0.8em;padding: 3px!important;min-width: 40px">@if(!empty($result))
                                                    {{$result[0]->workload}}
                                                @else
                                                    -
                                                @endif</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="max-width: 200px;vertical-align: top">
                                        <span class="fs-6">{{$skp_plan->title}}</span><br/><br/>
                                    </td>
                                    @foreach($skp_plan->skpActivity as $skp_activity)
                                        <td style="max-width: 200px;min-width: 150px">
                                            <p>{{$skp_activity->activity}}</p>
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
                                            <td style="font-size: 0.8em;padding: 3px!important;min-width: 40px">@if(!empty($result))
                                                    {{$result[0]->workload}}
                                                @else
                                                    -
                                                @endif</td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td style="max-width: 200px">
                                    <span class="fs-6">{{$skp_plan->title}}</span><br/><br/>
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



@push('scripts')
    <script>
        $(document).ready(function () {

            $('.select').select2({
                dropdownParent: $("#modalActivity"),
            });
        });
    </script>
@endpush
