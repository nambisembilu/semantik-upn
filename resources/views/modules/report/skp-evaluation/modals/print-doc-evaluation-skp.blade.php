<!-- Modal Add Attachment -->
<div class="modal fade" id="modalPrintDocEvaluationSKP" tabindex="-1"
    aria-labelledby="modalPrintDocEvaluarionSKPLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form target="_blank" id="modal-print-doc-form" action="{{route('modules.report.skp-evaluation.print_doc_evaluation_skp')}}" method="get">
                @csrf
            <input type="hidden" name="doc_pwu_id" id="doc_pwu_id" />
            <div class="modal-body px-4">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="doc-setting-tab" data-bs-toggle="tab" data-bs-target="#doc-setting" type="button" role="tab" aria-controls="doc-setting" aria-selected="true">Setting</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="doc-employee-tab" data-bs-toggle="tab" data-bs-target="#doc-employee" type="button" role="tab" aria-controls="doc-employee" aria-selected="false">Pegawai yang Dinilai</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="doc-officer-tab" data-bs-toggle="tab" data-bs-target="#doc-officer" type="button" role="tab" aria-controls="doc-officer" aria-selected="false">Pejabat Penilai</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="doc-upper-tab" data-bs-toggle="tab" data-bs-target="#doc-upper" type="button" role="tab" aria-controls="doc-upper" aria-selected="false">Atasan Pejabat Penilai</button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
            <div class="tab-pane active" id="doc-setting" role="tabpanel" aria-labelledby="doc-setting-tab">
                <div class="form-group row">
                    <label for="date_setting" class="col-sm-4 col-form-label">Tempat dan Tanggal Cetak Pegawai</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="date_setting_employee" name="date_setting_employee" required="" >
                    </div>
                    <label for="date_setting" class="col-sm-4 col-form-label">Tempat dan Tanggal Cetak PPK</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="date_setting_officer" name="date_setting_officer" required="" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="period_name" class="col-sm-4 col-form-label">Periode Kerja</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="period_name" name="period_name" required="" value="{{$period->description}}">
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="doc-employee" role="tabpanel" aria-labelledby="doc-employee-tab">
                <div class="form-group row">
                    <label for="employee_name" class="col-sm-4 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input style="background-color: #e8e8e8;" type="text" class="form-control" id="doc_employee_name" name="doc_employee_name" required="" readonly="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="employee_nip" class="col-sm-4 col-form-label">NIP</label>
                    <div class="col-sm-8">
                        <input style="background-color: #e8e8e8;" type="text" class="form-control" id="doc_employee_nip" name="doc_employee_nip" required="" readonly="" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="employee_rank" class="col-sm-4 col-form-label">Pangkat / Gol.</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="doc_employee_rank" name="doc_employee_rank" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="employee_position" class="col-sm-4 col-form-label">Jabatan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="doc_employee_position" name="doc_employee_position" required="" >
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="employee_work_unit" class="col-sm-4 col-form-label">Unit Kerja</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="doc_employee_work_unit" name="doc_employee_work_unit" required="" >
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="doc-officer" role="tabpanel" aria-labelledby="doc-officer-tab">
            <div class="form-group row">
                <label for="asessor_name" class="col-sm-4 col-form-label">Nama</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_asessor_name" name="doc_asessor_name" required="" >
                </div>
            </div>
            <div class="form-group row">
                <label for="asessor_nip" class="col-sm-4 col-form-label">NIP</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_asessor_nip" name="doc_asessor_nip" required="">
                </div>
            </div>
            <div class="form-group row">
                <label for="asessor_rank" class="col-sm-4 col-form-label">Pangkat / Gol.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_asessor_rank" name="doc_asessor_rank" required="" >
                </div>
            </div>
            <div class="form-group row">
                <label for="asessor_position" class="col-sm-4 col-form-label">Jabatan</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_asessor_position" name="doc_asessor_position" required="">
                </div>
            </div>
            <div class="form-group row mb-4">
                <label for="asessor_work_unit" class="col-sm-4 col-form-label">Unit Kerja</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_asessor_work_unit" name="doc_asessor_work_unit" required="" >
                </div>
            </div>
        </div>
        <div class="tab-pane" id="doc-upper" role="tabpanel" aria-labelledby="doc-upper-tab">
            <div class="form-group row">
                <label for="upper_asessor_name" class="col-sm-4 col-form-label">Nama</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_upper_asessor_name" name="doc_upper_asessor_name" >
                </div>
            </div>
            <div class="form-group row">
                <label for="upper_asessor_nip" class="col-sm-4 col-form-label">NIP</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_upper_asessor_nip" name="doc_upper_asessor_nip" required="">
                </div>
            </div>
            <div class="form-group row">
                <label for="upper_asessor_rank" class="col-sm-4 col-form-label">Pangkat / Gol.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_upper_asessor_rank" name="doc_upper_asessor_rank" required="">
                </div>
            </div>
            <div class="form-group row">
                <label for="upper_asessor_position" class="col-sm-4 col-form-label">Jabatan</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_upper_asessor_position" name="doc_upper_asessor_position" required="">
                </div>
            </div>
            <div class="form-group row mb-4">
                <label for="upper_asessor_work_unit" class="col-sm-4 col-form-label">Unit Kerja</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="doc_upper_asessor_work_unit" name="doc_upper_asessor_work_unit" required="">
                </div>
            </div>
        </div>
</div>

                <div class="alert alert-info mt-2 mb-0" role="alert">Pastikan semua field terisi.</div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary px-3" onclick="ClosePrintDocEvaluationSKPModal()">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary px-3">Cetak</button>
            </div>

            </form>
        </div>
    </div>
</div>