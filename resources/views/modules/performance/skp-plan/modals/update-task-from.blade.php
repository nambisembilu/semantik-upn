<!-- Modal Update Task From -->
<div class="modal fade" id="modalUpdateTaskFrom" tabindex="-1"
    aria-labelledby="modalUpdateTaskFromLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formUpdateTaskFrom">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modallUpdateTaskFromLabel">Edit Penugasan Dari</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        @if(!empty($skp))
                        <input type="hidden" id="skp_work_plan_id" name="skp_work_plan_id" />
                        @endif
                        <label class="col-sm-3 col-form-label">
                            <span>Hasil Kerja</span>
                        </label>
                        <div class="col-sm-9">
                            <input style="background-color: #e8e8e8;" type="text" id="task_from_skp_plan" name="task_from_skp_plan" class="form-control" />
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Penugasan Dari (Default)</span>
                        </label>
                        <div class="col-sm-9">
                            <input style="background-color: #e8e8e8;" type="text" id="task_from_default" name="task_from_default" class="form-control" />
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Penugasan Dari</span>
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <button onclick="CopyTaskFromDefault()" class="btn btn-primary" type="button"><i class="ph ph-copy"></i></button>
                                <input type="text" id="get_task_from" name="get_task_from" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseUpdateTaskFromModal()">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>