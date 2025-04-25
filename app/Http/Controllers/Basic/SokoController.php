<?php

namespace App\Http\Controllers\Basic;

use App\Role;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Esurv\Soko;
use Illuminate\Http\Request;

use HP;

class SokoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('soko','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['search'] = $request->get('search', '');
            $filter['type'] = $request->get('type', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new Soko;
            $Query = $Query->where('is_nsw','n');

            if ($filter['search']!='') {
              $Query = $Query->where(function ($Query) use ($filter)  {
                          $Query->where('trader_operater_name', 'LIKE', '%'.$filter['search'].'%')
                                ->orWhere('trader_id', 'like', '%' . $filter['search'] . '%')
                                ->orWhere('agent_email', 'like', '%' . $filter['search'] . '%');
                       });
            }

            if ($filter['type']!='') {
                $Query = $Query->where('trader_type', $filter['type']);
            }

            $soko = $Query->sortable()->orderby('date_of_data','DESC')->paginate($filter['perPage']);

            return view('basic.soko.index', compact('soko', 'filter'));
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('soko','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.soko.create');
        }
        abort(403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('soko','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'trader_type' => 'required',
        			'trader_operater_name' => 'required',
        			'trader_id' => 'required',
        			'agent_email' => 'required'
        		]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            Soko::create($requestData);
            return redirect('basic/soko')->with('flash_message', 'เพิ่ม soko เรียบร้อยแล้ว');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('soko','-');
        if(auth()->user()->can('view-'.$model)) {
            $soko = Soko::findOrFail($id);
            return view('basic.soko.show', compact('soko'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('soko','-');
        if(auth()->user()->can('edit-'.$model)) {

            $soko = Soko::findOrFail($id);
            $soko->trader_id_register = HP::revertDate($soko->trader_id_register);

            $roles = Role::all();
            $trader_roles = RoleUser::where('user_trader_autonumber', $soko->getKey())->pluck('role_id', 'role_id')->toArray();

            return view('basic.soko.edit', compact('soko', 'roles', 'trader_roles'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('soko','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'trader_type' => 'required',
        			'trader_operater_name' => 'required',
        			'trader_id' => 'required',
        			'agent_email' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();//user update
            $requestData['trader_id_register'] = HP::convertDate($requestData['trader_id_register']);//converdate
            if($request->password){
                $requestData['trader_password'] = $request->password;
            }

            $soko = Soko::findOrFail($id);
            $soko->update($requestData);

            //บันทึก Roles
            RoleUser::where('user_trader_autonumber', $soko->getKey())->delete();//ลบออกก่อน

            if(isset($requestData['roles'])){
              $roles = [];
              foreach ((array)$requestData['roles'] as $role_id) {
                $roles[] = ['role_id' => $role_id, 'user_trader_autonumber' => $soko->getKey()];
              }
              RoleUser::insert($roles);
            }

            return redirect('basic/soko')->with('flash_message', 'แก้ไข soko เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('soko','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new Soko;
            Soko::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            Soko::destroy($id);
          }

          return redirect('basic/soko')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('soko','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Soko;
          Soko::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/soko')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }


    public function checkemailexits(Request $req)
    {
        $email = $req->email;
        $emailcheck = Soko::where('trader_username', $email)->count();
        if($emailcheck > 0)
        {
        echo "already";
        }
    }

}
