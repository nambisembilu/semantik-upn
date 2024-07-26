<!-- Modal Add Attachment -->
<div class="modal fade" id="modalCreateInternalAssignment" tabindex="-1"
    aria-labelledby="modalCreateInternalAssignmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div id="modal_cascade_content">
                <div class="modal-header">
                    <div class="container px-0">
                        <div class="row">
                            <div class="col-auto mr-auto">
                                <h5 class="modal-title" id="modalCreateInternalAssignmentLabel">Tambah Cascading Internal</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="card mb-3 border-secondary">
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <footer class="blockquote-footer">
                                    <span id="work_indicator_title"></span>
                                </footer>
                            </blockquote>
                        </div>
                    </div>

                    <div class="card border-secondary">
                        <div class="card-header">
                            <div class="container px-0">
                                <div class="row">
                                    <div class="col-auto mr-auto">
                                        <b>Pilih Anggota Tim Kerja</b>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-sm btn-primary" title="Uncheck All"
                                            onclick="UncheckAllAssignee()" id="btn_uncheck_all"
                                            style="display: none;">
                                            <i class="ph ph-x"></i>&nbsp;&nbsp;Uncheck All
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" title="Check All"
                                            onclick="CheckAllAssignee()" id="btn_check_all">
                                            <i class="ph ph-check"></i>&nbsp;&nbsp;Check All
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" title="Simpan Cascading"
                                            onclick="SaveInternalAssignment(55371)" id="btn_save_assignment" data-planid="" data-indiid="">
                                            <i class="ph ph-plus"></i>&nbsp;&nbsp;Tambah Cascading
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-3">

                            <div class="table-responsive">
                                <div id="form_cascade_internal_table_wrapper" class="dataTables_wrapper no-footer">
                                    <table class="table table-hover table-bordered table-stripped" id="tableCreateAssignment" style="width: 100%">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Pegawai</th>
                                            <th>Jabatan & Unit Kerja</th>
                                            <th>Peran</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="CloseInternalAssignmentModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
