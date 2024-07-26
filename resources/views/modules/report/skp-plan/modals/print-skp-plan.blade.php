<!-- Modal Add Attachment -->
<div class="modal fade" id="modalPrintSKP" tabindex="-1"
    aria-labelledby="modalPrintSKPLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form target="_blank" id="modal-print-form" action="{{route('modules.report.skp-plan.print_skp')}}" method="get">
                @csrf
                <input type="hidden" name="pwu_id" id="pwu_id" />
            <div class="modal-body px-4">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="setting-tab" data-bs-toggle="tab" data-bs-target="#setting" type="button" role="tab" aria-controls="setting" aria-selected="true">Setting</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="employee-tab" data-bs-toggle="tab" data-bs-target="#employee" type="button" role="tab" aria-controls="employee" aria-selected="false">Pegawai yang Dinilai</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="officer-tab" data-bs-toggle="tab" data-bs-target="#officer" type="button" role="tab" aria-controls="officer" aria-selected="false">Pejabat Penilai</button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
            <div class="tab-pane active" id="setting" role="tabpanel" aria-labelledby="setting-tab">
                <div class="form-group row">
                    <label for="date_setting" class="col-sm-4 col-form-label">Tempat dan Tanggal Cetak</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="date_setting" name="date_setting" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="period_name" class="col-sm-4 col-form-label">Periode Kerja</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="period_name" name="period_name" required="" value="{{$period->description}}">
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="employee" role="tabpanel" aria-labelledby="employee-tab">
                <div class="form-group row">
                    <label for="employee_name" class="col-sm-4 col-form-label">Nama</label>
                    <div class="col-sm-8">
                        <input style="background-color: #e8e8e8;" type="text" class="form-control" id="employee_name" name="employee_name" required="" readonly="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="employee_nip" class="col-sm-4 col-form-label">NIP</label>
                    <div class="col-sm-8">
                        <input style="background-color: #e8e8e8;" type="text" class="form-control" id="employee_nip" name="employee_nip" required="" readonly="" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="employee_rank" class="col-sm-4 col-form-label">Pangkat / Gol.</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="employee_rank" name="employee_rank" required="" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="employee_position" class="col-sm-4 col-form-label">Jabatan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="employee_position" name="employee_position" required="">
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label for="employee_work_unit" class="col-sm-4 col-form-label">Unit Kerja</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="employee_work_unit" name="employee_work_unit" required="">
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="officer" role="tabpanel" aria-labelledby="officer-tab">
            <div class="form-group row">
                <label for="asessor_name" class="col-sm-4 col-form-label">Nama</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="asessor_name" name="asessor_name" required="">
                </div>
            </div>
            <div class="form-group row">
                <label for="asessor_nip" class="col-sm-4 col-form-label">NIP</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="asessor_nip" name="asessor_nip" required="">
                </div>
            </div>
            <div class="form-group row">
                <label for="asessor_rank" class="col-sm-4 col-form-label">Pangkat / Gol.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="asessor_rank" name="asessor_rank" required="">
                </div>
            </div>
            <div class="form-group row">
                <label for="asessor_position" class="col-sm-4 col-form-label">Jabatan</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="asessor_position" name="asessor_position" required="">
                </div>
            </div>
            <div class="form-group row mb-4">
                <label for="asessor_work_unit" class="col-sm-4 col-form-label">Unit Kerja</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="asessor_work_unit" name="asessor_work_unit" required="">
                </div>
            </div>
                </div>
            </div>

                <div class="alert alert-info mt-2 mb-0" role="alert">Pastikan semua field terisi.</div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary px-3" onclick="ClosePrintSKPModal()">Batal</button>
                <button type="submit" class="btn btn-sm btn-primary px-3">Cetak</button>
            </div>

            </form>
        </div>
    </div>
</div>