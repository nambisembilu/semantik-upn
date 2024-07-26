@extends('template.master')

@section('title', $menu_title)

@section('sidebar')
    @include('template.sidebar')
@endsection

@section('page-head')
    <div class="page-header">
        <div class="page-header-content container d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    {{$menu_title}} -
                    <span class="fw-normal">
                        Tambah
                    </span>
                </h4>

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
    <div class="content container pt-0">

        <!-- Basic card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Form</h6>
            </div>

            <div class="card-body">
                <form action="{{route($route.'store')}}" method="post">
                    @csrf
                    @if($data)
                        <input type="hidden" name="id" value="{{$data->id}}">
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nama :</label>
                        <div class="col-6">
                            <input type="text" name="name" class="form-control" placeholder="Nama" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP/NIK :</label>
                        <div class="col-6">
                            <input type="text" name="work_id_number" class="form-control" placeholder="Nomer Induk Pegawai" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="fw-semibold">Jenis Kelamin</p>
                        <div class="border p-3 rounded">
                            <div class="d-inline-flex flex-row-reverse align-items-center me-3">
                                <label class="me-2" for="dr_ri_c">Laki-laki</label>
                                <input class="me-2" type="radio" name="gender" value="Laki-laki" id="dr_ri_c" required>

                            </div>

                            <div class="d-inline-flex flex-row-reverse align-items-center">
                                <label class="me-2" for="dr_ri_u">Perempuan</label>
                                <input class="me-2" type="radio" name="gender" value="Perempuan" id="dr_ri_u" checked>

                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Pegawai:</label>
                        <div class="col-6">
                            <select name="employee_type" data-placeholder="Pilih..." class="form-control" required>
                                <option value="">---</option>
                                @foreach($employee_types as $employee_type)
                                    <option value="{{$employee_type->name}}"  >{{$employee_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pangkat/Golongan:</label>
                        <div class="col-6">
                            <select name="work_rank_id" data-placeholder="Pilih..." class="form-control select-profile" required>
                                <option></option>
                                @foreach($work_ranks as $work_rank)
                                    <option value="{{$work_rank->id}}"  >{{$work_rank->grade_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit Kerja Utama:</label>
                        <div class="col-12">
                            <select name="root_work_unit_id" data-placeholder="Pilih..." class="form-control select-history" required>
                                <option></option>
                                @foreach($work_units as $work_unit)
                                    <option value="{{$work_unit->id}}">{{$work_unit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit Kerja:</label>
                        <div class="col-12">
                            <select name="work_unit_id" data-placeholder="Pilih..." class="form-control select-history" required>
                                <option></option>
                                @foreach($work_units as $work_unit)
                                    <option value="{{$work_unit->id}}">{{$work_unit->name}}</option>
                                    @if(count($work_unit->childs)>0&&!empty($work_unit->parent_id))
                                        @foreach($work_unit->childs as $work_unit_child1)
                                            <option value="{{$work_unit_child1->id}}">{{$work_unit->name}} > {{$work_unit_child1->name}}</option>
                                            @if(count($work_unit_child1->childs)>0&&!empty($work_unit_child1->parent_id))
                                                @foreach($work_unit_child1->childs as $work_unit_child2)
                                                    <option value="{{$work_unit_child2->id}}">{{$work_unit->name}} > {{$work_unit_child1->name}} > {{$work_unit_child2->name}}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan:</label>
                        <div class="col-12">
                            <select name="work_position_id" data-placeholder="Pilih..." class="form-control select-history" required>
                                <option></option>
                                @foreach($work_positions as $work_position)
                                    <option value="{{$work_position->id}}">{{$work_position->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Roles:</label>
                        <div class="col-12">
                            <select name="role_id" data-placeholder="Pilih..." class="form-control select-history" required>
                                <option></option>
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="fw-semibold">Status Tim</p>
                        <div class="border p-3 rounded">
                            <div class="d-inline-flex flex-row-reverse align-items-center me-3">
                                <label class="me-2" for="dr_ri_c">Ketua</label>
                                <input class="me-2" type="radio" name="is_head" id="dr_ri_c" required>

                            </div>

                            <div class="d-inline-flex flex-row-reverse align-items-center">
                                <label class="me-2" for="dr_ri_u">Anggota</label>
                                <input class="me-2" type="radio" name="is_head" id="dr_ri_u" checked>

                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">TMT :</label>
                        <div class="col-3">
                            <input type="date" name="date" class="form-control" placeholder="Tanggal TMT" required>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <a href="{{route($route.'index')}}" class="btn btn-light">Kembali</a>
                        <button type="submit" class="btn btn-primary ms-3"><i class="ph-check me-2"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /basic card -->


    </div>
    <!-- /content area -->

@endsection

@push('scripts')
    <!-- Theme JS files -->
    <script>
        // Default initialization
        $('.select-profile, .select-history').select2();
    </script>
    <!-- /theme JS files -->
@endpush
