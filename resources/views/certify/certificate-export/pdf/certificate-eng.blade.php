<style>
    @page {
        margin:1% 2%;padding:0;
    }
    body {
        font-family: 'THSarabunNew', sans-serif;
    }
    .content{
        /*border: 5px solid black;*/
        padding: 5%;
        margin: 0px;
        height: 100%;
        top: 10%;
        position: relative;

    }
    .tc{
        text-align: center;
    }
    div{
        width: 100%;
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
        font-size: 1em;
    }

</style>




{{-- HTML--}}
<body>
<div style="top: 30px;left: 82%;position: absolute">
    Form NSC/TISI 2
</div>
<div class="content">
    <div class="tc">
        <h2 style="margin:20px 0px">(Garuda)</h2>
    </div>
    <p>Certificate No. {{$certificate_no}}</p>

    <div class="tc">
        <div class="space"></div>
        <h1><b>Certificate of Laboratory Accreditation</b></h1>
        <h3><b>by Virtue of National Standardization Act BE. 2551 (2008)</b></h3>
        <h3><b>Seeretary-General, Thai Industrial Standards Institute</b></h3>
        <h3><b>Issue this Certificate for</b></h3>
        <div class="space"></div>
        <h3><b>{{$certificate_for}}</b></h3>
        <h3><b>ห้องปฏิบัติการ {{$lab_name}}</b></h3>
        <div class="space"></div>
        <p>Laboratory address :</p>
        <p>{{$address}}</p>
        <p>This laboratory is accredited for {{$lab_type == "3" ? "testing":"calibration"}}</p>
        <p>in accordance with the Thai industrial Standard {{$formula}}</p>
        <p>General Requirements for the Competence of Testing and Calibration Laboratories</p>
        <div class="space-mini"></div>
        <b>Accreditation No. {{$accereditatio_no}}</b>
        <div class="space-mini"></div>
        <p>The scope of accreditation is as annexed hereto.</p>
    </div>
    <div class="space"></div>
    <table class="w-100">
        <tr>
            <td class="w-66 tr">
                <p>Issue date</p>
                <p>Valid date</p>
            </td>
            <td class="w-33" style="padding-left: 30px">
                <p>{{$certificate_date_start}}</p>
                <p>{{$certificate_date_end}}</p>
            </td>
        </tr>
        <tr>
            <td style="height: 40px"></td>
        </tr>
        <tr>
            <td class="w-50"></td>
            <td class="w-50 tc">
                <p>(Signature)</p>
                <p>(Mr, Nattapol Rangsitpol)</p>
                <p>Secretary-General <br>Thai Industrial Standards Institute</p>
            </td>
        </tr>
    </table>
    <div class="footer">
        <table style="margin-top: 40px;font-size: 1em">
            <tr>
                <td style="width: 80%">
                    <p>Date of initial issue {{$certificate_date_first}}</p>
                    <p>Ministry of Industry, Thai Industrial Standards Institute</p>
                </td>
                <td class="tr" style="width:15%;">
                    <img src="{{ public_path('storage/uploads/certify/ilac.png') }}"/>
                </td>
                <td class="tr" style="width:15%;">
                    <img src="{{ public_path('storage/uploads/certify/nc.png') }}"/>
                </td>
            </tr>
        </table>
        <small>Transtation Note : In the event of doubt or misunderstanding, the original in Thai shall be the authoritative.</small>
    </div>
</div>

</body>
