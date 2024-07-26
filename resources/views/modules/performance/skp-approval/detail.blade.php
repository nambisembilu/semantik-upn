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

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
<!--{{var_dump(json_encode($attachmentCategories))}}-->
        <div id="my_content">
            <div class="card shadow mb-3">
                <div class="card-header py-3">
                    <div style="float:left!important;">
                        <h4 class="m-0 font-weight-bold text-primary">Sasaran Kinerja Pegawai</h4>
                    </div>
                    <form id="formskp">
                        @csrf
                    </form>
                    <div style="float:right!important;">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                            
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body pt-2">
                    <!-- Status SKP -->
                    <div class="mb-2">
                        @if(!empty($skp))
                            @if($skp->application_status == 'Belum Diajukan')
                            <span class="badge bg-danger">Belum Diajukan</span>
                            @elseif($skp->application_status == 'Belum Disetujui')
                            <span class="badge bg-primary">Sudah Diajukan</span>
                            <span class="badge bg-danger">Belum Disetujui</span>
                            @else
                            <span class="badge bg-primary">Sudah Diajukan</span>
                            <span class="badge bg-primary">Sudah Disetujui</span>
                            @endif
                        @endif    
                    </div>
                    <!-- Header Data Pegawai -->
                    <div class="table-responsive">
                        <!-- Data Pegawai -->
                        <table class="table table-bordered table-hover table-blue table-sm mb-4">
                            <tbody>
                                <tr class="table-primary text-primary mpdf-table-header-gray">
                                    <td class="text-center">No</td>
                                    <td class="text-center" colspan="2">Pegawai yang Dinilai</td>
                                    <td class="text-center">No</td>
                                    <td class="text-center" colspan="2">Pejabat Penilai Kinerja</td>
                                </tr>
                                <tr>
                                    <td class="td-data-no text-center">1.</td>
                                    <td class="td-data-attr">Nama</td>
                                    <td class="td-data-val">{{ !empty($personalWorkUnit) ? $personalWorkUnit->personal->name : '-' }}</td>
                                    <td class="td-data-no text-center">1.</td>
                                    <td class="td-data-attr">Nama</td>
                                    <td class="td-data-val">{{ !empty($officerWorkUnit) ? $officerWorkUnit->personal->name : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2.</td>
                                    <td>NIP</td>
                                    <td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->personal->work_id_number : '-' }}</td>
                                    <td class="text-center">2.</td>
                                    <td>NIP</td>
                                    <td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->personal->work_id_number : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">3.</td>
                                    <td>Pangkat&nbsp;/&nbsp;Gol.</td>
                                    <td>{{ !empty($personalWorkUnit) ? $helper->getGradeValue($personalWorkUnit->personal->employee_type, $personalWorkUnit->personal->workRank->grade_name, $personalWorkUnit->personal->workRank->name) : '-' }}</td>
                                    <td class="text-center">3.</td>
                                    <td>Pangkat&nbsp;/&nbsp;Gol.</td>
                                    <td>{{ !empty($officerWorkUnit) ? $helper->getGradeValue($officerWorkUnit->personal->employee_type, $officerWorkUnit->personal->workRank->grade_name, $officerWorkUnit->personal->workRank->name) : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">4.</td>
                                    <td>Jabatan</td>
                                    <td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->workPosition->name : '-' }}</td>
                                    <td class="text-center">4.</td>
                                    <td>Jabatan</td>
                                    <td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->workPosition->name : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">5.</td>
                                    <td>Unit Kerja</td>
                                    <td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->rootWorkUnit->name : '-' }}</td>
                                    <td class="text-center">5.</td>
                                    <td>Unit Kerja</td>
                                    <td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->rootWorkUnit->name : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Hasil Kerja -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-blue table-sm mb-4">
                            <tbody>
                                <tr class="table-primary text-primary">
                                    <td colspan="3">HASIL KERJA</td>
                                </tr>
                                <!-- Hasil Kerja Utama -->
                                <tr class="table-primary text-primary">
                                    <td colspan="2">A. Utama</td>
                                    <td class="text-left" style="width: 85px">
                                        
                                    </td>
                                </tr>
                                @if(!empty($mainSkp) && count($mainSkp) > 0)
                                    @foreach($mainSkp[0]->skpWorkPlans as $key => $skpWorkPlan)
                                    <!-- Hasil Kerja Utama -->
                                    @if(empty($skpWorkPlan->get_task_from))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td class="text-center" style="width:5%;">
                                            {{$key + 1}}. </td>
                                        <td style="width:80%;">
                                            <!-- <span class="badge badge-pill badge-primary"><small>Cascading</small></span> -->
                                            &nbsp;<i class="fas fa-user-circle" data-toggle="tooltip" data-placement="top"
                                                title=""></i>&nbsp;
                                            {{$skpWorkPlan->title}} (Penugasan Dari : {{$skpWorkPlan->get_task_from}})
                                        </td>
                                        <td class="text-left" style="width:20%;">
                                            
                                        </td>
                                    </tr>
                                    <!-- Indikator Hasil Kerja Utama -->
                                    <tr>
                                        <td rowspan="3">&nbsp;</td>
                                        <td>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</td>
                                        <td class="text-left"></td>
                                    </tr>
                                    @if(empty($skpWorkPlan->get_task_from))
                                    <tr class="text-danger">
                                    @else
                                    <tr>
                                    @endif
                                        <td>
                                            <ul class="mb-0" style="margin-left: -15px">
                                            @foreach($skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
                                                <li>
                                                    {{$skpWorkIndicator->title}} 
                                                </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-left"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    @endforeach
                                @endif
                               

                                <!-- Hasil Kerja Tambahan -->
                                <tr class="table-primary text-primary">
                                    <td colspan="2">B. Tambahan</td>
                                    <td class="text-left">
                                    </td>
                                </tr>
                                @if(!empty($additionalSkp) && count($additionalSkp) > 0)
                                    @foreach($additionalSkp[0]->skpWorkPlans as $key => $skpWorkPlan)
                                    <!-- Hasil Kerja Utama -->
                                    <tr class="">
                                        <td class="text-center" style="width:5%;">
                                            {{$key + 1}}. </td>
                                        <td style="width:80%;">
                                            <!-- <span class="badge badge-pill badge-primary"><small>Cascading</small></span> -->
                                            &nbsp;<i class="fas fa-user-circle" data-toggle="tooltip" data-placement="top"
                                                title=""
                                                data-original-title="Direktorat Sumber Daya Manusia"></i>&nbsp;
                                            {{$skpWorkPlan->title}} (Penugasan Dari : {{$skpWorkPlan->get_task_from}})
                                        </td>
                                        <td class="text-left" style="width:20%;">
                                           
                                        </td>
                                    </tr>
                                    <!-- Indikator Hasil Kerja Utama -->
                                    <tr>
                                        <td rowspan="3">&nbsp;</td>
                                        <td>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</td>
                                        <td class="text-left"></td>
                                    </tr>
                                    <tr class="">
                                        <td>
                                            <ul class="mb-0" style="margin-left: -15px">
                                            @foreach($skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
                                                <li>
                                                    {{$skpWorkIndicator->title}} 
                                                </li>
                                            @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-left"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    @endforeach
                                @endif    
                                <tr>
                                    <td style="width: 50px">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <!-- Perilaku Kerja -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-blue table-sm mb-4">
                            <tbody>
                                <tr class="table-primary text-primary">
                                    <td colspan="3">PERILAKU KERJA</td>
                                </tr>
                                @if(!empty($skpBehaviors) && count($skpBehaviors) > 0)
                                    @foreach($skpBehaviors as $key => $skpBehavior)
                                    <tr>
                                        <td style="width: 50px" class="text-center align-top">
                                        {{$key + 1}}.
                                        </td>
                                        <td class="align-top">
                                            {{$skpBehavior->behaviorCategory->name}} <br>
                                            <ul class="mb-2 pl-4">
                                                @foreach($skpBehavior->behaviorCategory->behaviorCriterias as $key => $behaviorCriteria)
                                                <li>{{$behaviorCriteria->name}}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td style="min-width: 300px; max-width: 400px;">
                                            Ekspektasi Khusus Pimpinan: {{$skpBehavior->notes}}
                                            <br>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- Lampiran -->
                    <div class="table-responsive mb-2">
                        <table class="table table-bordered table-hover table-blue table-sm mb-4">
                            <tbody>
                                <tr class="table-primary text-primary">
                                    <td colspan="3">LAMPIRAN</td>
                                </tr>
                                @if(!empty($attachmentCategories) && count($attachmentCategories) > 0)
                                    @foreach($attachmentCategories as $attachmentCategory)
                                    <tr class="table-primary text-primary mpdf-table-header-gray">
                                        <td colspan="2">{{$attachmentCategory->name}}</td>
                                        <td class="text-left" style="width: 85px">
                                            
                                        </td>
                                    </tr>
                                        @foreach($attachmentCategory->skpWorkAttachments as $key => $skpWorkAttachment)
                                        <tr>
                                            <td class="text-center" style="width: 50px">{{$key+1}}.</td>
                                            <td>{{$skpWorkAttachment->description}}</td>
                                            <td class="text-left"></td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>


        </div><!-- content -->
    </div>
    <div class="span-5 last">
        <div id="sidebar">
        </div><!-- sidebar -->
    </div>
</div>

@endsection

@push('scripts')
<script>
</script>
@endpush



