<style>
    @page {
        margin: 2%;
        padding: 0;
    }

    @page {
        header: page-header;
        footer: page-footer;
    }

    body {
        font-family: 'THSarabunNew', sans-serif;
    }

    .content {
        /* border: 5px solid #d4af37; */
        /* padding: 5%; */
        padding-top: 5%;
        padding-left: 5%;
        padding-right: 5%;
        margin: 0px;
        height: 100%;
        top: 10%;
        position: relative;

    }

    .tc {
        text-align: center;
    }

    div {
        width: 100%;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        padding: 0px;
        margin: 0px;
        line-height: 2em;
    }

    .space {
        height: 20px;
    }

    .space-mini {
        height: 10px;
    }

    .space_big {
        height: 80px;
    }

    b {
        font-weight: bold;
    }

    h1 {
        margin-bottom: 10px;
    }

    .w-100 {
        width: 100%;
    }

    .tab {
        display: inline-block;
        margin-left: 40px;
    }

    .tr {
        text-align: right;
    }

    .w-66 {
        width: 66%;
    }

    .w-33 {
        width: 33%;
    }

    .w-15 {
        width: 15%;
    }

    .w-50 {
        width: 50%;
    }

    table {
        line-height: 2em;
        font-size: 1.2em;
    }

    .font-6 {
        font-size: 6pt;
    }

    .font-7px {
        font-size: 7px;
    }

    .font-7 {
        font-size: 7pt;
    }

    .font-8 {
        font-size: 8pt;
    }

    .font-8px {
        font-size: 8px;
    }

    .font-10 {
        font-size: 10pt;
    }

    .font-11 {
        font-size: 11pt;
    }

    .font-12 {
        font-size: 12pt;
    }

    .font-13 {
        font-size: 11pt;
    }

    .font-16 {
        font-size: 16pt;
    }

    .font-18 {
        font-size: 16pt;
    }

    .font-20 {
        font-size: 16pt;
    }

    .font-8px {
        font-size: 8px;
    }

    .free-dot {
        border-bottom: thin dotted #000000;
        padding-bottom: 0px !important;

    }

    .custom-label {
        background: #ffffff;
        border-bottom: thin dotted #ffffff;
        padding-bottom: 5px;
    }

</style>

{{-- HTML--}}
<body>

<div class="content">
    {{-- <div style="padding-left: 7%; padding-right: 7%">
        <table width="100%" style="padding-top: 10px;">
            <tr>
                <td align="center" class="font-13" style="padding-top: 50px;line-height:15px;">
                    <b>ก.๑ รูปแบบมาตรฐานการตรวจสอบและรับรองแห่งชาติ แบบยกร่าง</b>
                </td>
            </tr>
        </table>
    </div> --}}
    <div class="space"></div>
    <div style="padding-left: 7%; padding-right: 7%">
        <table width="100%">
            <tr>
                <td align="center" rowspan="3">
                    <img src="{{ public_path('images/certify/NC2.png') }}" width="150" />
                </td>
                <td align="right" class="font-16">
                    <b>มาตรฐานการตรวจสอบและรับรองแห่งชาติ</b>
                </td>
            </tr>
            <tr>
                <td align="right" class="font-12">
                    <b>THAI CONFORMITY ASSESSMENT STANDARD</b>
                </td>
            </tr>
            <tr>
                <td align="right" class="font-12">
                    <b>มตช. {{ $std_no }}</b>
                </td>
            </tr>
            <tr>
                <td align="center" class="font-16">
                    <b>มตช.</b>
                </td>
                <td align="center" class="font-16">
                </td>
            </tr>
        </table>
    </div>

    <div class="space_big"></div>
    <div class="space_big"></div>
    <div class="space"></div>

  <div style="padding-left: 7%; padding-right: 7%">
      <table width="100%">
          <tr>
              <td align="left" class="font-18" style="padding-left: 10px; height:85">
                  <p><b>ชื่อมาตรฐานภาษาไทย</b> {{$std_title}}</p>
              </td>
          </tr>
          <tr>
              <td align="left" class="font-14" style="padding-left: 10px;">
                  <p><b>ENGLISH</b> {{$std_title_en}}</p>
              </td>
          </tr>
      </table>
  </div>

</div>

    <htmlpagefooter name="page-footer">
        <div style="padding-left: 7%; padding-right: 7%">
            <table width="100%" style="margin-bottom:70px">
                <tr>
                    <td align="left" class="font-12" style="padding-left: 40px">
                        <p><b>สำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม</b></p>
                    </td>
                    <td class="font-12">
                    </td>
                </tr>
                <tr>
                    <td align="left" class="font-12" width="75%" style="padding-left: 40px">
                        <p><b>กระทรวงอุตสาหกรรม</b></p>
                    </td>
                    <td align="right" class="font-12" style="padding-right: 40px">
                        <b>ISBN {{ $isbn_no }}</b>
                    </td>
                </tr>
            </table>
        </div>
    </htmlpagefooter>

</body>

