<!-- Modal Update Task From -->
<div class="modal fade" id="modalUpdateRealizationValue" tabindex="-1"
    aria-labelledby="modalUpdateRealizationValueLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formUpdateRealizationValue">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUpdateRealizationValueLabel">Update Realisasi</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        @if(!empty($skpRealization))
                        <input type="hidden" id="skp_plan_realization_id" name="skp_plan_realization_id" />
                        @endif
                        <label class="col-sm-3 col-form-label">
                            <span>Hasil Kerja</span>
                        </label>
                        <div class="col-sm-9">
                            <input style="background-color: #e8e8e8;" type="text" id="skp_plan" name="skp_plan" class="form-control" readonly />
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Realisasi</span>
                        </label>
                        <div class="col-sm-9">
                            <textarea id="realization" name="realization" class="form-control" cols="40" rows="5" required></textarea>
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Bukti Link Pendukung</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" id="supporting_evidence" name="supporting_evidence" class="form-control" />
                        </div>
                    </div>
                </div>

                
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseUpdateRealizationValueModal()">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>