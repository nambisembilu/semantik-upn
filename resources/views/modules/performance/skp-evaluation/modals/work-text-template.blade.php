<!-- Modal Update Task From -->
<div class="modal fade" id="modalWorkTextTemplate" tabindex="-1"
    aria-labelledby="modalWorkTextTemplateLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalWorkTextTemplateLabel">Template Umpan Balik Hasil Kerja</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <input type="hidden" id="plan_realization_key" name="plan_realization_key" />
                        <input type="hidden" id="plan_realization_is_main" name="plan_realization_is_main" />
                        <div class="col-sm-12">
                            <select name="work_template" class="form-control">
                                <option value=''>== Pilih Template ==</option>
                                @foreach($feedbackWorkCategories as $feedbackWorkCategory)
                                <optgroup label="{{$feedbackWorkCategory->name}}">
                                    @foreach($feedbackWorkCategory->feedbackWorkTextTemplates as $textTemplate)
                                    <option value="{{$textTemplate->name}}">{{$textTemplate->name}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseWorkTextTemplateModal()">Tutup</button>
                    <button id="btnSubmitWorkTextTemplate" type="button" class="btn btn-sm btn-primary" onclick="SubmitWorkTextTemplate()">Simpan</button>
                </div>
        </div>
    </div>
</div>