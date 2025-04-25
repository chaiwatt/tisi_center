<?php

namespace App\Http\Controllers\Basic;

use App\Role;
use App\RoleUser;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Esurv\Trader;
use Illuminate\Http\Request;

use HP;

class TraderController extends Controller
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
        $model = str_slug('trader','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['search'] = $request->get('search', '');
            $filter['type'] = $request->get('type', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new Trader;
            $Query = $Query->where('is_nsw','y');

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

            $trader = $Query->sortable()->orderby('date_of_data','DESC')->paginate($filter['perPage']);

            return view('basic.trader.index', compact('trader', 'filter'));
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
        $model = str_slug('trader','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.trader.create');
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
        $model = str_slug('trader','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'trader_type' => 'required',
        			'trader_operater_name' => 'required',
        			'trader_id' => 'required',
        			'agent_email' => 'required'
        		]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            Trader::create($requestData);
            return redirect('basic/trader')->with('flash_message', 'เพิ่ม trader เรียบร้อยแล้ว');
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
        $model = str_slug('trader','-');
        if(auth()->user()->can('view-'.$model)) {
            $trader = Trader::findOrFail($id);
            return view('basic.trader.show', compact('trader'));
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
        $model = str_slug('trader','-');
        if(auth()->user()->can('edit-'.$model)) {

            $trader = Trader::findOrFail($id);
            $trader->trader_id_register = HP::revertDate($trader->trader_id_register);

            $roles = Role::all();
            $trader_roles = RoleUser::where('user_trader_autonumber', $trader->getKey())->pluck('role_id', 'role_id')->toArray();

            return view('basic.trader.edit', compact('trader', 'roles', 'trader_roles'));
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
        $model = str_slug('trader','-');
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

            $trader = Trader::findOrFail($id);
            $trader->update($requestData);

            //บันทึก Roles
            RoleUser::where('user_trader_autonumber', $trader->getKey())->delete();//ลบออกก่อน

            if(isset($requestData['roles'])){
              $roles = [];
              foreach ((array)$requestData['roles'] as $role_id) {
                $roles[] = ['role_id' => $role_id, 'user_trader_autonumber' => $trader->getKey()];
              }
              RoleUser::insert($roles);
            }

            return redirect('basic/trader')->with('flash_message', 'แก้ไข trader เรียบร้อยแล้ว!');
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
        $model = str_slug('trader','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new Trader;
            Trader::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            Trader::destroy($id);
          }

          return redirect('basic/trader')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('trader','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Trader;
          Trader::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/trader')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
