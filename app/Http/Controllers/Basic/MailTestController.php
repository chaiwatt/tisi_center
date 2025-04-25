<?php

namespace App\Http\Controllers\Basic;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

use App\Mail\Basic\TestMail;

class MailTestController extends Controller
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
        $model = str_slug('mail-test', '-');
        if(auth()->user()->can('view-'.$model)) {

            $config = config('mail');

            $config['from_address'] = $config['from']['address'];
            $config['from_name']    = $config['from']['name'];

            $config['send_to'] = $config['username'];
            $config['subject'] = 'ทดสอบส่งเมล';
            $config['body']    = 'เมลทดสอบจาก '.URL('/');


            return view('basic.mail-test.index', compact('config'));

        }
        abort(403);

    }

    public function send_mail(Request $request){

        $model = str_slug('mail-test', '-');
        if(auth()->user()->can('view-'.$model)) {

            $data   = $request->all();

            //ไฟล์แนบ
            $attach_path = null;
            if(array_key_exists('attach', $data)){
                $attach_folder = 'storage/uploads/tmp/'.uniqid();
                $attach_path   = $attach_folder.'/'.$data['attach']->getClientOriginalName();
                File::makeDirectory($attach_folder, $mode = 0777, true, true);
                File::copy($data['attach']->getPathName(), $attach_path);
            }

            $path = base_path('.env');

            if (file_exists($path)) {

                $old = env('MAIL_FROM_NAME');
                
                file_put_contents(
                    $path, 
                    str_replace(
                        'MAIL_FROM_NAME='.$old , 
                        'MAIL_FROM_NAME='.$data['from_name'], 
                        file_get_contents($path)
                    )
                );
            }

            $mail_format = new TestMail([
                'subject' => $data['subject'],
                'body' => $data['body'],
                'from_address' => $data['from_address'],
                'from_name' => $data['from_name'],
                'attach_path' => $attach_path
            ]);
            Mail::to($data['send_to'])->send($mail_format);

            //ลบไฟล์แนบ
            if(array_key_exists('attach', $data)){
                File::deleteDirectory($attach_folder);
            }

            return back()->withInput()->with('flash_message', 'ส่งอีเมลเรียบร้อยแล้ว');

        }
        abort(403);

    }

}
