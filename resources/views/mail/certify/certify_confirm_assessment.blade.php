

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <style>
       #style{
            width: 80%;
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
   </style>
</head>
<body>
   <div id="style">
   <p style="font-size:30px;"> <img src="{!! asset('plugins/images/anchor.jpg') !!}"  height="50px" width="50px"/> <b> {{ $title ?? 'บันทึกผลการตรวจประเมิน' }}  </b></p>
         <p><b>คำขอ : </b>  {{ $app_no ?? '-' }}</p>  
         <p><b>ชื่อห้องปฏิบัติการ : </b>   {{ $lab_name ?? '-' }}</p>  
         <p><b>วันที่ทำรายงาน : </b>   {{ $assessment_date ?? '-' }}</p>   
         @if($file != '-')
            <p><b> รายงานการตรวจประเมิน :</b>    
                <a href="{{ url('certify/check/files/assessment/'. basename($file)) }}"> 
                {!!  @basename($file) ?? '' !!}
                </a> 
            </p>  
         @endif
         @if($file_scope != '-')
         <p><b> รายงาน Scope :</b>    
             <a href="{{ url('certify/check/files/assessment/'. basename($file_scope)) }}"> 
             {!!  @basename($file_scope) ?? '' !!}
             </a> 
         </p>  
        @endif
        @if($file_car != '-')
        <p><b> รายงาน Scope :</b>    
            <a href="{{ url('certify/check/files/assessment/'. basename($file_car)) }}"> 
            {!!  @basename($file_car) ?? '' !!}
            </a> 
        </p>  
       @endif

        

          <p><a href="{{ $url ?? '/' }}"class="btn btn-link" target="_blank"> เข้าสู่ระบบ </a></p>  
    </div> 
</body>
</html>  

