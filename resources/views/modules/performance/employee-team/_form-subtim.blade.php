<form action="{{route('modules.performance.employee-team.addSubteam')}}" method="post">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Sub Tim Kerja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">

        <div class="mb-3">
            <label class="form-label">Unit Kerja :</label>
            <input type="text" name="work_unit" class="form-control" placeholder="" value="{{$work_unit->name}}">
        </div>
        <div class="mb-3">
            <label class="form-label">Subtim :</label>
            <input type="text" name="name" class="form-control" placeholder="Nama Subtim" />
        </div>
    </div>
    <div class="modal-footer">
        <input type="hidden" name="work_unit_id" value="{{$work_unit->id}}">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>