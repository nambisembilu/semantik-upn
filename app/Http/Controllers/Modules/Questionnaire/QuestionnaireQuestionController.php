<?php

namespace App\Http\Controllers\Modules\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Master\Period;
use App\Models\Master\Personal;
use App\Models\Master\Questionnaire;
use App\Models\Transaction\SkpArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\Facades\DataTables;

class QuestionnaireQuestionController extends Controller
{
    private $route = "modules.questionnaire.question.";
    private $menu_title = 'Kuisioner - Pertanyaan';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $questionnaires = Questionnaire::get();
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'type' => 'plan',
            '$questionnaires' => $questionnaires
        ];
        return view($this->route . 'index', $param);
    }

    public function create()
    {
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'data' => [],
        ];
        return view($this->route . 'form', $param);
    }

    public function store(Request $request)
    {
        request()->validate($this->validate_fields);
        DB::beginTransaction();
        try {
            $data = new Questionnaire();
            $data->organization_id = $request->organization_id;
            $data->unit_id = $request->unit_id;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->guidlines = $request->guidlines;
            $data->save();

            DB::commit();
            return redirect(route($this->route . 'index'))->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getTraceAsString()]);
        }
    }

    public function edit($id)
    {
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'data' => Questionnaire::find($id),
        ];
        return view($this->route . 'form', $param);
    }

    public function save(Request $request)
    {
        request()->validate($this->validate_fields);
        DB::beginTransaction();
        try {
            $id = $request->id;
            $data = Questionnaire::find($id);
            $data->organization_id = $request->organization_id;
            $data->unit_id = $request->unit_id;
            $data->title = $request->title;
            $data->description = $request->description;
            $data->guidlines = $request->guidlines;
            $data->save();
            DB::commit();
            return redirect(route($this->route . 'index'))->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $data = Questionnaire::find($id);

            $data->delete();
            DB::commit();
            return redirect(route($this->route . 'index'))->with([
                'toast-success' => 'success',
                'success' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

    public function link($encryptedId)
    {
        $id= Crypt::decryptString($encryptedId);
        $param = [
            'route' => $this->route,
            'menu_title' => $this->menu_title,
            'data' => Questionnaire::find($id),
        ];
        return view($this->route . 'link', $param);
    }

    public function datatable(Request $request)
    {
        $data = Questionnaire::get();
        return DataTables::of($data)->addColumn('action', function ($d) {
            $html = '
                <div class="d-flex justify-content-center">
                    <button tooltip="Copy Public Link" onclick="copyClipboard(\'' . route($this->route . 'link', Crypt::encryptString($d->id)) . '\')" class="btn btn-sm btn-success btn-icon me-1 btn-copy-link">
                        <i class="ph-share"></i>
                    </button>
                    <a href="' . route($this->route . 'edit', $d->id) . '" class="btn btn-sm btn-primary btn-icon me-1">
                        <i class="ph-pencil"></i>
                    </a>
                    <form action="' . route($this->route . 'delete') . '" method="post">
                        <input type="hidden" name="id" value="' . $d->id . '"/>
                        <input type="hidden" name="_token" value="' . csrf_token() . '"/>
                        <button type="submit" onclick="deleteRow(event)" class="btn btn-sm btn-danger btn-icon">
                           <i class="ph-x"></i>
                        </button>
                    </form>
                </div>';
            return $html;
        })->rawColumns([
            'action'
        ])->make(true);
    }
}
