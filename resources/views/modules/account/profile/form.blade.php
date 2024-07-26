@extends('template.master')

@section('css')
    <link href="{{asset('plugins/custom/vis-timeline/vis-timeline.bundle.css')}}" rel="stylesheet" type="text/css"/>
@endsection

@section('toolbar-title')
    <!--begin::Page title-->
    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
        <!--begin::Title-->
        <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Akun
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Profil</small>
            <!--end::Description--></h1>
        <!--end::Title-->
    </div>
    <!--end::Page title-->
@endsection

@section('toolbar-action')

    <!--begin::Actions-->
    <div class="d-flex">

    </div>
    <!--end::Actions-->
@endsection

@section('page-content')
    <!--begin::Container-->
    <div class="content container pt-0">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card body-->
            <div class="card-body py-4">
                <form class="form" method="post" action="{{route('modules.account.profile.update')}}">
                    @csrf

                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">Form </h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="text-muted fw-bold fs-5">
                            Profil
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->

                    <!--begin::Input group-->
                    <div class="mt-10 fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Nama</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="name" style="text-transform: capitalize" class="form-control form-control-solid mb-2" placeholder="Nama" @if($data->id) value="{{$data->name}}" @endif required/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mt-10 fv-row">
                        <!--begin::Label-->
                        <label class="required form-label">Nomor Induk Pegawai (NIP/NIK)</label>
                        <!--end::Label-->
                        <!--begin::Input-->
                        <input type="text" name="work_id_number" style="text-transform: capitalize" class="form-control form-control-solid mb-2" placeholder="NIP/NIK" @if($data->id) value="{{$data->work_id_number}}" @endif required/>
                        <!--end::Input-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mt-10 d-flex flex-column mb-5 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center mb-2">
                            <span class="required">Jenis Kelamin</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Select-->
                        <select name="gender" data-control="select2" data-placeholder="Pilih..." class="form-select form-select-solid" required>
                            <option value="">Pilih...</option>
                            <option value="Laki - Laki" @if($data->id) @if($data->gender=='Laki - Laki') selected @endif @endif>Laki-Laki</option>
                            <option value="Wanita" @if($data->id) @if($data->gender=='Wanita') selected @endif @endif>Wanita</option>
                        </select>
                        <!--end::Select-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mt-10 d-flex flex-column mb-5 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center mb-2">
                            <span class="required">Jabatan</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Select-->
                        <select name="work_position_unit_id" data-control="select2" data-placeholder="Pilih..." class="form-select form-select-solid" disabled="">
                            <option value="">Pilih...</option>
                            @foreach($work_position_units as $work_position_unit)
                                <option value="{{$work_position_unit->id}}" @if($data->id) @if($data->work_position_id==$work_position_unit->work_position_id&&$data->work_unit_id==$work_position_unit->work_unit_id) selected @endif @endif>{{$work_position_unit->unit->name}} - {{$work_position_unit->position->name}}</option>
                            @endforeach
                        </select>
                        <!--end::Select-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mt-10 d-flex flex-column mb-5 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center mb-2">
                            <span class="required">Pangkat dan Golongan</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Select-->
                        <select name="work_rank_id" data-control="select2" data-placeholder="Pilih..." class="form-select form-select-solid" disabled="">
                            <option value="">Pilih...</option>
                            @foreach($work_ranks as $work_rank)
                                <option value="{{$work_rank->id}}" @if($data->id) @if($data->work_rank_id==$work_rank->id) selected @endif @endif>{{$work_rank->grade_name}}</option>
                            @endforeach
                        </select>
                        <!--end::Select-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mt-10 d-flex flex-column mb-5 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center mb-2">
                            <span class="required">Status Kepegawaian</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Select-->
                        <select name="employee_type" data-control="select2" data-placeholder="Pilih..." class="form-select form-select-solid" disabled="">
                            <option value="">Pilih...</option>
                            @foreach($employee_types as $employee_type)
                                <option value="{{$employee_type->value}}" @if($data->id) @if($data->employee_type==$employee_type->value) selected @endif @endif>{{$employee_type->name}}</option>
                            @endforeach
                        </select>
                        <!--end::Select-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="mt-10 d-flex flex-column mb-5 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center mb-2">
                            <span class="required">Role</span>
                        </label>
                        <!--end::Label-->
                        <!--begin::Select-->
                        <select name="role_id" data-control="select2" data-placeholder="Pilih..." class="form-select form-select-solid" disabled="">
                            <option value="">Pilih...</option>
                            @foreach($roles as $role)
                                <option value="{{$role->id}}" @if($data->id) @if($data->user->role_id==$role->id) selected @endif @endif>{{$role->name}}</option>
                            @endforeach
                        </select>
                        <!--end::Select-->
                    </div>
                    <!--end::Input group-->
                    <div class="mt-10 d-flex flex-column mb-5 fv-row">
                        <label class="d-flex align-items-center mb-2">
                            <span class="required">Pangkat dan Golongan</span>
                        </label>
                        <select name="work_rank_id" data-control="select2" data-placeholder="Pilih..." class="form-select form-select-solid" disabled="">
                            <option value="">Pilih...</option>
                            @foreach($work_ranks as $work_rank)
                                <option value="{{$work_rank->id}}" @if($data->id) @if($data->work_rank_id==$work_rank->id) selected @endif @endif>{{$work_rank->grade_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($user->role_id!=6)
                    <div class="mt-10 d-flex flex-column mb-5 fv-row">
                        <label class="d-flex align-items-center mb-2">
                            <span class="required">Pilih Atasan Langsung</span>
                        </label>
                        <select name="lead_id" data-control="select2" data-placeholder="Pilih Atasan Langsung..." class="form-select form-select-solid form-select-sm">
                                <option value="">Pilih Atasan Langsung...</option>
                            @foreach($lead_datas as $lead_data)
                                <option value="{{$lead_data->id}}"@if($data->id) @if($data->lead_id==$lead_data->id) selected @endif @endif>{{$lead_data->position->name}} - {{$lead_data->name}}</option>
                            @endforeach
                            
                        </select>
                    </div>
                    @endif
                    <div class="text-center">
                        <input type="hidden" name="id" value="{{$data->id}}">
                        <button type="submit" class="btn btn-primary" name="submit">
                            <span class="indicator-label">Simpan</span>
                            <span class="indicator-progress">Please wait...
									<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
@endsection

@push('scripts')

    <script src="{{asset('js/customs/apps/projects/settings/settings.js')}}"></script>
    <script src="{{asset('js/customs/apps/projects/project/project.js')}}"></script>
@endsection

@section('modal')
@endsection

