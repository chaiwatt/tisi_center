@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดการตรวจติดตามผล {{ $follow_up->id }}</h3>
                    @can('view-'.str_slug('follow_up'))
                        <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                      @php
                          $check = ['1'=>'มีการตรวจ'];
                      @endphp
                        <table class="table table">
                            <tbody>
                              <tr>
                                  <th class="col-sm-2">ชื่อผู้รับใบอนญาต</th>
                                  <td class="col-sm-10">
                                      {{ $follow_up->tradename }}
                                  </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2">มาตรฐาน</th>
                                <td class="col-sm-10">
                                    {{ !empty($follow_up->tb3_TisThainame) ? $follow_up->tb3_TisThainame : ''  }}
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2">ใบอนุญาต</th>
                                <td class="col-sm-10">
                                  @if(!empty($follow_up->data_id_follow_up))
                                           @foreach ($follow_up->data_id_follow_up as $key => $name)
                                              <span>{{  $name->license  }}</span><br>
                                          @endforeach
                                  @endif
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2">ชื่อโรงงาน/สำนักงาน</th>
                                <td class="col-sm-10">
                                    {{ !empty($follow_up->factory_name) ? $follow_up->factory_name : ''  }}
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2">ตั้งอยู่เลขที่</th>
                                <td class="col-sm-10">
                                    {{ !empty($follow_up->address) ? $follow_up->address : ''  }}
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2">บุคคลที่พบ</th>
                                <td class="col-sm-10">
                                    <div class="col-sm-3"><b>ชื่อ-สกุล</b></div>
                                    <div class="col-sm-3"><b>ตำแหน่ง</b></div>
                                    <div class="col-sm-3"><b>เบอร์โทร</b></div>
                                    <div class="col-sm-3"><b>E-mail</b></div>
                                    <div class="clearfix"></div>
                                      @if(!empty($follow_up->person->name))
                                          @foreach($follow_up->person->name as $key=>$item)
                                              <div class="col-sm-3">{{ $item  }}</div>
                                              <div class="col-sm-3">{{ !empty(@$follow_up->person->position[$key]) ? @$follow_up->person->position[$key] : '' }}</div>
                                              <div class="col-sm-3">{{ !empty(@$follow_up->person->tel[$key]) ? @$follow_up->person->tel[$key] : ''}}</div>
                                              <div class="col-sm-3">{{ !empty(@$follow_up->person->email[$key]) ? @$follow_up->person->email[$key] : '' }}</div>
                                              <div class="clearfix"></div>
                                              @endforeach
                                      @endif
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2">วันที่ตรวจ</th>
                                <td class="col-sm-10">
                                  {{ !empty($follow_up->check_date) ? HP::DateThai($follow_up->check_date) : ''  }}
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2">พนักงานเจ้าหน้าที่</th>
                                <td class="col-sm-10">
                                    <div class="col-sm-6"><b>ชื่อ-สกุล</b></div>
                                    <div class="col-sm-6"><b>ตำแหน่ง</b></div>
                                  @if(!empty($follow_up->staff->name))
                                      @foreach($follow_up->staff->name as $key=>$item)
                                          <div class="col-sm-6">{{ $item  }}</div>
                                          <div class="col-sm-6">{{  !empty(@$follow_up->staff->position[$key]) ?  @$follow_up->staff->position[$key]: ''    }}</div>
                                      @endforeach
                                  @endif
                                </td>
                              </tr>
                               <tr>
                                <th class="col-sm-2">
                                   @if($follow_up->follow_type == 1)
                                      การผลิต
                                  @else
                                      การนำเข้า
                                  @endif
                                </th>
                                <td class="col-sm-10">
                                  @if($follow_up->inform_manufacture == 3)
                                       <font style="color:rgb(255, 0, 0);">ไม่มีการตรวจ</font>  {{  @$follow_up->inform_manufacture_text }}
                                  @else
                                    @if($follow_up->inform_manufacture == 1)
                                      {{    'แจ้งการผลิต ' }}
                                    @else
                                       {{  'ไม่แจ้งการผลิต '.@$follow_up->inform_manufacture_remark  }}
                                    @endif
                                  @endif
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2">
                                  เครื่องหมายการค้าที่แสดงกับผลิตภัณฑ์
                                </th>
                                <td class="col-sm-10">
                                 @if($follow_up->check_product == 2)
                                     <font style="color:rgb(255, 0, 0);">ไม่มีการตรวจ</font>  {{  @$follow_up->reason_not_inform }}
                                @else
                                    {{ !empty($follow_up->show_mark == "1")? 'แสดงเครื่องหมาย มอก.':""  }} <br>
                                    {{ !empty($follow_up->show_manufacturer == "1")? 'แสดงชื่อผู้ผลิต':""  }}
                                @endif
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-3">
                                    การปฏิบัติตามเงื่อนไขในการออกใบอนุญาต
                                </th>
                                <td class="col-sm-9">
                                    <div class="col-sm-12">
                                          <p><b>3.1 ระบบการควบคุมคุณภาพ</b></p>
                                          <ol>
                                            <li>
                                              @if($follow_up->quality_control == 3)
                                              <font style="color:rgb(255, 0, 0);">ไม่มีการตรวจ</font>  {{  @$follow_up->quality_control_remark }}
                                              @else
                                                  @if($follow_up->quality_control == 1)
                                                  {{    'เป็นไปตามหลักเกณฑ์เฉพาะฯ ข้อ 1.1 หัวข้อ '.@$follow_up->quality_control_text_yes  }}
                                                  @else
                                                  {{   'ไม่เป็นไปตามหลักเกณฑ์เฉพาะฯ ข้อ 1.1 หัวข้อ '.@$follow_up->quality_control_text_no   }}
                                                  @endif
                                              @endif
                                            </li>
                                          </ol>
                                     </div>
                                      <div class="col-sm-12">
                                          <p><b> 3.2 การตรวจสอบผลิตภัณฑ์ และเครื่องมือทดสอบ</b></p>
                                          <ol>
                                            <li>
                                              @if($follow_up->test_tool_product == 3)
                                                 <font style="color:rgb(255, 0, 0);">ไม่มีการตรวจ</font>  {{  @$follow_up->test_tool_product_remark }}
                                              @else
                                                  @if($follow_up->test_tool_product == 1)
                                                  {{    'เป็นไปตามหลักเกณฑ์เฉพาะฯ '.@$follow_up->test_tool_product_text_no   }}
                                                  @else
                                                  {{   'ไม่เป็นไปตามหลักเกณฑ์เฉพาะฯ ข้อ '.@$follow_up->test_tool_product_text   }}
                                                  @endif
                                               @endif
                                            </li>
                                          </ol>
                                      </div>
                                      <div class="col-sm-12">
                                        <p><b> 3.3 การดำเนินการกับข้อร้องเรียนเกี่ยวกับคุณภาพผลิตภัณฑ์</b></p>
                                        <ol>
                                          @if($follow_up->check_proceed == 2)
                                          <font style="color:rgb(255, 0, 0);">ไม่มีการตรวจ</font>  {{  @$follow_up->check_proceed_text }}
                                          @else
                                                @if(!empty($follow_up->complaint_amount))
                                                  <li>
                                                    {{  'มีการร้องเรียนกี่ครั้ง โปรดระ '.@$follow_up->complaint_amount   }}
                                                </li>
                                                @endif
                                                @if(!empty($follow_up->complaint_amount))
                                                <li>
                                                  @if($follow_up->complaint_collect == 1)
                                                      {{  'การจัดเก็บข้อร้องเรียน มี ' }}
                                                  @else
                                                      {{  'การจัดเก็บข้อร้องเรียน ไม่มี '}}
                                                  @endif
                                                </li>
                                                @endif
                                                @if(!empty($follow_up->complaint_handle))
                                                <li>
                                                  @if($follow_up->complaint_handle == 1)
                                                      {{  'การจัดการข้อร้องเรียน มีประสิทธิผล ' }}
                                                  @else
                                                      {{  'การจัดการข้อร้องเรียน ไม่มีประสิทธิผล '}}
                                                  @endif
                                                </li>
                                              @endif
                                        @endif


                                        </ol>
                                    </div>
                                    <div class="col-sm-12">
                                      <p><b> 3.4 การแสดงเครื่องหมายมาตรฐานกับผลิตภัณฑ์</b></p>
                                      <ol>
                                        <li>
                                           @if($follow_up->show_mark_product == 3)
                                            <font style="color:rgb(255, 0, 0);">ไม่มีการตรวจ</font>  {{  @$follow_up->show_mark_product_remark }}
                                           @else
                                                @if($follow_up->show_mark_product == 1)
                                                  {{    'แสดงเครื่องหมายแล้ว '  }}
                                                @else
                                                  {{   'ไม่แสดงเครื่องหมาย '  }}
                                                @endif
                                          @endif
                                        </li>
                                      </ol>
                                    </div>
                                    <div class="col-sm-12">
                                      <p><b> 3.5  การแจ้งการนำเข้า</b></p>
                                      <ol>
                                        <li>
                                          @if($follow_up->inform_import == 3)
                                          <font style="color:rgb(255, 0, 0);">ไม่มีการตรวจ</font>  {{  @$follow_up->inform_import_text }}
                                         @else
                                              @if($follow_up->inform_import == 1)
                                                {{    'เป็นไปตามหลักเกณฑ์เฉพาะฯ '  }}
                                              @else
                                                {{   'ไม่เป็นไปตามหลักเกณฑ์เฉพาะฯ '}}
                                              @endif
                                        @endif
                                        </li>
                                      </ol>
                                    </div>
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-3">
                                    บันทึกผลตรวจสอบผลิตภัณฑ์สำเร็จรูป
                                </th>
                                <td class="col-sm-10">
                                    {{ !empty($follow_up->inspection_result_date_start)? $follow_up->inspection_result_date_start : ''.'  '.!empty($follow_up->inspection_result_date_end)?   ' ถึง '.$follow_up->inspection_result_date_start : '' }}
                                    <br>
                                    @if($follow_up->inspection_result == 1)
                                      {{    'มีการตรวจ '  }}
                                    @else
                                    {{   'ไม่มีการตรวจ '.@$follow_up->inspection_result_text   }}
                                    @endif
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-3">
                                  การเก็บตัวอย่าง
                                </th>
                                <td class="col-sm-10">
                                    @if($follow_up->sampling == 1)
                                      {{    'ไม่มีการเก็บตัวอย่าง '  }}
                                    @else
                                    {{   'มีการเก็บตัวอย่าง '  }}
                                    @endif
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-3">
                                  บันทึกเพิ่มเติม
                                </th>
                                <td class="col-sm-10">
                                    {{ !empty($follow_up->additional_note) ? $follow_up->additional_note : ''  }}
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-3">
                                  ไฟล์แนบเพิ่มเติม
                                </th>
                                <td class="col-sm-10">
                                    @foreach ($attachs as $key => $attach)
                                    <div class="form-group other_attach_item">
                                      <div class="col-md-6">
                                      {{  $attach->file_note }}
                                      </div>
                                      <div class="col-md-6">
                                        @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                          <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i></a>
                                        @endif
                                      </div>
                                    </div>
                                    @endforeach
                                </td>
                              </tr>
                              <tr>
                                <th class="col-sm-3">
                                  สำหรับ ผก. รับรอง
                                </th>
                                <td class="col-sm-10">
                                  @php
                                    $conclude_result = ['เห็นชอบและโปรดดำเนินการต่อไป'=>'เห็นชอบและโปรดดำเนินการต่อไป'];
                                  @endphp
                                  @if(array_key_exists($follow_up->conclude_result,$conclude_result))
                                      {{ 'เห็นชอบและโปรดดำเนินการต่อไป' }}
                                  @else
                                       {{ 'อื่นๆ' }}
                                       <p>
                                         {{ @$follow_up->conclude_result_remark }}
                                       </p>
                                  @endif

                                </td>
                              </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
