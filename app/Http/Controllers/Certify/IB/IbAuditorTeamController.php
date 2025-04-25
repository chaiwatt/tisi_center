<?php

namespace App\Http\Controllers\Certify\IB;

use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use App\Certify\IbAuditorTeam;
use App\Http\Controllers\Controller;
use App\Models\Certify\ApplicantIB\CertiIBAuditorsStatus;

class IbAuditorTeamController extends Controller
{
    public function index(Request $request)
    {
        $model = str_slug('setting-team-ib','-');
        if(auth()->user()->can('view-'.$model)) {
            $ibAuditorTeams = IbAuditorTeam::paginate(15);
            return view('certify.ib.auditor_setting.index',[
                'ibAuditorTeams' => $ibAuditorTeams
            ]);
        }
        abort(403);

    }

    public function create()
    {
        $model = str_slug('setting-team-ib','-');
        if(auth()->user()->can('add-'.$model)) {
            $auditors_status = [new CertiIBAuditorsStatus]; 
            $signers = Signer::all();
            
            return view('certify.ib.auditor_setting.create',[
                'auditors_status' => $auditors_status,
                'signers' => $signers,
            ]);
        }
        abort(403);
        
    }

    public function store(Request $request)
    {
        $auditorTeamJson = json_encode($request->list, JSON_UNESCAPED_UNICODE);
        // dd($auditorTeamJson,$request->state);
        // บันทึกลงฐานข้อมูล
        $auditorTeam = IbAuditorTeam::create([
            'name' => $request->auditor_name,
            'auditor_team_json' => $auditorTeamJson,
            'state' => $request->state,
        ]);
    
        return redirect()->to('/certify/setting-team-ib');
    }

    public function updateState(Request $request)
    {

        $requestData = $request->all();
        if(array_key_exists('item-selection', $requestData)){
            $ids = $requestData['item-selection'];
            $db = new IbAuditorTeam;
            IbAuditorTeam::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }
    
        return redirect()->to('/certify/setting-team-ib');
    }
    
}

