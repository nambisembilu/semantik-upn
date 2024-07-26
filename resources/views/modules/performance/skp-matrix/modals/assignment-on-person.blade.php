<!-- Modal Add Attachment -->
<div class="modal fade" id="modalAssignmentOnPerson" tabindex="-1"
    aria-labelledby="modalAssignmentOnPersonLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="modal_cascade_content">
                <div class="modal-header">
                    <div class="container px-0">
                        <div class="row">
                            <div class="col-auto mr-auto">
                                <h5 class="modal-title" id="modalAssignmentOnPersonLabel">Informasi Cascading Internal</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="card border-secondary">
                        <div class="card-header">
                            <div class="container px-0">
                                <div class="row">
                                    <div class="col-auto mr-auto">
                                        <b>Nama : <span id="assignmentOnPersonName"></span></b>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive" id="devTableAssignmentOnPerson">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseAssignmentOnPersonModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
