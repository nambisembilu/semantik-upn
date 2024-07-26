<?php

namespace App\Http\Controllers\Modules\Performance;

use App\Http\Controllers\Controller;
use App\Models\Master\Personal;
use App\Models\Transaction\SkpArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SkpArchiveController extends Controller
{
    private $route = "modules.performance.skp-archive.";
    private $menu_title = 'Kinerja - Arsip SKP';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $skp_archive = SkpArchive::where('personal_id', $personal->id)->where('period_id', session('period_id'))->where('work_unit_id', session('work_unit_id'))->first();
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'skp_archive' => $skp_archive
        ];
        return view($this->route . 'form', $param);
    }

    public function saveFile(Request $request)
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $period_id = session('period_id');
        $work_unit_id = session('work_unit_id');
        $type = $request->type;
        DB::beginTransaction();
        try {
            $file = $request->file('file_archive');
            if (!empty($file)) {
                $file_path = 'kinerja/skp-archive';
                // Allowed extension
                $validatedData = [
                    'file_archive' => 'file|max:2048|mimes:pdf',
                ];
                $validator = Validator::make($request->all(), $validatedData);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        DB::commit();
                        return Redirect::back()->withErrors([$error]);
                    }
                }
                $result_path = Storage::disk('public')->putFileAs($file_path, $file, $period_id . '-' . $personal->id . '-' . $type . '.' . $file->extension());
                $skp_archive = SkpArchive::where('personal_id', $personal->id)->where('period_id', $period_id)->where('work_unit_id', $work_unit_id)->first();
                if (empty($skp_archive)) {
                    $skp_archive = new SkpArchive();
                    $skp_archive->personal_id = $personal->id;
                    $skp_archive->period_id = $period_id;
                    $skp_archive->work_unit_id = $work_unit_id;
                }
                if ($type == 'rencana') {
                    $skp_archive->plan_file = $result_path;
                    $skp_archive->plan_status = 1;
                } else if ($type == 'eval') {
                    $skp_archive->eval_file = $result_path;
                    $skp_archive->eval_status = 1;
                } else {
                    $skp_archive->doc_eval_file = $result_path;
                    $skp_archive->doc_eval_status = 1;
                }
                $skp_archive->save();
                DB::commit();
                return redirect(route($this->route . 'index'))->with([
                    'toast-success' => 'success',
                    'success' => 'Data berhasil disimpan'
                ]);
            } else {
                DB::rollBack();
                return Redirect::back()->withErrors(['File tidak boleh kosong']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }
}

?>