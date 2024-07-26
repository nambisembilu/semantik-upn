<!-- Modal Add Work Plan and Indicator -->
<div class="modal fade" id="modalAddWorkPlan" tabindex="-1"
    aria-labelledby="modalAddWorkPlanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formAddWorkPlan">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddWorkPlanLabel">Tambah Hasil Kerja Tambahan / Utama Non Cascading</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        @if(!empty($skp))
                        <input type="hidden" id="skp_id" name="skp_id" value="{{$skp->id}}" />
                        @endif
                        <input type="hidden" id="skp_plan_id" name="skp_plan_id" />
                       
                        <label class="col-sm-3 col-form-label">
                            <span>Peran</span>
                        </label>
                        <div class="col-sm-9">
                            <input disabled type="text" id="position" class="form-control" value="{{session('work_position_name')}}" />
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Hasil Kerja</span>
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input id="work_plan_title" name="work_plan_title" type="text" class="form-control">
                                <span class="text-danger error-text work_plan_title_err"></span>
                            </div>
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Utama Non Cascading?</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="checkbox" id="is_main" name="is_main" class="mt-2" />
                        </div>
                        <label class="col-sm-3 col-form-label mt-3">
                            <span>Indikator</span>
                        </label>
                        <div class="col-sm-9 mt-3">
                            <div class="input-group">
                                <button onclick="AddIndicator()" id="add_indicator" class="btn btn-success" type="button"><i class="ph ph-plus"></i></button>
                            </div>
                            
                        </div>
                        <div class="col-sm-12" id="work_plan_indicator_container" data-count="0">
                        </div>
                    </div>
                </div>

                
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseWorkplanModal()">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>