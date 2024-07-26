<?php

    namespace App\Http\Controllers\Modules\Account;

    use App\Http\Controllers\Controller;
    use App\Models\Master\Personal;
    use App\Models\Master\Role;
    use App\Models\Master\User;
    use App\Models\Master\WorkPosition;
    use App\Models\Master\WorkPositionUnit;
    use App\Models\Master\WorkRank;
    use App\Models\System\LogError;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Redirect;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    

    class ProfileController extends Controller
    {
        private $route = "modules.account.profile.";

        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct()
        {
            $this->middleware('auth');
        }

        /**
         * Show the application dashboard.
         *
         * @return \Illuminate\Contracts\Support\Renderable
         */
        public function index()
        {
            $user = Auth::user();
            $data = Personal::find(session('personal_id'));
            $work_position = WorkPosition::where('id', $data->work_position_id)->first();
            $work_position_units = WorkPositionUnit::get();
            $work_ranks = WorkRank::get();
            $roles = Role::get();
            $employee_types = Personal::select('employee_type as value', 'employee_type as name')->groupBy('employee_type')->get();
            $lead_datas = Personal::where('work_position_id', $work_position->parent_id)->get();

            $params = [
                "user" => $user,
                "data" => $data,
                "work_position_units" => $work_position_units,
                "work_ranks" => $work_ranks,
                "roles" => $roles,
                "employee_types" => $employee_types,
                "lead_datas" => $lead_datas,
                "route" => $this->route
            ];
            return view($this->route . 'form', $params);
        }

        public function update(Request $request)
        {
            request()->validate([
                'name' => 'required',
            ]);
            $id = $request->id;

            DB::beginTransaction();
            try {
                $data = Personal::find($id);
                $user = User::find($data->user_id);
                $user->name = $request->name;
                $user->save();

                $data->name = strtoupper($request->name);
                $data->gender = $request->gender;
                $data->work_id_number = $request->work_id_number;
                $data->lead_id = $request->lead_id;
                $data->save();

                DB::commit();
                return redirect(route($this->route . 'index'))->with([
                    'toast-success' => "success",
                    'success' => 'Data berhasil disimpan'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                LogError::create([
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'params' => json_encode([$request->all()]),
                    'stack_trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'url' => $request->fullurl(),
                    'ip_source' => $request->ip(),
                    'client_code' => '',
                    'user_agent' => $request->header('User-Agent'),
                    'error_code' => $e->getCode(),
                    'http_code' => '500',
                ]);
                return Redirect::back()->withErrors(['Gagal menyimpan, terdapat kesalahan pengisian data']);
            }
        }
        public function newPassword()
        {
            $user = user::find(session('personal_id'));
            $data = Personal::find(session('personal_id'));

            $params = [
                "user" => $user,
                "data" => $data,
                "route" => $this->route
            ];
            return view($this->route . 'new-password', $params);
        }

        public function updatePassword(Request $request)
        {
            request()->validate([
                'name' => 'nama harus di isi',
            ]);
            $user = User::find($request->id);

            if(!Hash::check($request->password, $user->password)){
                return Redirect::back()->withErrors("Password Lama tidak sesuai ");
            }
            if($request->newPassword != $request->confirmPassword){
                return Redirect::back()->withErrors("Konfirmasi Password Baru tidak sesuai ");
            }
            
            DB::beginTransaction();
            try {

                $newPassword = Hash::make($request->newPassword);
                $user->password = $newPassword;
                
                $user->save();
                DB::commit();
                return redirect('/')->with([
                    'toast-success' => "success",
                    'success' => 'Password berhasil dirubah'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                LogError::create([
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'params' => json_encode([$request->all()]),
                    'stack_trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'url' => $request->fullurl(),
                    'ip_source' => $request->ip(),
                    'client_code' => '',
                    'user_agent' => $request->header('User-Agent'),
                    'error_code' => $e->getCode(),
                    'http_code' => '500',
                ]);
                return Redirect::back()->withErrors(['Gagal menyimpan, terdapat kesalahan pengisian data']);
            }
        }
    }
