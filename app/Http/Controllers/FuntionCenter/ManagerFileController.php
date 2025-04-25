<?php

namespace App\Http\Controllers\FuntionCenter;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use stdClass;

class ManagerFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('config','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('function.manager-file.index');
        }
        abort(403);

    }

    public function show_all(Request $request)
    {

        $folder  = $request->get('folder');
        $search  = $request->get('search');
     
        if( !empty($search) ){

            $list = [];

            if( pathinfo($search, PATHINFO_EXTENSION) == "" ){
  
                $cut_path = explode('/', $search);
                $cut_path = array_filter($cut_path);

                $last = array_key_last($cut_path);
               
                $search = str_replace( '//', '/',$search  );

                $directory = '/';
     
                if( Storage::exists($search) ){

                    $path_main =  'uploads/'.$search ;

                    $directories = Storage::Directories($search);

                    foreach( $directories AS $item ){
                        $data = new stdClass;
                        $data->path = $item;
                        $data->name =  pathinfo($item, PATHINFO_BASENAME);
                        $data->pathinfo = 'folder';
                        $data->time = Storage::lastModified($item);
                        $list[] = $data;
                    }

                    $files = Storage::Files($search);

                    foreach( $files AS $item ){
                        $data = new stdClass;
                        $data->path = Storage::path($item);
                        $data->name = pathinfo($item, PATHINFO_BASENAME);
                        $data->pathinfo = pathinfo($item, PATHINFO_EXTENSION);
                        $data->size = Storage::size($item);
                        $data->time = Storage::lastModified($item);
                        $list[] = $data;
                    } 

                }else{
                    $directories = Storage::allDirectories($directory);

                    foreach( $directories AS $item ){
                        if( mb_strpos( pathinfo($item, PATHINFO_BASENAME), $search ) !== false ){
                            $data = new stdClass;
                            $data->path = $item;
                            $data->name =  pathinfo($item, PATHINFO_BASENAME);
                            $data->pathinfo = 'folder';
                            $data->time = Storage::lastModified($item);
                            $list[] = $data;
                        }
                    }

                    $files = Storage::allFiles($directory);
                
                    foreach( $files AS $item ){
    
                        if( mb_strpos( pathinfo($item, PATHINFO_BASENAME) , $search ) !== false ){
                            $data = new stdClass;
                            $data->path = Storage::path($item);
                            $data->name = pathinfo($item, PATHINFO_BASENAME);
                            $data->pathinfo = pathinfo($item, PATHINFO_EXTENSION);
                            $data->size = Storage::size($item);
                            $data->time = Storage::lastModified($item);
                            $list[] = $data;
                        }
                    }  
                }
      
            }else{
                $directory = '/';

                if( Storage::exists( $search ) ){
                    $cut_path = explode('/', $search);
                    $cut_path = array_filter($cut_path);
    
                    $last = array_key_last($cut_path);

                    $search = implode('/', $cut_path);
                   
                    $search = str_replace( '//', '/',$search  );

                    $files = Storage::allFiles(pathinfo($search , PATHINFO_DIRNAME));

                    foreach( $files AS $item ){
                        if( mb_strpos( pathinfo($item, PATHINFO_BASENAME) , pathinfo($search , PATHINFO_BASENAME) ) !== false ){
                            $data = new stdClass;
                            $data->path = Storage::path($item);
                            $data->name = pathinfo($item, PATHINFO_BASENAME);
                            $data->pathinfo = pathinfo($item, PATHINFO_EXTENSION);
                            $data->time = filectime($item);
                            $list[] = $data; 
                        }
                    }

                }else{
            
                    $files = Storage::allFiles($directory);

                    $search = str_replace( '//', '/',$search  );

                    foreach( $files AS $item ){

                        if( mb_strpos( $item, $search ) !== false ){
                            $data = new stdClass;
                            $data->path = Storage::path($item);
                            $data->name = pathinfo($item, PATHINFO_BASENAME);
                            $data->pathinfo = pathinfo($item, PATHINFO_EXTENSION);
                            $data->size = Storage::size($item);
                            $data->time = Storage::lastModified($item);
                            $list[] = $data;
                        }
                    }     

                }

            }


        }else{
            $path_main = '/';
            if(empty( $folder)){
                $directory = '/';
            }else{
                $folder = base64_decode($folder);
    
                $check = substr($folder, -1);
    
                $directory = $check == '/' ? mb_substr($folder,0,mb_strlen($folder)-1):$folder;
                $cut_path = explode('/', $directory);
                $path_main =  'uploads/'.$directory ;
            }
       
            $directories = Storage::Directories($directory);
    
            $list = [];
            foreach( $directories AS $item ){
                $data = new stdClass;
                $data->path = $item;
                $data->name = pathinfo($item, PATHINFO_BASENAME);
                $data->pathinfo = 'folder';
                $data->time = Storage::lastModified($item);
                $list[] = $data;
            }
    
            $files = Storage::Files($directory);
            foreach( $files AS $item ){
                $data = new stdClass;
                $data->path = $item;
                $data->name = pathinfo($item, PATHINFO_BASENAME);
                $data->pathinfo = pathinfo($item, PATHINFO_EXTENSION);
                $data->size = Storage::size($item);
                $data->time = Storage::lastModified($item);
                $list[] = $data;
     
            }
    
        }

        if( empty( $path_main ) || $path_main == '' ){
            $path_main = '/';
        }        

        return view('function.manager-file.show',compact('list','path_main'));
    }

    public function ConvertKB($invalue)
    {
        if( !empty( $invalue ) && is_numeric($invalue) && $invalue > 0 ){
            $bytevalue = $invalue * 1024 * 1024;
            return $bytevalue;
        }else{
            return null;
        }

    }

    public function LoadFolder(Request $request)
    {
        $level = 0;
        $directories = Storage::Directories('/');
        $list =  $this->LoopItem($directories, $level);
        return response()->json($list);
    }

    public function LoopItem($directory, $level)
    {

        $list = [];
        $level++;
        foreach( $directory AS $item ){

            if( $level <= 2){
                $directories = Storage::Directories($item);

                $folder = pathinfo($item, PATHINFO_BASENAME);
    
                $data = new stdClass;
                $data->text = '<i class="icon-folder text-warning"></i> '.$folder;
                $data->href = $item;
                $result = $this->LoopItem( $directories, $level);
                $data->tags = [ count($result) ];
                if(count( $result) >= 1 ){
                    $data->nodes =  $result;
                }    
                $list[] =   $data;
            }

        }
        return $list;
        
    }
}
