<!-- Modal Add Attachment -->
<div class="modal fade" id="modalAddAttachment" tabindex="-1"
    aria-labelledby="modalAddAttachmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="formAddAttachment">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddAttachmentLabel">Tambah Lampiran</h5>
                </div>

                <div class="modal-body">
                    <div class="form-group row">
                        @if(!empty($skp))
                        <input type="hidden" id="skp_id" name="skp_id" value="{{$skp->id}}" />
                        @endif
                        <input type="hidden" id="attachment_category_id" name="attachment_category_id" />
                        <label class="col-sm-12 col-form-label">
                            <span id="attachment_category_name_label">/<span>
                        </label>
                        <div class="col-sm-12 mt-3">
                            <div class="input-group">
                                <button onclick="AddAttachment()" id="add_attachment" class="btn btn-success" type="button"><i class="ph ph-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3" id="work_attachment_container" data-count="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseAttachmentModal()">Tutup</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>