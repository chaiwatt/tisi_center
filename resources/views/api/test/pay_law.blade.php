<style>
    @page {
        margin:2%;padding:0;
    }
    @page {
    header: page-header;
    footer: page-footer;
}
    body {
        /* font-family: 'THSarabunNew', sans-serif; */
        font-family: 'thiasarabun', sans-serif;
    }
    .content{
            padding-top: 5%;
            padding-left: 2%;
            padding-right: 2%;
            margin: 0px;
            height: 100%;
            top: 10%;
            position: relative;
    
        }
    .tc{
        text-align: center;
    }
    .tl{
        text-align: left;
    }
 
    h1,h2,h3,h4,h5,h6,p{
        padding: 0px;
        margin: 0px;
        line-height: 2em;
    }
    .space{
        height: 20px;
    }
    .space-mini{
        height: 10px;
    }
    b{
        font-weight: bold;
    }
    h1{
        margin-bottom: 10px;
    }
    .w-100{
        width: 100%;
    }
    .tab {
        display:inline-block;
        margin-left: 40px;
    }
    .tr{
        text-align: right;
    }
    .w-66{
        width: 66%;
    }
    .w-70{
        width: 70%;
    }
    .w-33{
        width: 33%;
    }
    .w-15{
        width: 15%;
    }
    .w-50{
        width: 50%;
    }
    table{
        line-height: 2em;
        font-size: 1.2em;
    }
    


    .font-10{
        font-size: 10pt;
    }
    .font-11{
        font-size: 11pt;
    }
    .font-12{
        font-size: 12pt;
    }
    .font-13{
        font-size: 13pt;
    }
    .font-14{
        font-size: 14pt;
    }
    .font-15{
        font-size: 15pt;
    }
    .font-16{
        font-size: 16pt;
    }
    .font-18{
        font-size: 18pt;
    }
    .font-19{
        font-size: 19pt;
    }
    .font-20{
        font-size: 20pt;
    }
    .font-25{
        font-size: 25pt;
    }
  .free-dot {
            border-bottom: thin dotted #000000; 
            padding-bottom: 0px !important;
   }
   .custom-label{
        background: #ffffff;
        border-bottom: thin dotted #ffffff; 
        padding-bottom: 5px;
      }

    .line-height25  {
        line-height:25px;
        padding-top: -5px;
    }


</style>


<div class="content">
 

<table width="100%"    style="padding-top: 7px;"    > 
    <tr>
         <td  style="padding-left: 25px;" class="font-14">
               {{ !empty($cases->law_cases_payments_to->name) ?  $cases->law_cases_payments_to->name : @$cases->offend_name  }} 
         </td>      
    </tr>
</table>

 <table width="100%"    style="padding-left: 100px;padding-top: -31px;"    > 
    <tr>
         <td     class="font-10"   >
               {{ !empty($cases->law_cases_payments_to->start_date) ? HP::formatDateThaiFull($cases->law_cases_payments_to->start_date)  : '-' }} 
         </td>      
    </tr>
</table>
 
 <table width="100%"    style="padding-left: 128px;padding-top: -30px;"    > 
    <tr>
         <td     class="font-10"   >
               {{ !empty($cases->law_cases_payments_to->end_date) ? HP::formatDateThaiFull($cases->law_cases_payments_to->end_date)  : '-' }} 
         </td>      
    </tr>
</table>

<table width="100%"    style="padding-top: -38px;padding-right: -40px;"    > 
    <tr>
 
         <td     class="font-14"  align="right"  >
               {{ !empty($cases->law_cases_payments_to->amount) ? number_format($cases->law_cases_payments_to->amount,2)  : '-' }} 
         </td>      
    </tr>
</table>

 
<table width="100%"    style="padding-top: 5px;padding-right: -40px;"    > 
    <tr>
 
         <td     class="font-14"  align="right"  >
               {{ !empty($cases->law_cases_payments_to->amount) ? number_format($cases->law_cases_payments_to->amount,2)  : '-' }} 
         </td>      
    </tr>
</table>


 <table width="100%"    style="padding-left: 25px;padding-top: -58px;"    > 
    <tr>
         <td     class="font-16"   >
               <b>
                 {{ !empty($detail->fee_name) ? $detail->fee_name  : '' }} 
              </b> 
         </td>      
    </tr>
</table>
 <table width="100%"    style="padding-left: 25px;padding-top: -35px;"    > 
    <tr>
         <td     class="font-14"   >
               {{ !empty($detail->remark_fee_name) ? $detail->remark_fee_name  : '' }} 
         </td>      
    </tr>
</table>
 
<table width="100%"    style="padding-top: 38px;padding-right: -40px;"    > 
    <tr>
 
         <td     class="font-14"  align="right"  >
               {{ !empty($cases->law_cases_payments_to->amount) ? number_format($cases->law_cases_payments_to->amount,2)  : '-' }} 
         </td>      
    </tr>
</table>

 
 <table width="100%"    style="padding-left: 100px;padding-top: 113px;"    > 
    <tr>
         <td     class="font-10"   >
               {{ !empty($cases->law_cases_payments_to->start_date) ? HP::formatDateThaiFull($cases->law_cases_payments_to->start_date)  : '-' }} 
         </td>      
    </tr>
</table>
 <table width="100%"    style="padding-right: -40px;padding-top: -58px;"    > 
    <tr>
         <td     class="font-14"   align="right" >
               {{ !empty($cases->law_cases_payments_to->start_date) ? HP::formatDateThaiFull($cases->law_cases_payments_to->start_date)  : '-' }} 
         </td>      
    </tr>
</table>

 
 <table width="100%"    style="padding-left: 130px;padding-top: -33px;"    > 
    <tr>
         <td     class="font-10"   >
               {{ !empty($cases->law_cases_payments_to->end_date) ? HP::formatDateThaiFull($cases->law_cases_payments_to->end_date)  : '-' }} 
         </td>      
    </tr>
</table>

 
<table width="100%"       style="padding-left: 332px;padding-top: 343px;"    > 
    <tr>
 
         <td     class="font-12"   >
               {{ !empty($cases->law_cases_payments_to->amount) ?  str_replace(".","",number_format($cases->law_cases_payments_to->amount,2, '.', ''))     : '-' }} 
         </td>      
    </tr>
</table>

</div>


