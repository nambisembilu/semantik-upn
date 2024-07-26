<?php

namespace App\Http\Controllers\Modules\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Transaction\EmploymentAgreement;
use App\Models\Transaction\EmploymentAgreementIndicator;
use App\Models\Transaction\EmploymentAgreementIndicatorPerspective;
use App\Models\Master\PerspectiveIndicator;
use App\Models\Master\Personal;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\System\LogError;

class EmploymentAgreementController extends Controller
{
    private $route = "modules.master.employment-agreement.";
    private $view = "modules.master.employment-agreement.";
    private $menu_title = 'Master - Perjanjian Kinerja';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $employmentAgreements = EmploymentAgreement::with(['employmentAgreementIndicators' => function($query){
            $query->orderBy('id', 'asc');
        }, 'employmentAgreementIndicators.employmentAgreementIndicatorPerspectives.perspectiveIndicator'])
        ->where(['period_id' => session('period_id')])
        ->orderBy('no', 'asc')->get();

        $perspectiveIndicators = PerspectiveIndicator::get();
        $period = Period::find(session('period_id'));

        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'employmentAgreements' => $employmentAgreements,
            'period' => $period,
            'perspectiveIndicators' => $perspectiveIndicators
        ];
        return view($this->view . 'index', $param);
    }

    public function create_employment_agreement(Request $request)
    {
        $customMessages = [
        'agreement_no.required'=> 'Nomor harus diisi',
        'agreement_title.required'   => 'Sasaran harus diisi',
        ];

        $validator = Validator::make($request->all(), [
            'agreement_no'			=> 'required',
            'agreement_title'			=> 'required',

        ], $customMessages);

        if (!$validator->fails()) 
        {
            DB::beginTransaction();
            try
            {
                $user = Auth::user();
                $personal = Personal::where('user_id', $user->id)->first();
                $period = Period::find(session('period_id'));
                if($request->get('agreement_id') > 0)
                {
                    $employmentAgreement = EmploymentAgreement::find($request->get('agreement_id'));
                    $employmentAgreement->title = $request->get('agreement_title');
                    $employmentAgreement->no = $request->get('agreement_no');
                    $employmentAgreement->save();

                }
                else
                {
                    $employmentAgreement = EmploymentAgreement::create([
                        'title' => $request->get('agreement_title'),
                        'no' => $request->get('agreement_no'),
                        'get_task_from' => 'Sekretaris Jenderal',
                        'personal_id' => $personal->id,
                        'period_id' => $period->id
                    ]);
                }
                
                
                DB::commit();
                
                return response()->json([
                    'status' => 1,
                    'message' => $employmentAgreement,
                ]);
            }
            catch(\Exception $e)
            {
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
    
                return response()->json([
                    'status' => 0,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'error'=>$validator->errors()]);
        }
    }

    public function create_employment_agreement_indicator(Request $request)
    {
        $customMessages = [
            'indi_agreement_id.required'=> 'Sasaran PK harus diisi',
            'agreement_indicator_code.required'=> 'Nomor harus diisi',
            'agreement_indicator_title.required'   => 'Indikator harus diisi',
            'agreement_indicator_target.required'=> 'Target harus diisi',
            'agreement_indicator_perspective.required'   => 'Perspektif harus diisi',
            ];
    
            $validator = Validator::make($request->all(), [
                'indi_agreement_id'			=> 'required',
                'agreement_indicator_code'			=> 'required',
                'agreement_indicator_title'			=> 'required',
                'agreement_indicator_target'			=> 'required',
                'agreement_indicator_perspective'			=> 'required',
    
            ], $customMessages);
    
            if (!$validator->fails()) 
            {
                DB::beginTransaction();
                try
                {
                    $user = Auth::user();
                    $personal = Personal::where('user_id', $user->id)->first();
    
                    if($request->get('agreement_indicator_id') > 0)
                    {
                        $employmentAgreementIndicator = EmploymentAgreementIndicator::find($request->get('agreement_indicator_id'));
                        $employmentAgreementIndicator->employment_agreement_id = $request->get('indi_agreement_id');
                        $employmentAgreementIndicator->title = $request->get('agreement_indicator_title');
                        $employmentAgreementIndicator->code = $request->get('agreement_indicator_code');
                        $employmentAgreementIndicator->target = $request->get('agreement_indicator_target');
                        $employmentAgreementIndicator->save();
                        
                        $employmentAgreementIndicatorPerspective = EmploymentAgreementIndicatorPerspective::where([
                            ['employment_agreement_indicator_id', '=', $request->get('agreement_indicator_id')]])->first();

                        if(!empty($employmentAgreementIndicatorPerspective))
                        {
                            $employmentAgreementIndicatorPerspective->perspective_indicator_id = $request->get('agreement_indicator_perspective');
                        }
    
                    }
                    else
                    {
                        $newEmploymentAgreementIndicator = EmploymentAgreementIndicator::create([
                            'employment_agreement_id' => $request->get('indi_agreement_id'),
                            'title' => $request->get('agreement_indicator_title'),
                            'code' =>  $request->get('agreement_indicator_code'),
                            'target' =>  $request->get('agreement_indicator_target'),
                        ]);

                        EmploymentAgreementIndicatorPerspective::create([
                            'perspective_indicator_id' => $request->get('agreement_indicator_perspective'),
                            'employment_agreement_indicator_id' => $newEmploymentAgreementIndicator->id
                        ]);
                    }
                    
                    
                    DB::commit();
                    
                    return response()->json([
                        'status' => 1,
                        'message' => 'Sasaran indikator berhasil diupdate',
                    ]);
                }
                catch(\Exception $e)
                {
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
        
                    return response()->json([
                        'status' => 0,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            else
            {
                return response()->json(['status' => 0, 'error'=>$validator->errors()]);
            }
    }

    public function delete_employment_agreement(Request $request)
    {
        try
        {
            $employmentAgreement = EmploymentAgreement::find($request->get('id'));
            $employmentAgreement->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Sasaran berhasil dihapus',
            ]);
        }
        catch(\Exception $e)
        {
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

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete_employment_agreement_indicator(Request $request)
    {
        try
        {
            $employmentAgreementIndicatorPerspective = EmploymentAgreementIndicatorPerspective::where([
                ['employment_agreement_indicator_id', '=', $request->get('id')]])->first();
            $employmentAgreementIndicatorPerspective->delete();

            $employmentAgreementIndicator = EmploymentAgreementIndicator::find($request->get('id'));
            $employmentAgreementIndicator->delete();
            return response()->json([
                'status' => 1,
                'message' => 'Sasaran indikator berhasil dihapus',
            ]);
        }
        catch(\Exception $e)
        {
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

            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }
}

?>