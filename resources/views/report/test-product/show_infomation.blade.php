
<table width="100%" class="table_hight">
    <tbody>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">ผู้ประกอบการ : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->trader_name )?  $testproduct->trader_name :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">เลขผู้เสียภาษี : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->trader_taxid )?  $testproduct->trader_taxid :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">อีเมลผู้ประกอบการ : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->trader_email )?  $testproduct->trader_email :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">มอก. : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->tis_no )?  $testproduct->tis_no :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">รหัสหน่วยตรวจสอบ : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->lab_code )?  $testproduct->lab_code :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">ชื่อหน่วยตรวจสอบ : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->lab_name )?  $testproduct->lab_name :null  !!}</td>
        </tr>        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">เลขที่ใบรับ-นำส่งตัวอย่าง : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->sample_bill_no )?  $testproduct->sample_bill_no :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">จำนวนชุดตัวอย่าง : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->total_sample_qty )?  $testproduct->total_sample_qty :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">วันที่รับตัวอย่าง : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->receive_date )?  HP::DateThai($testproduct->receive_date) :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">รับตัวอย่างจาก : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->sample_from )?  $testproduct->sample_from :null  !!}</td>
        </tr>        
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">กอง : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->department )?  $testproduct->department :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">กลุ่ม : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->sub_department )?  $testproduct->sub_department :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">วันที่ทดสอบ : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->test_date )?  HP::DateThai($testproduct->test_date) :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">วันที่ทดสอบเสร็จ : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->test_finish_date )?  HP::DateThai($testproduct->test_finish_date) :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">ระยะเวลาทดสอบ (วัน) : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->test_duration )?  $testproduct->test_duration :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">ราคาทดสอบ/ชุดทดสอบ : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->test_price )?  number_format($testproduct->test_price) :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">รวมราคาทั้งหมด : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->total_test_price )?  number_format($testproduct->total_test_price) :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">วันที่ออกรายงาน : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->report_date )?  HP::DateThai($testproduct->report_date) :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">วันที่ชำระเงินค่าตรวจสอบ : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->payment_date )?  HP::DateThai($testproduct->payment_date) :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">เลขที่รายงายตามระบบ LAB : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->ref_report_no )?  $testproduct->ref_report_no :null  !!}</td>
        </tr>
        <tr>
            <td valign="top" width="20%" class="text-right lead_cuttom">รายละเอียด : </td>
            <td valign="top" width="80%" class="lead_cuttom td_border">{!! !empty( $testproduct->remark )?  $testproduct->remark :null  !!}</td>
        </tr>        
    </tbody>
</table>