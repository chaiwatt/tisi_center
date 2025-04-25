<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use App\Profile;
use App\Sessions;
use HP;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $user = User::where('reg_email', $request->email)
                    ->where('reg_pword', md5($request->password))
                    ->withTrashed()
                    ->first();

        if(!is_null($user)){//login สำเร็จ
    
            if($user->deleted_at!=null || $user->status=='0'){
                return back()->withInput()->withErrors(['ผู้ใช้งานถูกปิดการใช้งาน']);
            }

            Auth::login($user);

            $session_id = session()->getId();

            //บันทึกลงตาราง session
            Sessions::Add(
                    $session_id,
                    $user->getKey(),
                    $request->ip(),
                    $request->userAgent(),
                    'web'
                );

            $config = HP::getConfig();

            //Save to Cookie
            Cookie::queue($config->officer_name_cookie_login,
                        $session_id,
                        120,
                        null,
                        $config->officer_domain_cookie_login,
                        null,
                        false
            );

            if(is_null($user->profile)){//ถ้า profile ยังไม่มีข้อมูล
                $input_profile = [];
                $input_profile['user_runrecno'] = $user->getKey();

                $profile = new Profile;
                $profile->create($input_profile);
            }

        }else{
            return back()->withInput()->withErrors(['ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง']);
        }

      return redirect()->intended('/home');

    }

    public function authenticated(Request $request, $user)
    {
        activity($user->name)
            ->performedOn($user)
            ->causedBy($user)
            ->log('LoggedIn');
        if($user->hasRole('admin')){
            return redirect('home');
        }  else{
            return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        //ล้างค่าต่างๆ
        $this->action_logout($request);

        return redirect('/login');
    }

    private function action_logout($request){

        //Config
        $config = HP::getConfig();

        $user = Auth::user();
        if(!is_null($user)){

            Sessions::Remove(session()->getId());//ลบจากตาราง sso_session

            activity($user->name)
                ->performedOn($user)
                ->causedBy($user)
                ->log('LoggedOut');
            $this->guard()->logout();
            $request->session()->invalidate();
        }else{
            $cookie = Cookie::get($config->officer_name_cookie_login);
            if(!is_null($cookie)){//มีค่า cookie
                Sessions::Remove($cookie);//ลบจากตาราง sso_session
            }
        }

        //ลบ Cookie Login
        Cookie::queue(Cookie::forget($config->officer_name_cookie_login, null, $config->officer_domain_cookie_login));

        return true;
    }
}
