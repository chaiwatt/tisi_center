
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
            เรียน ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ 
         </b> 
     </p>
     <p> 
         <b> เรื่อง  แจ้งขยายเวลาการชำระค่าบริการในการตรวจประเมิน
         </b> 
      </p>
      <p class="indent50"> 
         ตามที่   {{  !empty($certi_lab->BelongsInformation->name) ?  $certi_lab->BelongsInformation->name   :  ''  }} 
         ระบบได้ขยายเวลาการชำระค่าบริการ โดยได้แนบหลักฐานการชำระเงินค่าบริการในการตรวจประเมินคำขอรับบริการห้องปฏิบัติการ หมายเลขคำขอ   {{  !empty($certi_lab->app_no) ?   $certi_lab->app_no  :  ''  }} 
         สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้รับการชำระเงินเรียบร้อยแล้ว 
     </p>
     <p>
         จึงเรียนมาเพื่อโปรดทราบ
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

