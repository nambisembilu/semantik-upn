<!-- Modal Update Task From -->
<div class="modal fade" id="modalBehaviorTextTemplate" tabindex="-1"
    aria-labelledby="modalBehaviorTextTemplateLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBehaviorTextTemplateLabel">Template Umpan Balik Perilaku</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <input type="hidden" id="behavior_realization_key" name="behavior_realization_key" />
                        <div class="col-sm-12">
                            <select name="behavior_template" class="form-control">
                                <option value=''>== Pilih Template ==</option>
                                @foreach($feedbackBehaviorCategories as $feedbackBehaviorCategory)
                                <optgroup label="{{$feedbackBehaviorCategory->name}}">
                                    @foreach($feedbackBehaviorCategory->feedbackBehaviorTextTemplates as $textTemplate)
                                    <option value="{{$textTemplate->name}}">{{$textTemplate->name}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseBehaviorTextTemplateModal()">Tutup</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="SubmitBehaviorTextTemplate()">Simpan</button>
                </div>
        </div>
    </div>
</div>