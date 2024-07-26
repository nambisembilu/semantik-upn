<!-- Modal Update Task From -->
<div class="modal fade" id="modalNotification" tabindex="-1"
    aria-labelledby="modalNotificationLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title" id="modallNotificationLabel">Informasi Penting</h3>
            </div>
            <div class="modal-body">
                @if(!empty($skp))
                    @if($skp->application_status == 'Tidak Disetujui')
                    <p>
                        <h3 style="color:red;">Rencana SKP Anda tidak disetujui. Segera berkomunikasi dengan pejabat penilai kerja anda</h3>
                        <h3 style="color:red;">dan perbaiki rencana SKP dan ajukan ulang</h3>
                    </p>
                    @elseif($skp->application_status == 'Sudah Disetujui')
                        <h3 class="modal-title">Rencana SKP Anda sudah disetujui.</h3>
                        <h3 class="modal-title">Silahkan melakukan pengisian realisasi di menu Realisasi</h3>
                    @endif
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" onclick="CloseNotificationModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>