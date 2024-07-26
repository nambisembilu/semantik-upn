<!--begin::Navbar-->
<div class="row mb-5 mt-5">

    <div class="col-12">
        <div class="card card-bordered">
            <!--begin::Header-->
            <div class="card-header px-7">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label text-gray-800 text-hover-primary fs-2 fw-bolder me-3">Pengisian Kinerja Pegawai</span>
                    <span class="text-gray-400 mt-1 fw-bold fs-6">Kinerja Per bulan</span>
                </h3>
                <!--end::Title-->
            </div>
            <div class="card-body p-6">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <!--begin::Details toggle-->
                        <div class="fs-4 py-3 px-3 bg-primary opacity-75 text-light rounded">
                            <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">Biodata Diri
                                <span class="ms-2 rotate-180">
                                    {!! getSvgIcon('media/icons/duotune/arrows/arr073.svg', 'svg-icon-2x svg-icon-secondary') !!}
                                </span>
                            </div>
                        </div>
                        <!--end::Details toggle-->
                        <div class="separator"></div>
                        <!--begin::Details content-->
                        <div id="user_view_details" class="collapse show">
                            <div class="pb-5 px-3 fs-6">
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Nama Lengkap</div>
                                <div class="text-gray-600">{{$personal->user->name}}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">NIP</div>
                                <div class="text-gray-600">{{$personal->work_id_number}}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Status Pegawai</div>
                                <div class="text-gray-600">{{$personal->employee_type}}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Pangkat/Gol.Ruang</div>
                                <div class="text-gray-600">{{$personal->rank->name}} - {{$personal->rank->grade_name}}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Jabatan</div>
                                <div class="text-gray-600">{{$personal->position->name}}</div>
                                <!--end::Details item-->
                                <!--end::Details item-->
                                <div class="fw-bolder mt-5">Unit Kerja</div>
                                <div class="text-gray-600">{{$personal->unit->name}}</div>
                                <!--end::Details item-->
                            </div>
                        </div>
                        <!--end::Details content-->
                        <!--begin::Details-->
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <!--begin::Details toggle-->
                        <div class="fs-4 py-3 px-3 bg-warning opacity-75 text-light rounded">
                            <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#evaluator_view_details" role="button" aria-expanded="false" aria-controls="evaluator_view_details">Biodata Penilai
                                <span class="ms-2 rotate-180">
                                    {!! getSvgIcon('media/icons/duotune/arrows/arr073.svg', 'svg-icon-2x svg-icon-secondary') !!}
                                </span>
                            </div>
                        </div>
                        <!--end::Details toggle-->
                        <div class="separator"></div>
                        <!--begin::Details content-->
                        <div id="evaluator_view_details" class="collapse show">
                            <div class="pb-5 px-3 fs-6">
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Nama Lengkap</div>
                                <div class="text-gray-600">{{$personal_manager->user->name}}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">NIP</div>
                                <div class="text-gray-600">{{$personal_manager->work_id_number}}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Status Pegawai</div>
                                <div class="text-gray-600">{{$personal_manager->employee_type}}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Pangkat/Gol.Ruang</div>
                                <div class="text-gray-600">{{$personal_manager->rank->name}} - {{$personal_manager->rank->grade_name}}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Jabatan</div>
                                <div class="text-gray-600">{{$personal_manager->position->name}}</div>
                                <!--end::Details item-->
                                <!--end::Details item-->
                                <div class="fw-bolder mt-5">Unit Kerja</div>
                                <div class="text-gray-600">{{$personal_manager->unit->name}}</div>
                                <!--end::Details item-->
                            </div>
                        </div>
                        <!--end::Details content-->
                        <!--begin::Details-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-5">

    <div class="col-12">
        <div class="card mb-5 mb-xxl-8">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                    <!--begin::Wrapper-->
                    <div class="flex-grow-1">
                        <!--begin::Head-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::Details-->
                            <div class="d-flex flex-column">
                                <!--begin::Status-->
                                <div class="d-flex align-items-center mb-1">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-3">{{searchMonth($month)['name']}} {{$year}}</a>
                                </div>
                                <!--end::Status-->
                            </div>
                            <!--end::Details-->
                            <!--begin::Actions-->
                            <div class="d-flex mb-4">
                                <!--begin::Menu-->
                                <div class="me-0">
                                    @if($personal_assessment)
                                        @if($personal_assessment->status=='0')
                                            <span class="badge badge-secondary py-4" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Draft</span>
                                            <button class="btn btn-sm btn-icon btn-bg-primary btn-color-light ms-2" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                <i class="bi bi-three-dots fs-3"></i>
                                            </button>
                                            <!--begin::Menu 3-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
                                                <!--begin::Heading-->
                                                <div class="menu-item px-3">
                                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Aksi</div>
                                                </div>
                                                <!--end::Heading-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <form action="{{route('modules.performance.employee.update-status')}}" id="form-update-status-assessment" method="post">
                                                        @csrf
                                                        <input type="hidden" name="personal_assessment_id" value="{{$personal_assessment->id}}">
                                                        <input type="hidden" name="status" value="1">
                                                        <a class="menu-link px-3  fs-5" onclick="$('#form-update-status-assessment').submit()"><i class="bi bi-send mx-2"></i> Kirim</a>
                                                    </form>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 fs-5"><i class="bi bi-arrow-clockwise mx-2"></i> Reset</a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu 3-->
                                        @elseif($personal_assessment->status=='1')
                                            <span class="badge badge-primary py-4" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Sudah dikirim</span>
                                        @elseif($personal_assessment->status=='2')
                                            <span class="badge badge-info py-4" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Sedang dinilai</span>
                                        @elseif($personal_assessment->status=='3')
                                            <span class="badge badge-success py-4" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Sudah dinilai</span>
                                        @endif
                                    @else
                                        <span class="badge badge-warning py-4" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-html="true" title='@include('modules.performance.employee.widgets._tooltips-status-skp')'>Belum Mengisi</span>
                                    @endif

                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Head-->
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <!--begin::Details toggle-->
                                <div class="fs-4 py-3 px-3 bg-success text-light rounded">
                                    <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#summary_assessment" role="button" aria-expanded="false" aria-controls="summary_assessment">Ringkasan Penilaian
                                        <span class="ms-2 rotate-180">
                                    {!! getSvgIcon('media/icons/duotune/arrows/arr073.svg', 'svg-icon-2x svg-icon-secondary') !!}
                                </span>
                                    </div>
                                </div>
                                <!--end::Details toggle-->
                                <div class="separator"></div>
                                <!--begin::Details content-->
                                <div id="summary_assessment" class="collapse show">
                                    <div class="pb-3  fs-6">
                                        <p class="text-center py-10">
                                            <br/>
                                            @if($personal_assessment)
                                                @if($personal_assessment->status<3)
                                                    {!! getSvgIcon('media/icons/duotune/general/gen044.svg', 'svg-icon-5tx svg-icon-warning w-150px h-150px') !!}
                                                    <br/><br/>
                                                    <span class="text-muted">Penilaian belum dilakukan</span>
                                                @else
                                                    {!! getSvgIcon('media/icons/duotune/general/gen020.svg', 'svg-icon-5tx svg-icon-primary w-150px h-150px') !!}

                                                    @php $vals=getValueRangeAssessment($personal_assessment->result) @endphp
                                                    <br/><br/>
                                                    <span class="badge {{$vals['badge_class']}} mt-5 fs-7">{{$vals['text']}}</span>
                                                @endif
                                            @else
                                                {!! getSvgIcon('media/icons/duotune/general/gen044.svg', 'svg-icon-5tx svg-icon-warning w-150px h-150px') !!}
                                                <br/><br/>
                                                <span class="text-muted">Penilaian belum dilakukan</span>
                                            @endif

                                        </p>
                                    </div>
                                </div>
                                <!--end::Details content-->
                                <!--begin::Details-->
                            </div>
                        </div>

                        <div class="separator border-2  mt-5"></div>
                        <!--begin::Nav wrapper-->
                        <div class="d-flex overflow-auto h-55px">
                            <!--begin::Nav links-->
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder flex-nowrap">

                                <!--begin::Nav item-->
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary me-6  @if(Request::route()->getName()=='modules.performance.employee.detail-attendance') active @endif" href="{{route('modules.performance.employee.detail-attendance',['month'=>$month,'year'=>$year])}}">Absensi</a>
                                </li>
                                <!--end::Nav item-->
                                <!--begin::Nav item-->
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary me-6 @if(Request::route()->getName()=='modules.performance.employee.detail') active @endif" href="{{route('modules.performance.employee.detail',['month'=>$month,'year'=>$year])}}">Kinerja Utama & Tambahan</a>
                                </li>
                                <!--end::Nav item-->
                                <!--begin::Nav item-->
                            {{--                                <li class="nav-item">--}}
                            {{--                                    <a class="nav-link text-active-primary me-6 " href="#">Sikap Perilaku</a>--}}
                            {{--                                </li>--}}
                            <!--end::Nav item-->
                            </ul>
                            <!--end::Nav links-->
                        </div>
                        <!--end::Nav wrapper-->
                    </div>
                    <!--end::Wrapper-->
                </div>
            </div>
        </div>
    </div>
</div>
