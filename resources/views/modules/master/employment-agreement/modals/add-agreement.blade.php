<!-- Modal Add Agreement -->
<div class="modal fade" id="modalAddAgreement" tabindex="-1"
    aria-labelledby="modalAddAgreementLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formAddAgreement">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgreementLabel">Tambah Sasaran</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <input type="hidden" id="agreement_id" name="agreement_id" />
                        
                        <label class="col-sm-3 col-form-label">
                            <span>Tahun</span>
                        </label>
                        <div class="col-sm-9">
                            <input style="background-color: #e8e8e8;" 
                            disabled type="text" id="period_year" class="form-control" value="{{$period->year}}" />
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Nomor</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" id="agreement_no" name="agreement_no" class="form-control" />
                            <span class="text-danger error-text agreement_no_err"></span>
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Sasaran</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" id="agreement_title" name="agreement_title" class="form-control" />
                            <span class="text-danger error-text agreement_title_err"></span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseAgreementModal()">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>