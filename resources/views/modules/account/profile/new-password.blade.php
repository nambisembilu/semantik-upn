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
            <small class="text-muted fs-7 fw-bold my-1 ms-1">Reset Password</small>
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
                <form class="form" method="post" action="{{route('modules.account.profile.update-password')}}">
                    @csrf

                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">Form </h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="text-muted fw-bold fs-5">
                            Reset Password
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->
                    <div class="mt-10 fv-row">
                        <label class="required form-label">Nama</label>
                        <input type="text" name="name" style="text-transform: capitalize" class="form-control form-control-solid mb-2" placeholder="Nama" @if($data->id) value="{{$data->name}}" @endif disabled required/>
                    </div>
                    <div class="mt-10 fv-row">
                        <label class="required form-label">Password Lama</label>
                        <input class="form-control form-control-solid mb-2" type="password" name="password" required />
                    </div>
                    <div class="mt-10 fv-row">
                        <label class="required form-label">Password Baru</label>
                        <input class="form-control form-control-solid mb-2" type="password" name="newPassword" required />
                    </div>
                    <div class="mt-10 fv-row">
                        <label class="required form-label">Confirm Password</label>
                        <input  class="form-control form-control-solid mb-2"type="password" name="confirmPassword" required />
                    </div>
                    <div class="text-center">
                        <input type="hidden" name="id" value="{{$user->id}}">
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

