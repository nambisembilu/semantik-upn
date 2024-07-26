<?php

    namespace App\Http\Controllers\Modules\Account;

  
use App\Http\Controllers\Controller;
use App\Models\Master\Periode;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\WorkPosition;
use App\Models\Master\WorkRank;
use App\Models\Master\WorkUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
    

    class LoginAsOtherController extends Controller
    {
        private $route = "modules.account.loginasother.";

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
            $param = [
                'route' => $this->route,
            ];
            return view($this->route . 'index', $param);
        }

        public function datatable(Request $request)
        {
            if(!empty(session("role_name")))
            {
                if(session("role_name") == "Superadmin")
                {
                    $data = DB::select("
                    select p.id, p.name, p.work_id_number, wr.grade_code from personals p
                    join work_ranks wr on wr.id=p.work_rank_id
                    where p.deleted_at is null 
                    ");

                    return DataTables::of($data)->addColumn('action', function ($d) {
                        $html = '
                            <div class="d-flex justify-content-center">
                                <form action="' . route($this->route . 'post') . '" method="post">
                                    <input type="hidden" name="id" value="' . $d->id . '"/>
                                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                                    <button type="submit" onclick="loginAsOther(event)" class="btn btn-sm btn-success btn-icon">
                                    <i class="ph ph-sign-in"></i>
                                    </button>
                                </form>
                            </div>';
                        return $html;
                    })->rawColumns([
                        'action'
                    ])->make(true);
                }
                else if(session("role_name") == "SuperadminUK")
                {
                    $data = DB::select("
                    select p.id, p.name, p.work_id_number, wr.grade_code from personals p
                    join work_ranks wr on wr.id=p.work_rank_id
                    join personal_work_units pwu on pwu.personal_id=p.id
                    where p.deleted_at is null and pwu.root_work_unit_id='".session("root_work_unit_id")."' 
                    group by p.id, p.name, p.work_id_number, wr.grade_code
                    ");

                    return DataTables::of($data)->addColumn('action', function ($d) {
                        $html = '
                            <div class="d-flex justify-content-center">
                                <form action="' . route($this->route . 'post') . '" method="post">
                                    <input type="hidden" name="id" value="' . $d->id . '"/>
                                    <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                                    <button type="submit" onclick="loginAsOther(event)" class="btn btn-sm btn-success btn-icon">
                                    <i class="ph ph-sign-in"></i>
                                    </button>
                                </form>
                            </div>';
                        return $html;
                    })->rawColumns([
                        'action'
                    ])->make(true);
                }
            }
        }

        public function post(Request $request)
        {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            $personal = Personal::find($request->id);
            $check_user = User::where('id', $personal->user_id)->first();
        
            Auth::login($check_user);

            // Authentication passed...
            $user = Auth::user();
            $personal = $user->personal;
            session([
                'user_id' => $user->id,
                'personal_id' => $personal->id,
                'user_name' => $user->name
            ]);

            return redirect('/');
        }
    }
