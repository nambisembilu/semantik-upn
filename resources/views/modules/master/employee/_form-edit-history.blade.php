<form id="form-clear-ppk" action="{{route('modules.master.employee.saveHistory')}}" method="post">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Riwayat Jabatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Unit Kerja Utama:</label>
            <div class="col-12">
                <select name="root_work_unit_id" data-placeholder="Pilih..." class="form-control select-edit-history">
                    <option></option>
                    @foreach($work_units as $work_unit)
                        <option value="{{$work_unit->id}}" @if($data->root_work_unit_id==$work_unit->id) selected @endif>{{$work_unit->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Unit Kerja:</label>
            <div class="col-12">
                <select name="work_unit_id" data-placeholder="Pilih..." class="form-control select-edit-history">
                    <option></option>
                    @foreach($work_units as $work_unit)
                        <option value="{{$work_unit->id}}" @if($data->work_unit_id==$work_unit->id) selected @endif>{{$work_unit->name}}</option>
                        @if(count($work_unit->childs)>0&&!empty($work_unit->parent_id))
                            @foreach($work_unit->childs as $work_unit_child1)
                                <option value="{{$work_unit_child1->id}}"  @if($data->work_unit_id==$work_unit_child1->id) selected @endif>{{$work_unit->name}} > {{$work_unit_child1->name}}</option>
                                @if(count($work_unit_child1->childs)>0&&!empty($work_unit_child1->parent_id))
                                    @foreach($work_unit_child1->childs as $work_unit_child2)
                                        <option value="{{$work_unit_child2->id}}" @if($data->work_unit_id==$work_unit_child2->id) selected @endif>{{$work_unit->name}} > {{$work_unit_child1->name}} > {{$work_unit_child2->name}}</option>
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
                <select name="work_position_id" data-placeholder="Pilih..." class="form-control select-edit-history">
                    <option></option>
                    @foreach($work_positions as $work_position)
                        <option value="{{$work_position->id}}" @if($data->work_position_id==$work_position->id) selected @endif>{{$work_position->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Roles:</label>
            <div class="col-12">
                <select name="role_id" data-placeholder="Pilih..." class="form-control select-edit-history">
                    <option></option>
                    @foreach($roles as $role)
                        <option value="{{$role->id}}" @if($data->role_id==$role->id) selected @endif>{{$role->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3">
            <p class="fw-semibold">Status Tim</p>
            <div class="border p-3 rounded">
                <div class="d-inline-flex flex-row-reverse align-items-center me-3">
                    <label class="me-2" for="dr_ri_c">Ketua</label>
                    <input class="me-2" type="radio" name="is_head" value="1" id="dr_ri_c" @if($data->is_head) checked @endif>

                </div>

                <div class="d-inline-flex flex-row-reverse align-items-center">
                    <label class="me-2" for="dr_ri_u">Anggota</label>
                    <input class="me-2" type="radio" name="is_head" value="0" id="dr_ri_u" @if(!$data->is_head) checked @endif>

                </div>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">TMT :</label>
            <div class="col-3">
                <input type="date" name="date" class="form-control" placeholder="Tanggal TMT" value="{{$data->start_date}}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="id" value="{{$data->id}}">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>
<script>
    $('.select-edit-history').select2({
        dropdownParent: $("#modalEditHistory"),
    });
</script>