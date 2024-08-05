<?php

namespace App\Http\Controllers\Modules\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use App\Models\Master\Period;
use App\Models\Master\WorkUnit;
use App\Models\Transaction\Skp;
use App\Models\Transaction\SkpWorkAssignment;
use App\Models\Transaction\SkpWorkPlan;
use App\Models\Transaction\SkpRealization;
use App\Models\Transaction\SkpPlanRealization;
use App\Models\Transaction\SkpBehaviorRealization;
use App\Constants\SkpStatus;
use App\Models\Master\FeedbackWorkCategory;
use App\Models\Master\FeedbackBehaviorCategory;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\System\LogError;
use Illuminate\Validation\ValidationException;
use App\Constants\RealizationStatus;
use App\Helpers\Helper;
use App\Models\UI\PersonalInfo;
use App\Models\UI\PrintSkpInfo;
use Carbon\Carbon;
use PDF;

class QuestionnaireResultController extends Controller
{

    private $route = "modules.report.questionnaire-result.";
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
        $results = DB::select("
            select que.*,queh.count_question,queh.count_audience,queh.avg_result,quelow.title low_question,quelow.avg_result low_result,
            quehigh.title high_question,quehigh.avg_result high_result
            from questionnaires que 
            join (
                select q.questionnaire_id,
                count(distinct(q.id)) count_question,
                count(distinct(qr.question_header_id)) count_audience,
                avg(	qr.answer::FLOAT) avg_result 
                from questions q 
                left join question_results qr on q.id=qr.question_id
                and q.questionnaire_id = qr.questionnaire_id
                where q.deleted_at is null 
                group by q.questionnaire_id
            ) queh on queh.questionnaire_id=que.id
            left join (
                select * from (
                    SELECT
                        qq.questionnaire_id,qq.question_id,qq.avg_result,q.title,
                        (RANK () OVER ( PARTITION BY qq.questionnaire_id ORDER BY qq.avg_result ASC )) as rank_avg 
                    FROM
                        ( SELECT questionnaire_id, question_id, AVG ( answer :: FLOAT ) avg_result 
                        FROM question_results WHERE deleted_at IS NULL 	
                        GROUP BY questionnaire_id, question_id 
                        ) qq
                        JOIN questions q ON q.ID = qq.question_id 
                ) qlow
                where rank_avg=1
            ) quelow on quelow.questionnaire_id=que.id
            left join (
                select * from (
                    SELECT
                        qq.questionnaire_id,qq.question_id,qq.avg_result,q.title,
                        (RANK () OVER ( PARTITION BY qq.questionnaire_id ORDER BY qq.avg_result DESC )) as rank_avg 
                    FROM
                        ( SELECT questionnaire_id, question_id, AVG ( answer :: FLOAT ) avg_result 
                        FROM question_results WHERE deleted_at IS NULL 	
                        GROUP BY questionnaire_id, question_id 
                        ) qq
                        JOIN questions q ON q.ID = qq.question_id 
                ) qlow
                where rank_avg=1
            ) quehigh on quehigh.questionnaire_id=que.id
            ");
        $data = [
            "route" => $this->route,
            'results' => $results,
        ];
        return view($this->route . 'index', $data);
    }

}
