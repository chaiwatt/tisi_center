@push('css')

    <style>

        th {
            text-align: center;
        }

        td {
            text-align: center;
        }

        .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
            background-color: #FFF2CC;
        }

        .modal-header {
            padding: 9px 15px;
            border-bottom: 1px solid #eee;
            background-color: #317CC1;
        }

        .form-group {
            margin-bottom: 15px;
        }

        /*
          Max width before this PARTICULAR table gets nasty. This query will take effect for any screen smaller than 760px and also iPads specifically.
          */
        @media only screen
        and (max-width: 760px), (min-device-width: 768px)
        and (max-device-width: 1024px) {

            /* Force table to not be like tables anymore */
            table, thead, tbody, th, td, tr {
                display: block;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                margin: 0 0 1rem 0;
            }

            tr:nth-child(odd) {
                background: #eee;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }

            td:before {
                /* Now like a table header */
                /*position: absolute;*/
                /* Top/left values mimic padding */
                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
            }

            /*
            Label the data
        You could also use a data-* attribute and content for this. That way "bloats" the HTML, this way means you need to keep HTML and CSS in sync. Lea Verou has a clever way to handle with text-shadow.
            */
            /*td:nth-of-type(1):before { content: "Column Name"; }*/

        }

        .wrapper-detail {
            border: solid 1px silver;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        legend {
            width: auto; /* Or auto */
            padding: 0 10px; /* To give a bit of padding on the left and right */
            border-bottom: none;
            font-size: 14px;
        }
    </style>

@endpush

            <div class="row wrapper-detail">
                <div class="form-group">
                    <label class="control-label text-right col-md-4">ชื่อผลิตภัณฑ์อุตสาหกรรม:</label>
                    <div class="col-md-6">
                        {{ $data->title }}
                    </div>
                </div>

                <table class="table color-bordered-table primary-bordered-table">
                    <thead>
                    <tr>
                        <th class="text-center">รายการที่</th>
                        <th class="text-center" width="50%">รายละเอียดผลิตภัณฑ์อุตสาหกรรม</th>
                        <th class="text-center">ปริมาณที่ขอทำ</th>
                        <th class="text-center">หน่วย</th>
                    </tr>
                    </thead>
                      <tbody id="table-body">
                      @foreach ($data_detail as $item)
                          <tr>
                              <td class="text-center">{{ $loop->iteration }}</td>
                              <td class="text-center align-top">
                                  <input class="form-control" value="{{$item->detail}}" disabled>
                              </td>
                              <td class="text-center align-top">
                                  <input class="form-control" value="{{$item->quantity}}" disabled>
                              </td>
                              <td class="text-center align-top">
                                  <input class="form-control" value="{{ App\Models\Esurv\Unit::find($item->unit)->title }}" disabled>
                              </td>
                          </tr>
                      @endforeach
                      </tbody>

                </table>

                <div class="form-group">
                    <label class="control-label text-right col-md-4">แตกต่างจากมาตรฐานเลขที่:</label>
                    <div class="col-md-6">
                      @php
                        $different_nos = json_decode($data->different_no);
                      @endphp

                      @foreach ($different_nos as $key => $different_no)
                        {{ HP::get_different_no_4($different_no) }}@if($key!=count($different_nos)-1), @endif

                      @endforeach

                    </div>
                </div>
                <div class="form-group ">
                    <label class="control-label text-right col-md-4">เหตุผลที่ขออนุญาต:</label>
                    <div class="col-md-6">
                        {{ $data->reason }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label text-right col-md-4">ประเทศที่ทำผลิตภัณฑ์ที่ขอนำเข้า:</label>
                    <div class="col-md-6">

                        {{ HP::get_county_4($data->country_made) }}

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label text-right col-md-4">ระยะเวลาที่นำเข้า:</label>
                    <div class="col-md-8">
                        {{ HP::DateThai($data->start_date) }} ถึง {{ HP::DateThai($data->end_date) }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label text-right col-md-4">บริษัทที่สั่งผลิตภัณฑ์:</label>
                    <div class="col-md-6">
                        {{ $data->company_order }}
                    </div>
                </div>

                <div class="row">
                    <div class="white-box">
                        <fieldset>
                            <legend>พร้อมแนบเอกสาร ดังนี้</legend>

                            <div class="form-group">
                                <label for="attach_product_plan" class="col-md-6 control-label required">แผนงาน ระยะเวลาดำเนินงาน</label>
                                <div class="col-md-6">

                                    @php $attach_product_plan = json_decode($data->attach_product_plan); @endphp

                                    @if($attach_product_plan->file_name!='' && HP::checkFileStorage($attach_path.$attach_product_plan->file_name))
                                      <a href="{{ HP::getFileStorage($attach_path.$attach_product_plan->file_name) }}" target="_blank">
                                        {{ $attach_product_plan->file_client_name }}
                                      </a>
                                    @endif

                                </div>

                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">

                                <label for="attach_hiring_book" class="col-md-6 control-label required">สำเนาหนังสือสัญญาว่าจ้าง/สัญญาการสั่งซื้อ/ข้อตกลงการว่าจ้าง/ใบสั่งซื้อสินค้า</label>
                                <div class="col-md-6">

                                    @php $attach_hiring_book = json_decode($data->attach_hiring_book); @endphp

                                    @if($attach_hiring_book->file_name!='' && HP::checkFileStorage($attach_path.$attach_hiring_book->file_name))
                                      <a href="{{ HP::getFileStorage($attach_path.$attach_hiring_book->file_name) }}" target="_blank">
                                        {{ $attach_hiring_book->file_client_name }}
                                      </a>
                                    @endif

                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <label for="attach_drawing" class="col-md-6 control-label required">สำเนาแบบ (Drawing) ที่ใช้ในการผลิต เฉพาะส่งที่เกี่ยวข้องเกี่ยวกับผลิตภัณฑ์ที่ขอนำเข้า</label>
                                <div class="col-md-6">

                                    @php $attach_drawing = json_decode($data->attach_drawing); @endphp

                                    @if($attach_drawing->file_name!='' && HP::checkFileStorage($attach_path.$attach_drawing->file_name))
                                      <a href="{{ HP::getFileStorage($attach_path.$attach_drawing->file_name) }}" target="_blank">
                                        {{ $attach_drawing->file_client_name }}
                                      </a>
                                    @endif

                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <label for="attach_enumerate" class="col-md-6 control-label required">บัญชีแจกแจงรายละเอียดของผลิตภัณฑ์ที่ขอนำเข้า</label>
                                <div class="col-md-6">

                                    @php $attach_enumerate = json_decode($data->attach_enumerate); @endphp

                                    @if($attach_enumerate->file_name!='' && HP::checkFileStorage($attach_path.$attach_enumerate->file_name))
                                      <a href="{{ HP::getFileStorage($attach_path.$attach_enumerate->file_name) }}" target="_blank">
                                        {{ $attach_enumerate->file_client_name }}
                                      </a>
                                    @endif

                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                {!! Form::label('attach_other', 'เอกสารอื่นๆ (ถ้ามี)', ['class' => 'col-md-4 control-label']) !!}
                                <div class="col-md-8">&nbsp;</div>
                            </div>
                            <div class="clearfix"></div>

                            @if($attachs!=null)
                                    @foreach ($attachs as $key => $attach)
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                &nbsp;&nbsp;&nbsp;-{{ $attach->file_note }}
                                            </div>
                                            <div class="col-md-6">

                                              @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                                <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank">
                                                  {{ $attach->file_client_name }}
                                                </a>
                                              @endif

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    @endforeach
                            @endif

                            <div class="clearfix"></div>

                            <div class="form-group">
                                <label class="col-md-4 control-label text-right">หมายเหตุ:</label>
                                <div class="col-md-6">
                                    {{ $data->remark }}
                                </div>
                            </div>

                        </fieldset>
                    </div>
                </div>

            </div>
