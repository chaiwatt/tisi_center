<table width="100%">
    <tbody>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">ผู้ประกอบการ : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->trader_name )?  $testfactory->trader_name :null  !!}</td>
            <td valign="top" width="20%" class="text-right lead_cuttom">เลขผู้เสียภาษี : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->trader_taxid )?  $testfactory->trader_taxid :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">มอก. : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->tis_no )?  $testfactory->tis_no :null  !!}</td>
            <td valign="top" width="20%" class="text-right lead_cuttom">ชื่อมอก. : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $standard_tisi->tis_name )?  $standard_tisi->tis_name :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">รหัสหน่วยตรวจสอบ. : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->ib_code )?  $testfactory->ib_code :null  !!}</td>
            <td valign="top" width="20%" class="text-right lead_cuttom">ชื่อหน่วยตรวจสอบ. : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->ib_name )?  $testfactory->ib_name :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">อ้างอิงเลขที่คำขอโรงงาน : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border" colspan="3">{!! !empty( $testfactory->factory_request_no )?  $testfactory->factory_request_no :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">ค่าตรวจสอบ : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->test_price )?  number_format($testfactory->test_price) :null  !!}</td>
            <td valign="top" width="20%" class="text-right lead_cuttom">วันที่ชำระเงินค่าตรวจสอบ : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->payment_date )?  HP::DateThai($testfactory->payment_date) :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">ผลการตรวจโรงงาน : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->test_result )?  $testfactory->test_result :null  !!}</td>
            <td valign="top" width="20%" class="text-right lead_cuttom">ไฟล์ผลทดสอบโรงงาน : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->test_result_file )?  '<i class="fa  fa-file-text" style="font-size:20px; color:#92b9b9" aria-hidden="true"></i>' :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">รายละเอียดการตรวจสอบ : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->remark )?  $testfactory->remark :null  !!}</td>
            <td valign="top" width="20%" class="text-right lead_cuttom">อ้างอิงเลขที่รายงานของ IB : </td>
            <td valign="top" width="30%" class="lead_cuttom td_border">{!! !empty( $testfactory->ref_report_no )?  $testfactory->ref_report_no :null  !!}</td>
        </tr>
    </tbody>
</table>
