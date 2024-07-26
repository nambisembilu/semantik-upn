<!--begin::Navbar-->
<div class="row mb-5 mt-5">

    <div class="col-12">
        <div class="card card-bordered">
            <!--begin::Header-->
            <div class="card-header px-7">
                <!--begin::Title-->
                <h3 class="card-title d-flex align-items-start flex-column">
                    <span class="card-label text-gray-900 text-hover-primary fs-1 fw-bolder me-3">
                        {{ $personal->unit->name }}
                    </span>
                    <span class="text-gray-400 mt-1 fw-bold fs-6">Pendekatan Hasil Kerja <b class="fw-bolder text-gray-800">{{ $work_performance_result->measurement }}</b></span>
                </h3>
                <!--end::Title-->
                <div class="card-toolbar d-flex align-items-end flex-column">

                    <span class="card-label text-gray-800 fs-3 fw-bolder me-3">
                        RENAKSI Tahun {{ $work_performance_result->year }}
                    </span>
                    <span class="card-label text-gray-400 fs-6 fw-bolder me-3">
                        Periode
                        {{ Carbon\Carbon::parse($work_performance_result->start_period)->translatedFormat('d F Y') }}
                        -
                        {{ Carbon\Carbon::parse($work_performance_result->end_period)->translatedFormat('d F Y') }}
                    </span>
                    @if ($work_performance_result->status == '1')
                        <span class="fs-8 badge badge-success">Disetujui</span>
                    @else
                        <span class="fs-8 badge badge-secondary">Draft</span>
                    @endif
                </div>
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
                        <div id="user_view_details" class="collapse">
                            <div class="pb-5 px-3 fs-6">
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Nama Lengkap</div>
                                <div class="text-gray-600">{{ $personal->user->name }}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">NIP</div>
                                <div class="text-gray-600">{{ $personal->work_id_number }}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Status Pegawai</div>
                                <div class="text-gray-600">{{ $personal->employee_type }}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Pangkat/Gol.Ruang</div>
                                <div class="text-gray-600">{{ $personal->rank->name }} -
                                    {{ $personal->rank->grade_name }}</div>
                                <!--end::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bolder mt-5">Jabatan</div>
                                <div class="text-gray-600">{{ $personal->position->name }}</div>
                                <!--end::Details item-->
                                <!--end::Details item-->
                                <div class="fw-bolder mt-5">Unit Kerja</div>
                                <div class="text-gray-600">{{ $personal->unit->name }}</div>
                                <!--end::Details item-->
                            </div>
                        </div>
                        <!--end::Details content-->
                        <!--begin::Details-->
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <!--begin::Details toggle-->
                        <div class="fs-4 py-3 px-3 bg-warning opacity-75 text-light rounded">
                            <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#evaluator_view_details" role="button" aria-expanded="false" aria-controls="evaluator_view_details">Biodata
                                Penilai
                                <span class="ms-2 rotate-180">
                                    {!! getSvgIcon('media/icons/duotune/arrows/arr073.svg', 'svg-icon-2x svg-icon-secondary') !!}
                                </span>
                            </div>
                        </div>
                        <!--end::Details toggle-->
                        <div class="separator"></div>
                        <!--begin::Details content-->
                        <div id="evaluator_view_details" class="collapse">
                            <div class="pb-5 px-3 fs-6">
                                @if (!empty($personal_head))
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">Nama Lengkap</div>
                                    <div class="text-gray-600">{{ $personal_head->user->name }}</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">NIP</div>
                                    <div class="text-gray-600">{{ $personal_head->work_id_number }}</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">Status Pegawai</div>
                                    <div class="text-gray-600">{{ $personal_head->employee_type }}</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">Pangkat/Gol.Ruang</div>
                                    <div class="text-gray-600">{{ $personal_head->rank->name }} -
                                        {{ $personal_head->rank->grade_name }}</div>
                                    <!--end::Details item-->
                                    <!--begin::Details item-->
                                    <div class="fw-bolder mt-5">Jabatan</div>
                                    <div class="text-gray-600">{{ $personal_head->position->name }}</div>
                                    <!--end::Details item-->
                                    <!--end::Details item-->
                                    <div class="fw-bolder mt-5">Unit Kerja</div>
                                    <div class="text-gray-600">{{ $personal_head->unit->name }}</div>
                                    <!--end::Details item-->
                                @endif

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
            <div class="card-body">
                <!--begin::Details-->
                <div class="d-flex justify-content-center">
                    <a href="#" class="btn btn-primary  mb-2"><span class="bi bi-printer"></span>
                        Cetak
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
