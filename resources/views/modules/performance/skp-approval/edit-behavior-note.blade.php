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
                    <form id="formBehaviorNote">
                        @csrf
                        <input type="hidden" name="id" value="{{$skp->id}}"/>
                    <div style="float:right!important;">
                        <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                            
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body pt-2">
                    <form id="formBehavorNote">
                        @csrf
                        <input type="hidden" name="id" value="{{$skp->id}}"/>
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

                    <!-- Perilaku Kerja -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-blue table-sm mb-4">
                            <tbody>
                                <tr class="table-primary text-primary">
                                    <td colspan="3">PERILAKU KERJA</td>
                                </tr>
                                @if(!empty($skpBehaviors) && count($skpBehaviors) > 0)
                                    @foreach($skpBehaviors as $key => $skpBehavior)
                                    <input type="hidden" name="skp_behavior_id[]" value="{{$skpBehavior->id}}"/>
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
                                            Ekspektasi Khusus Pimpinan:
                                            <br>
                                            <textarea name="behavior_note[]" class="form-control" cols="40" rows="5">{{$skpBehavior->notes}}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary ms-3" id="btn_submit_behavior_note">Simpan Perubahan</button>
                    </div>

            </form>
        </div><!-- content -->
    </div>
</div>

@endsection

@push('scripts')
<script>
    
$(document).ready(function() {
    $("#formBehaviorNote").submit(function(e){
        e.preventDefault();

        var formData = new FormData(this);

        //execute submit
        $.ajax({
            url: "{{route('modules.performance.skp-approval.save_behavior_note')}}",
            type:'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(data) {
                if(data.status == 1){
                    toastr.success(data.message);
                    setTimeout(location.reload.bind(location), 2000);
                }
                else
                {
                    if(typeof data.error === 'string')
                    {
                        toastr.error(data.error);
                    }
                    else
                    {
                        window.printErrorMsg(data.error);
                    }
                }
            }
        });
    });
});
</script>
@endpush



