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
                    {{$menu_title}}
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

        <div class="card shadow mb-12">

            <div class="card-header py-3">
                <div style="float:right!important;">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Aksi">
                        <button type="button" class="btn btn-sm btn-primary rounded px-3" title="Tambah Sasaran" 
                        onclick="AddAgreementModal(0,'','')">
                            <i class="ph ph-plus"></i>&nbsp;&nbsp;Sasaran
                        </button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="card-body">
                <div class="alert alert-info mb-2" role="alert">
                    <ul class="pl-3 mb-0">
                        <li>Nomor harus unik (tidak boleh ganda).</li>
                        <li>Sasaran &amp; indikator bisa dihapus jika belum diturunkan menjadi butir SKP.</li>
                    </ul>
                </div>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-dark text-white">
                            <tr class="text-center">
                                <th style="width: 75px;">No</th>
                                <th colspan="2">Sasaran</th>
                                <th style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($employmentAgreements) && count($employmentAgreements) > 0)
                            @foreach($employmentAgreements as $employmentAgreement)
                            <tr>
                                <td class="align-top text-center font-weight-bold" rowspan="{{(count($employmentAgreement->employmentAgreementIndicators) + 1)}}">
                                    {{ $employmentAgreement->no }}</td>
                                <td class="align-top font-weight-bold" colspan="2">
                                    {{ $employmentAgreement->title }} (Penugasan dari {{ $employmentAgreement->get_task_from }} ) </td>
                                <td class="align-top text-right">
                                    <button type="button" class="btn btn-sm btn-primary rounded"
                                        title="&nbsp;Tambah Indikator&nbsp;" onclick="AddAgreementIndicatorModal({{$employmentAgreement->id}}, 0, '', '', '', '')"
                                        style="width: 35px; margin-right: 1px;">
                                        <i class="ph ph-plus"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success rounded"
                                        title="&nbsp;Edit Sasaran&nbsp;" onclick="AddAgreementModal({{$employmentAgreement->id}}, '{{$employmentAgreement->no}}', '{{$employmentAgreement->title}}')" style="width: 35px;">
                                        <i class="ph ph-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger rounded"
                                        title="&nbsp;Hapus Sasaran&nbsp;" onclick="DeleteAgreement({{$employmentAgreement->id}})"
                                        style="width: 35px;">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </td>
                            </tr>
                                @foreach($employmentAgreement->employmentAgreementIndicators as $employmentAgreementIndicator)
                                <tr>
                                    <td class="align-top text-center" style="width: 75px;">
                                        {{ $employmentAgreementIndicator->code }} </td>
                                    <td class="align-top">
                                        {{ $employmentAgreementIndicator->title }} 
                                        (Perspektif {{ $employmentAgreementIndicator->employmentAgreementIndicatorPerspectives[0]->perspectiveIndicator->name }})
                                         (Target : {{ $employmentAgreementIndicator->target }})
                                    </td>
                                    <td class="align-top text-right">
                                        <button type="button" class="btn btn-sm btn-success rounded"
                                            title="&nbsp;Edit Indikator&nbsp;" onclick="AddAgreementIndicatorModal({{$employmentAgreement->id}}, {{$employmentAgreementIndicator->id}}, 
                                            '{{$employmentAgreementIndicator->code}}', '{{$employmentAgreementIndicator->title}}', '{{$employmentAgreementIndicator->target}}',
                                            {{$employmentAgreementIndicator->employmentAgreementIndicatorPerspectives[0]->perspective_indicator_id}})"
                                            style="width: 35px;">
                                            <i class="ph ph-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger rounded"
                                            title="&nbsp;Hapus Indikator&nbsp;" onclick="DeleteAgreementIndicator({{$employmentAgreementIndicator->id}})"
                                            style="width: 35px;">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @include('modules.master.employment-agreement.modals.add-agreement')
    @include('modules.master.employment-agreement.modals.add-agreement-indicator')
@endsection

@push('scripts')
    <script>
        function AddAgreementModal(id, no, title) {
            $('#modalAddAgreement').modal('show');

            $('#agreement_id').val(id);
            $('#agreement_no').val(no);
            $('#agreement_title').val(title);
        }

        function CloseAgreementModal() {
            $('#modalAddAgreement').modal('hide');
        }

        function AddAgreementIndicatorModal(agreementId, id, code, title, target, perspectiveId) {
            $('#modalAddAgreementIndicator').modal('show');
            $('#indi_agreement_id').val(agreementId);
            $('#agreement_indicator_id').val(id);
            $('#agreement_indicator_code').val(code);
            $('#agreement_indicator_title').val(title);
            $('#agreement_indicator_target').val(target);
            $('#agreement_indicator_perspective').val(perspectiveId);
        }

        function CloseAgreementIndicatorModal() {
            $('#modalAddAgreementIndicator').modal('hide');
        }

        function DeleteAgreement(id) {
        $.ajax({
            url: "{{route('modules.master.employment-agreement.delete_employment_agreement')}}",
                method: 'POST',
                data: 
                {
                    _token: $("input[name='_token']").val(),
                    id: id,
                },
                async: false,
                success: function (response) {
                    if (response.status == '1') 
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

        function DeleteAgreementIndicator(id) {
        $.ajax({
            url: "{{route('modules.master.employment-agreement.delete_employment_agreement_indicator')}}",
                method: 'POST',
                data: 
                {
                    _token: $("input[name='_token']").val(),
                    id: id,
                },
                async: false,
                success: function (response) {
                    if (response.status == '1') 
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

            $("#formAddAgreement").submit(function(e){
                e.preventDefault();

                //clear error before
                $('.agreement_no_err').text("");
                $('.agreement_title_err').text("");

                var formData = new FormData(this);

                //execute submit
                $.ajax({
                    url: "{{route('modules.master.employment-agreement.create_employment_agreement')}}",
                    type:'POST',
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(data) {
                        if(data.status == 1){
                            toastr.success(data.message);
                            CloseAgreementModal();
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
            
            $("#formAddAgreementIndicator").submit(function(e){
                e.preventDefault();

                //clear error before
                $('.agreement_indicator_code_err').text("");
                $('.agreement_indicator_title_err').text("");
                $('.agreement_indicator_target_err').text("");
                $('.agreement_id_err').text("");
                $('.agreement_indicator_perspective_err').text("");

                var formData = new FormData(this);

                //execute submit
                $.ajax({
                    url: "{{route('modules.master.employment-agreement.create_employment_agreement_indicator')}}",
                    type:'POST',
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(data) {
                        if(data.status == 1){
                            toastr.success(data.message);
                            CloseAgreementIndicatorModal();
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
