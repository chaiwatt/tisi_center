
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
           <b> 
               เรียน   {{  !empty($assign) ?   $assign  :  ''  }}   
 
            </b> 
        </p>
        <p> 
            <b> เรื่อง  แจ้งตรวจสอบการชำระ{{($PayIn->status == 1 ? 'ค่าบริการในการตรวจประเมิน': 'ค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง') }}

            </b> 
         </p>
         <p class="indent50"> 
            ตามที่    {{  !empty($data->name) ?  $data->name   :  ''  }}
            ได้แนบหลักฐานการชำระเงิน{{($PayIn->status == 1 ? 'ค่าบริการในการตรวจประเมิน': 'ค่าธรรมเนียมคำขอ และค่าธรรมเนียมใบรับรอง') }}  
            คำขอรับ{{($PayIn->status == 1 ? 'บริการ': 'ใบรับรอง') }}   
            หน่วยรับรอง หมายเลขคำขอ   {{  !empty($PayIn->reference_refno) ?   $PayIn->reference_refno  :  ''  }} 
            สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม{{($PayIn->status == 1 ? 'ได้รับการชำระเงินเรียบร้อยแล้ว': 'ยังไม่ได้รับการชำระเงิน') }} 
        </p>
 
        
        <p>
            จึงเรียนมาเพื่อ{{($PayIn->status == 1 ? 'โปรดทราบ': 'ดำเนินการ') }} 
            <br>
            --------------------------
        </p>
        <img src="{!! asset('plugins/images/anchor_sm200.jpg') !!}"  height="200px" width="200px"/>
        <p>
            {!!  !empty(auth()->user()->UserContact) ?  auth()->user()->UserContact   :  ''     !!}
        </p>
  
    </div> 
</body>
</html>

