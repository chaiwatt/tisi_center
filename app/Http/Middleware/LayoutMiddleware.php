<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

use App\User;
use App\Sessions;

use Cookie;
use HP;

class LayoutMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check()){

            $session_id = session()->getId();

            //อัพเดท cookie
            $config = HP::getConfig();
            $minutes = config('session.lifetime');
            Cookie::queue($config->officer_name_cookie_login,
                          $session_id,
                          $minutes,
                          null,
                          $config->officer_domain_cookie_login,
                          null,
                          false
                      );

            //อัพเดท session ใน DB
            $session = Sessions::where('id', $session_id)->first();
            if(is_null($session)){//ไม่มีในฐานให้สร้าง
                Sessions::Add($session_id, auth()->user()->getKey(), $request->ip(), $request->userAgent(), 'web');
            }else{//อัพเดทเวลา
                Sessions::Modify($session_id);
            }

        }

        $theme = ['normal', 'fix-header', 'mini-sidebar'];
        if(isset(request()->theme)){
            if($request->isMethod('get') && in_array($request->theme,$theme)){

                //ถ้าเปลี่ยน Layout type save ไปที่ user
                $user = User::findOrFail(auth()->user()->getKey());

                $params = (object)json_decode($user->params);
                $params->theme_layout = str_slug(request()->theme,'-');

                $user->params = json_encode($params);
                $user->save();

            }else{
                $query = $request->query();
            }
            return back();

        }
        return $next($request);
    }
}
