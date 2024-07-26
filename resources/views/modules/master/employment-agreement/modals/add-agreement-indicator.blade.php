<!-- Modal Add Agreement and Indicator -->
<div class="modal fade" id="modalAddAgreementIndicator" tabindex="-1"
    aria-labelledby="modalAddgreementIndicatorLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formAddAgreementIndicator">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddgreementIndicatorLabel">Tambah Indikator Sasaran</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <input type="hidden" id="agreement_indicator_id" name="agreement_indicator_id" />
                        <label class="col-sm-3 col-form-label">
                            <span>Sasaran PK</span>
                        </label>
                        <div class="col-sm-9">
                            <select id="indi_agreement_id" name="indi_agreement_id" class="form-control">
                                <option value="">== Pilih Sasaran ==</option>
                                @foreach($employmentAgreements as $employmentAgreement)
                                <option value="{{$employmentAgreement->id}}">{{$employmentAgreement->title}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text indi_agreement_id_err"></span>
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Nomor</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" id="agreement_indicator_code" name="agreement_indicator_code" class="form-control" />
                            <span class="text-danger error-text agreement_indicator_code_err"></span>
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Indikator</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" id="agreement_indicator_title" name="agreement_indicator_title" class="form-control" />
                            <span class="text-danger error-text agreement_indicator_title_err"></span>
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Target</span>
                        </label>
                        <div class="col-sm-9">
                            <input type="text" id="agreement_indicator_target" name="agreement_indicator_target" class="form-control" />
                            <span class="text-danger error-text agreement_indicator_target_err"></span>
                        </div>
                        <label class="col-sm-3 col-form-label">
                            <span>Perspektif</span>
                        </label>
                        <div class="col-sm-9">
                            <select id="agreement_indicator_perspective" name="agreement_indicator_perspective" class="form-control">
                                <option value="">== Pilih Perspektif ==</option>
                                @foreach($perspectiveIndicators as $perspectiveIndicator)
                                <option value="{{$perspectiveIndicator->id}}">{{$perspectiveIndicator->name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-text agreement_indicator_perspective_err"></span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseAgreementIndicatorModal()">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>