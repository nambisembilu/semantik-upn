<!-- Modal Update Task From -->
<div class="modal fade" id="modalAttachmentTextTemplate" tabindex="-1"
    aria-labelledby="modalAttachmentTextTemplateLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAttachmentTextTemplateLabel">Template Lampiran</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        <input type="hidden" id="attachment_key" name="attachment_key" />
                        <div class="col-sm-12">
                            <select id="attachment_template" name="attachment_template" class="form-control">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseAttachmentTextTemplateModal()">Tutup</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="SubmitAttachmentTextTemplate()">Pilih</button>
                </div>
        </div>
    </div>
</div>