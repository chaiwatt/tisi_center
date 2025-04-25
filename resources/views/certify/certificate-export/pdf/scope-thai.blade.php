<style>
    @page {
        margin:2%;padding:0;
        footer: page-footer;
    }
    body {
        font-family: 'THSarabunNew', sans-serif;
    }
    .content{
        height: 100%;
    }
    .tc{
        text-align: center;
    }
    div{
        width: 100%;
    }
    h1,h2,h3,h4,p,h6,p{
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
        line-height: 1.3em;
    }

    .infor {
        padding: 5px 10px;
    }

    .tableTesting{
        border: 1px solid black;
    }

    .tableTesting table  {
        border-collapse: collapse;
    }

    .br{
        border-right: 1px solid black;
    }

    .bb{
        border-bottom: 1px solid black;
    }
    .info{
        padding: 10px;
        padding-bottom: 30px;
        font-weight: 200;
        vertical-align: text-top;
    }

</style>




{{-- HTML--}}
<body>

<div class="content_scope content">
    <div class="tc">
        <h3><b>รายละเอียดแนบท้ายใบรับรองห้องปฏิบัตการ{{$lab_type == "3" ? "ทดสอบ":"สอบเทียบ"}}</b></h3>
        <h3><b>ที่ {{$certificate_no}}</b></h3>
    </div>
    <div class="space"></div>
    <div class="infor">
        <table>
            <tr>
                <td width="25%"><p>ชื่อห้องปฏิบัตการ</p></td>
                <td width="75%"><p>: {{$certificate_for}}</p></td>
            </tr>
            <tr>
                <td width="25%"></td>
                <td width="75%" style="padding-left: 10px"><p>ห้องปฏิบัตการ {{$lab_name}}</p></td>
            </tr>
            <tr>
                <td width="25%"><p>ที่อยู่</p></td>
                <td width="75%"><p>: {{$address}}</p></td>
            </tr>
            <tr>
                <td width="25%"><p>หมายเลขการรับรองที่</p></td>
                <td width="75%"><p>: {{$accereditatio_no}}</p></td>
            </tr>
            <tr>
                <td width="25%"><p>สถานภาพห้องปฏิบัตการ</p></td>
                <td width="75%" style="display: inline-block">
                    <p>:
                        <input type='checkbox' {{$scope_permanent == 1 ? "checked='checked'" : null}}> ถาวร
                        <input type='checkbox' {{$scope_site == 1 ? "checked='checked'" : null}}> นอกสถานที่
                        <input type='checkbox' {{$scope_temporary == 1 ? "checked='checked'" : null}}> ชั่วคราว
                        <input type='checkbox' {{$scope_mobile == 1 ? "checked='checked'" : null}}> เคลื่อนที่
                    </p>
                </td>
            </tr>
        </table>

        <div class="space"></div>

        <table width="100%" class="tableTesting">

            @if ($lab_type == "3")
                <tr>
                    <td width="33%" class="tc br bb"><b>สาขาทดสอบ</b></td>
                    <td width="33%" class="tc br bb"><b>รายการทดสอบ</b></td>
                    <td width="33%" class="tc bb"><b>วิธีการทดสอบ</b></td>
                </tr>

                @foreach ($scopes as $key => $scope)
                    <tr>
                        <td width="33%" class="br info">
                            <p>{{ $scope_branch = $scope->getBranch()->title ?? "N/A" }}</p>
                            <p>&nbsp;&nbsp;&nbsp;{{ $scope->test_detail }}</p>
                        </td>
                        <td width="33%" class="br info">
                            {!! $scope->test_list !!}
                        </td>
                        <td width="33%" class="info">
                            {!! $scope->test_method !!}
                        </td>
                    </tr>

                @endforeach

            @else
                <tr>
                    <td width="25%" class="tc br bb"><b>สาขาสอบเทียบ</b></td>
                    <td width="25%" class="tc br bb"><b>รายการสอบเทียบ</b></td>
                    <td width="25%" class="tc br bb"><b>ขีดความสามารถของ <br>การสอบเทียบและการวัด *</b></td>
                    <td width="25%" class="tc bb"><b>วิธีการสอบเทียบ</b></td>
                </tr>

                @foreach ($scopes as $key => $scope)
                  <tr>
                      <td width="25%" class="br info">
                          <p>{{$scope['scope_branch']}}</p>
                      </td>
                      <td width="25%" class="br info">
                          <p>&nbsp;</p>
                          @foreach ($scope['scope_detail'] as $detail)
                              <p>- {{$detail}}</p>
                          @endforeach
                      </td>
                      <td width="25%" class="br info">
                          <p>&nbsp;</p>
                          @foreach ($scope['scope_capability'] as $capability)
                              <p>- {{$capability}}</p>
                          @endforeach
                      </td>
                      <td width="25%" class="info">
                          <p>&nbsp;</p>
                          @foreach ($scope['scope_how'] as $how)
                              <p>- {{$how}}</p>
                          @endforeach
                      </td>
                  </tr>
                @endforeach
            @endif

        </table>

        <div class="space"></div>
        <table class="w-100">
            <tr>
                <td class="w-50 tc">
                </td>
                <td class="w-50" style="padding-left: 30px">
                    <p>ออกให้ ณ วันที {{$certificate_date_start}}</p>
                </td>
            </tr>
            <tr>
                <td style="height: 30px"></td>
            </tr>
            <tr>
                <td class="w-50"></td>
                <td class="w-50 tc">
                    <p>ลงชื่อ&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</p>
                    <p>(นายวันชัย พนมชัย)</p>
                    <p>เลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสากรรม</p>
                </td>
            </tr>
        </table>
    </div>
</div>
<htmlpagefooter name="page-footer">
    <table style="width: 100%">
        <tr>
            <td width="33%"><p>ฉบับที่ {{$issue_no}}</p></td>
            <td width="33%" class="tc"><p>หน้า {PAGENO}</p></td>
            <td width="33%" class="tr"><p>ออกให้ครั้งแรกเมื่อ {{$certificate_date_first}}</p></td>
        </tr>
    </table>
    <div class="w-100">
        <p><small>กระทรวงอุตสาหกรรม สำนักงานมาตรฐานผลิตภัณฑ์อุตสหากรรม</small></p>
    </div>
</htmlpagefooter>
</body>
