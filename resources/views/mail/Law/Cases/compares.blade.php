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
       
         <div id="style">
             <p>
                   <b>เรื่อง : </b>  {{$title}}
             </p>
              <p>
                  <b>เรียน :  {{$case->offend_name ?? ''}}</b>
              </p>
 
              <p class="indent50"> 
                          ตามที่ท่านได้ยินยอมชำระเปรียบเทียบปรับเมื่อวันที่   {!! !empty($case->compare_date) ? HP::formatDateThaiFull($case->compare_date) : '' !!} นั้น
              </p> 
              <p>
                    ขอแจ้งใบแจ้งชำระเงิน รายละเอียดตามแนบมา
              </p>
 
              <p>
                  จึงเรียนมาเพื่อโปรดทราบ  <br>
                  <hr style="border-top: 1px solid black;">
                  e-mail นี้เป็นระบบข้อความอัตโนมัติจากระบบ กรุณาอย่าตอบกลับ
              </p> 
               {!! '<p><b>สอบถามข้อมูลเพิ่มเติมได้ที่ <br> - Tel. : 0-2430-6830 ต่อ 2000 <br> - ฃe-mail : law2022@tisi.go.th <br> - Line : @law2022 </b></p>' !!}
             
          </div> 
       
       
       