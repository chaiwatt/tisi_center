<?php

namespace App\Http\Controllers\Certify\CB;

use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use App\Certify\CbAuditorTeam;
use App\Http\Controllers\Controller;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsStatus;

class CbAuditorTeamController extends Controller
{
    public function index(Request $request)
    {
        $model = str_slug('setting-team-cb','-');
        if(auth()->user()->can('view-'.$model)) {
            $cbAuditorTeams = CbAuditorTeam::paginate(15);
            // dd($cbAuditorTeams);
            return view('certify.cb.auditor_setting.index',[
                'cbAuditorTeams' => $cbAuditorTeams
            ]);
        }
        abort(403);

    }

    public function create()
    {
        $model = str_slug('setting-team-cb','-');
        if(auth()->user()->can('add-'.$model)) {
            $auditors_status = [new CertiCBAuditorsStatus]; 
            $signers = Signer::all();
            return view('certify.cb.auditor_setting.create',[
                'auditors_status' => $auditors_status,
                'signers' => $signers,
            ]);
        }
        abort(403);
        
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // แปลง list เป็น JSON
        $auditorTeamJson = json_encode($request->list, JSON_UNESCAPED_UNICODE);
    
        // บันทึกลงฐานข้อมูล
        $auditorTeam = CbAuditorTeam::create([
            'name' => $request->auditor_name,
            'auditor_team_json' => $auditorTeamJson,
            'state' => $request->state,
        ]);
    
        return redirect()->to('/certify/setting-team-cb');
    }

    public function updateState(Request $request)
    {

        $requestData = $request->all();
        if(array_key_exists('item-selection', $requestData)){
            $ids = $requestData['item-selection'];
            $db = new CbAuditorTeam;
            CbAuditorTeam::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }
    
        return redirect()->to('/certify/setting-team-cb');
    }
    
}
