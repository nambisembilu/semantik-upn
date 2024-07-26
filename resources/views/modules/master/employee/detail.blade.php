@extends('template.master')

@section('sidebar')
    @include('template.sidebar')
@endsection

@section('page-head')
    <div class="page-header">
        <div class="page-header-content container d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Pegawai -
                    <span class="fw-normal">
                        @if($data)
                            Edit
                        @else
                            Tambah
                        @endif
                    </span>
                </h4>

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
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
    <div class="content container pt-0">

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center py-3">
                <h4 class="m-0 font-weight-bold text-primary">Data Pegawai</h4>
                <div class="ms-auto">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProfil">
                        Ubah Profil
                    </button>
                </div>
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
                            <td class=""><b>{{ !empty($data) ? $data->name : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($data) ? $data->work_id_number : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Pangkat&nbsp;/&nbsp;Gol.</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($data) ? $data->workRank->grade_name : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($data)&&!empty($data->lastUnitPosition) ? $data->lastUnitPosition->workPosition->name : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Unit Kerja</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($data)&&!empty($data->lastUnitPosition) ? $data->lastUnitPosition->workUnit->name : '-' }}</b></td>
                        </tr>
                        <tr>
                            <td>Jenis Pegawai</td>
                            <td style="width: 10px">:</td>
                            <td><b>{{ !empty($data) ? $data->employee_type : '-' }}</b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </div><!-- content -->
        </div>
        <!-- Basic card -->
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center py-3">
                <h4 class="m-0 font-weight-bold text-primary">Riwayat Jabatan</h4>
                @if(session('role_name')=='Superadmin')
                    <div class="ms-auto">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalHistory">
                            Tambah Jabatan
                        </button>
                    </div>
                @endif
            </div>
            <div class="card-body pt-2">
                <!-- Header Data Pegawai -->
                <div class="table-responsive">
                    <!-- Data Pegawai -->
                    <table class="table table-striped table-hover table-sm mb-4">
                        <thead>
                        <tr class="bg-secondary bg-gradient text-white">
                            <th>No</th>
                            <th>Jabatan</th>
                            <th>Unit Kerja</th>
                            <th>TMT</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($personal_work_units as $personal_work_unit)

                            <tr>
                                <td style="width: 20px">{{$loop->index+1}}</td>
                                <td>{{$personal_work_unit->workPosition->name}}</td>
                                <td class="">{{$personal_work_unit->workUnit->name}}</td>
                                <td class="">{{Carbon\Carbon::parse($personal_work_unit->start_date)->translatedFormat('d F Y')}}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <button data-id="{{$personal_work_unit->id}}" class="btn btn-sm btn-warning btn-icon btn-edit-history me-2" data-bs-toggle="modal" data-bs-target="#modalEditHistory"><i class="ph-pencil"></i></button>

                                        @if(session('role_name')=='Superadmin')
                                            <form action="{{route('modules.master.employee.deleteHistory')}}" method="post">
                                                <input type="hidden" name="id" value="{{$personal_work_unit->id}}"/>
                                                <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                                <button type="submit" class="btn btn-sm btn-danger btn-icon">
                                                    <i class="ph-x"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center"><b class="text-danger">Riwayat Kosong</b></td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>


            </div><!-- content -->
        </div>
        <!-- /basic card -->


    </div>
    <!-- /content area -->

@endsection



@push('modals')
    <div class="modal fade" id="modalProfil" aria-labelledby="modalProfilLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modalLead-content" class="modal-content">
                <form id="form-ppk" action="{{route('modules.master.employee.saveProfile',[$data->id])}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Profil Pegawai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Nama :</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama" @if($data) value="{{$data->name}}" @endif>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIP/NIK :</label>
                            <input type="text" readonly name="work_id_number" class="form-control" placeholder="Nomer Induk Pegawai" @if($data) value="{{$data->work_id_number}}" @endif>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Pegawai:</label>
                            <div class="col-6">
                                <select name="employee_type" data-placeholder="Pilih..." class="form-control">
                                    <option value="">---</option>
                                    @foreach($employee_types as $employee_type)
                                        <option value="{{$employee_type->name}}" @if($data) @if($data->employee_type==$employee_type->name) selected @endif @endif >{{$employee_type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pangkat/Golongan:</label>
                            <div class="col-6">
                                <select name="work_rank_id" data-placeholder="Pilih..." class="form-control select-profile">
                                    <option></option>
                                    @foreach($work_ranks as $work_rank)
                                        <option value="{{$work_rank->id}}" @if($data) @if($data->work_rank_id==$work_rank->id) selected @endif @endif >{{$work_rank->grade_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalHistory" aria-labelledby="modalHistoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="modalHistory-content" class="modal-content">
                <form id="form-clear-ppk" action="{{route('modules.master.employee.addHistory',[$data->id])}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Riwayat Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Unit Kerja Utama:</label>
                            <div class="col-12">
                                <select name="root_work_unit_id" data-placeholder="Pilih..." class="form-control select-history">
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
                                <select name="work_unit_id" data-placeholder="Pilih..." class="form-control select-history">
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
                                <select name="work_position_id" data-placeholder="Pilih..." class="form-control select-history">
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
                                <select name="role_id" data-placeholder="Pilih..." class="form-control select-history">
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
                                    <input class="me-2" type="radio" value="1" name="is_head" id="dr_ri_c">

                                </div>

                                <div class="d-inline-flex flex-row-reverse align-items-center">
                                    <label class="me-2" for="dr_ri_u">Anggota</label>
                                    <input class="me-2" type="radio" value="0" name="is_head" id="dr_ri_u" checked>

                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">TMT :</label>
                            <div class="col-3">
                                <input type="date" name="date" class="form-control" placeholder="Tanggal TMT">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEditHistory" aria-labelledby="modalEditHistoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div id="modalEditHistory-content" class="modal-content">
            </div>
        </div>
    </div>
@endpush


@push('scripts')
    <!-- Theme JS files -->
    <script>
        // Default initialization
        $('.select-profile').select2({
            dropdownParent: $("#modalProfil"),
        });
        $('.select-history').select2({
            dropdownParent: $("#modalHistory"),
        });


        $('.btn-edit-history').click(function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "{{route('modules.master.employee.editHistory')}}",
                method: 'POST',
                data:
                    {
                        _token: $("input[name='_token']").val(),
                        id: id
                    },
                async: false,
                success: function (response) {
                    $('#modalEditHistory-content').html(response);
                }
            })
        });
    </script>
    <!-- /theme JS files -->
@endpush
