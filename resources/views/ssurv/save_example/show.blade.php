@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left"> รายละเอียดระบบใบรับนำ - ส่งตัวอย่าง </h3>
                    <a class="btn btn-success pull-right" href="{{ url('/ssurv/save_example') }}">
                        <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                    </a>
                    <div class="clearfix" style="margin-bottom: 20px;"></div>

                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr>
                                  <th class="col-sm-2" >ID :</th>
                                  <td class="col-sm-12">{{ $save_example->id }}</td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> มาตรฐาน : </th>
                                  <td class="col-sm-12"> {{ $save_example->tis->tb3_TisThainame }} </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2" > ผู้รับใบอนุญาต : </th>
                                <td class="col-sm-12"> {!! $save_example->licensee=='เลือกผู้รับใบอนุญาต'?'':$save_example->licensee !!} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2" > การตรวจสอบ : </th>
                                  <td class="col-sm-12"> {{ $save_example->verification }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> การนำส่งตัวอย่าง : </th>
                                  <td class="col-sm-12"> {{ $save_example->sample_submission }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> โดยเก็บตัวอย่างไว้ที่ : </th>
                                  <td class="col-sm-12"> {{ $save_example->stored_add }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> วันที่นำส่งตัวอย่าง : </th>
                                  <td class="col-sm-12"> {{ $save_example->sample_submission_date }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> ผู้จ่ายตัวอย่าง : </th>
                                  <td class="col-sm-12"> {{ $save_example->sample_pay }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> ตำแหน่ง : </th>
                                  <td class="col-sm-12"> {{ $save_example->permission_submiss }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> เบอร์โทรศัพท์ : </th>
                                  <td class="col-sm-12"> {{ $save_example->tel_submiss }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> Emai : </th>
                                  <td class="col-sm-12"> {{ $save_example->email_submiss }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> วันที่รับตัวอย่าง : </th>
                                  <td class="col-sm-12"> {{ $save_example->sample_collect_date }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> ผู้รับตัวอย่าง : </th>
                                  <td class="col-sm-12"> {{ $save_example->sample_recipient }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> ตำแหน่ง : </th>
                                  <td class="col-sm-12"> {{ $save_example->permission_receive }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> เบอร์โทรศัพท์ : </th>
                                  <td class="col-sm-12"> {{ $save_example->tel_receive }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> Email : </th>
                                  <td class="col-sm-12"> {{ $save_example->email_receive }} </td>
                              </tr>
                              <tr>
                                  <th class="col-sm-2"> การรับคืนตัวอย่าง : </th>
                                  <td class="col-sm-12"> {{ $save_example->sample_return }} </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2"> วันเวลาที่สร้าง : </th>
                                <td class="col-sm-12"> {{ HP::DateTimeThai($save_example->created_at) }} </td>
                              </tr>
                              <tr>
                                <th class="col-sm-2"> วันเวลาที่แก้ไข : </th>
                                <td class="col-sm-12"> {{ HP::DateTimeThai($save_example->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
