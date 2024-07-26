<form action="{{route('modules.performance.employee-team.saveTim')}}" method="post">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Tambah Tim</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="work_unit" class="col-form-label">Nama Tim:</label>
            <input type="text" class="form-control" name="work_unit" readonly value="{{$work_unit->name}}">
        </div>
        <div class="mb-3">
            <label for="personal_id" class="col-form-label">Nama @if($is_head) Ketua @else Anggota @endif</label>
            <select class="form-control select" name="personal_id">
                @foreach($staffs as $staff)
                    <option value="{{$staff->id}}">({{$staff->work_id_number}}) {{$staff->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="is_head" value="{{$is_head}}"/>
        <input type="hidden" name="work_unit_id" value="{{$work_unit->id}}"/>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit"  class="btn btn-primary">Simpan</button>
    </div>
</form>