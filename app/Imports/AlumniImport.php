<?php

namespace App\Imports;

use App\Models\Master\Fakultas;
use App\Models\Master\Negara;
use App\Models\Master\ProgramStudi;
use App\Models\Master\Universitas;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AlumniImport implements ToCollection
{
    private $data = [];

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $line_items = [];
        foreach ($rows as $index => $row) {
            if ($index > 0) {
                $nim = $row[0];
                $nama = $row[1];
                $universitas = $row[2];
                $fakultas = $row[3];
                $prodi = $row[4];
                $negara = $row[5];
                $tempat_kerja = $row[6];
                $status = 1;
                $message_status = '';
                $cek_universitas = [];
                $cek_fakultas = [];
                $cek_prodi = [];
                $cek_negara = [];
                $cek_universitas = Universitas::whereRaw("upper(nm_universitas) like '%" . strtoupper($universitas) . "%'")->first();
                if ($cek_universitas) {
                    $cek_fakultas = Fakultas::where('id_universitas', $cek_universitas->id_universitas)->whereRaw("upper(nm_fakultas) like '%" . strtoupper($fakultas) . "%'")->first();
                    if ($cek_fakultas) {
                        $cek_prodi = ProgramStudi::where('id_fakultas', $cek_fakultas->id_fakultas)->whereRaw("upper(nm_prodi) like '%" . strtoupper($prodi) . "%'")->first();
                    }
                }
                $cek_negara = Negara::whereRaw("upper(nm_negara) like '%" . strtoupper($negara) . "%'")->first();

                if (empty($cek_prodi)) {
                    $message_status .= 'Program Studi ' . $prodi . ' tidak ditemukan.<br/>';
                }
                if (empty($cek_negara)) {
                    $message_status .= 'Negara ' . $negara . ' tidak ditemukan.<br/>';
                }
                if (!empty($message_status)) {
                    $status = 0;
                } else {
                    $message_status = 'Bisa disimpan';
                }
                $line_items[] = [
                    'nim' => $nim,
                    'nama' => $nama,
                    'id_prodi' => !empty($cek_prodi) ? $cek_prodi->id_prodi : '',
                    'universitas' => !empty($cek_universitas) ? $cek_universitas->nm_universitas : '',
                    'prodi' => !empty($cek_prodi) ? $cek_prodi->nm_prodi : '',
                    'fakultas' => !empty($cek_prodi) ? $cek_prodi->fakultas->nm_fakultas : '',
                    'id_negara' => !empty($cek_negara) ? $cek_negara->id_negara : '',
                    'nm_negara' => !empty($cek_negara) ? $cek_negara->nm_negara : '',
                    'nm_tempat_kerja' => $tempat_kerja,
                    'status' => $status,
                    'message' => $message_status
                ];

            }
        }
        $this->data = $line_items;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
