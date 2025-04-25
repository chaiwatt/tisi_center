<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <style>
       #style{

            padding: 5px;
            border: 5px solid gray;
            margin: 0;
            
       }    
       #customers td, #customers th {
            border: 1px solid #ddd;
            padding: 8px;
            }

        #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #66ccff;
        color: #000000;
        }   
        .indent50 {
        text-indent: 50px;
        } 
        .indent100 {
        text-indent: 100px;
        } 
   </style>
</head>
<body>
   <div id="style">
   
        <p>
            <b>เรียน {!! isset($operater_name)?$operater_name:'-' !!}</b>
        </p>
        <p>
            <b>เรื่อง {!! isset($invite)?$invite:'-' !!}</b>
        </p>
        <p class="indent50"> 
            {!! isset($quality)?$quality:'-' !!}
        </p>

        @isset($file_attach)
            @php
                $attach_path = 'SendMails/File/';
            @endphp
           <p> <a href="{{ HP::getFileStorage($attach_path.$file_attach) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search">เอกสารแนบ</i></a></p>
        @endisset
        
        {!! isset($username)?'<p><b>UserName : </b>'.$username.'</p>':'-' !!}
        {!! isset($password)?'<p><b>Password : </b>'.$password.'</p>':'-' !!}
        
        <p>
            {!! isset($information)?$information:'-' !!}
        </p>
    </div> 
</body>
</html>
  