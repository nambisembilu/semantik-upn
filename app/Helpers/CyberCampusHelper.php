<?php

    namespace App\Helpers;

    use App\Models\Master\Personal;
    use App\Models\Master\Role;
    use App\Models\Master\WorkUnit;
    use App\Models\System\LogError;
    use App\Models\User;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Http;

    class CyberCampusHelper
    {

        public static function login($username, $pass, $request)
        {
            $result = [
                'status' => false,
                'message' => 'Error'
            ];
            $url = getenv('LOGIN_API_URL') . '/login';
            try {
                $curl = curl_init();
                $data = [
                    'LoginForm[username]' => $username,
                    'LoginForm[password]' => $pass,
                ];
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                $response = curl_exec($curl);
                $response = !$response ? [] : json_decode($response, true);
                if ($response['status'] == 'success') {
                    $data = $response['data'];
                    $result['status'] = true;
                    $result['message'] = $response['message'];
                } else {
                    $result['message'] = $response['message'];
                }
                return $result;
            } catch (\Exception $e) {
                $result['message'] = $e->getMessage();
                return $result;
            }

        }

        public static function checkUserDatabase($data, $request)
        {
            DB::beginTransaction();
            try {
                // If User Dosen
                $check_user = User::where('username', $data['username'])
                    ->first();
                if (!$check_user) {
                    $user = new User();
                    $user->role_id = Role::where('code', 'STAFF')
                        ->first()->id;
                    $user->username = $data['username'];
                    $user->name = $data['name'];
                    $user->email = $data['email'];
                    $user->password = bcrypt($data['password']);
                    $user->save();
                    $personal = new Personal();
                    $personal->user_id = $user->id;
                    $personal->work_unit_id = WorkUnit::where('ref_external_id', $data['pegawai']['id_unit_kerja'])
                        ->first()->id;
                    $personal->type = 'TENDIK';
                    $personal->type_status = $data['pegawai']['status_pegawai'];
                    $personal->work_id_number = $data['pegawai']['nip_pegawai'];
                    $personal->id_number = $data['pegawai']['no_ktp'];
                    $personal->name = $user->name;
                    $personal->gender = $data['gender'] == '2' ? 'Wanita' : 'Laki - Laki';
                    $grade = WorkUnit::where('ref_id', $data['pegawai']['id_golongan'])
                        ->first();
                    if (!empty($grade)) {
                        $personal->grade = $grade['grade'];
                        $personal->rank = $grade['rank'];
                    }
                    $personal->address = $data['pegawai']['alamat_pegawai'];
                    $personal->mobile = generatePhoneNumberCode($data['pegawai']['mobile_pegawai']);
                    $personal->save();
                } else {
                    $user = $check_user;
                    $user->password = bcrypt($data['password']);
                    $user->save();
                    $personal = Personal::where('user_id', $user->id)
                        ->first();
                    $personal->type = 'TENDIK';
                    $personal->type_status = $data['pegawai']['status_pegawai'];
                    $personal->employee_number = $data['pegawai']['nip_pegawai'];
                    $personal->id_number = $data['pegawai']['no_ktp'];
                    $personal->name = $user->name;
                    $personal->gender = $data['gender'] == '2' ? 'Wanita' : 'Laki - Laki';
                    $grade = WorkUnit::where('ref_id', $data['pegawai']['id_golongan'])
                        ->first();
                    if (!empty($grade)) {
                        $personal->grade = $grade['grade'];
                        $personal->rank = $grade['rank'];
                    }
                    $personal->address = $data['pegawai']['alamat_pegawai'];
                    $personal->mobile = self::generatePhoneNumberCode($data['pegawai']['mobile_pegawai']);
                    $personal->save();
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                LogError::create([
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'params' => json_encode($request->all()),
                    'stack_trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'url' => $request->fullurl(),
                    'ip_source' => $request->ip(),
                    'client_code' => '',
                    'user_agent' => $request->header('User-Agent'),
                    'error_code' => $e->getCode(),
                    'http_code' => '500',
                ]);
            }

        }

        public static function getAttendance($employee_number, $month, $year)
        {
            $result = [
                'status' => false,
                'message' => '',
                'data' => []
            ];
            $base_url = getenv('ATTENDANCE_API');
            $month = str_pad($month,2,'0',STR_PAD_LEFT);
            $url = $base_url . "/api_absen_7.php?nip={$employee_number}&bulan={$month}&tahun={$year}";
            $response = Http::get($url);
            $body = $response->json();
            if (!empty($body['status'])) {
                if ($body['status']) {
                    $result['status'] = true;
                    $result['message'] = 'Berhasil ambil data';
                    $result['data'] = [
                        'work_percent' => intval($body['prosen_absen']),
                        'work_day_office' => intval($body['hari_wfo']),
                        'work_day_home' => intval($body['hari_wfh']),
                        'absent' => intval($body['harikerja_tdkmasuk']),
                        'absent_permission' => intval($body['tidak_hadir_dg_ijin']),
                        'absent_leave' => intval($body['tidak_hadir_cuti']),
                        'absent_4day' => intval($body['tidak_masuk_4hari']),
                        'absent_5day' => intval($body['tidak_masuk_5hari']),
                        'work_day' => intval($body['jum_hari_kerja']),
                        'late' => intval($body['masuk_terlambat']),
                        'return_before_time' => intval($body['masuk_pulang_mendahului']),
                    ];
                } else {
                    $result['message'] = 'Gagal ambil data';
                }
            } else {
                $result['message'] = 'Gagal konek ke sistem absen';
            }
            return $result;
        }


    }
