@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงาน e-Payment</h3>
                    @can('view-'.str_slug('cerreport-epayments'))
                        <a class="btn btn-success pull-right" href="{{ url('/cerreport/epayments') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr>
                                  <th class="text-right">รายชื่อผู้ชำระ :</th>
                                  <td>{{ $epayment->bus_name }}</td>
                              </tr>

                              <tr>
                                <th class="text-right">ที่อยู่ผู้รับคำขอ :</th>
                                <td> {{ $epayment->FormatAddress }} </td>
                              </tr>
                          
                              <tr>
                                <th class="text-right">เลขที่คำขอ :</th>
                                <td> {{ $epayment->appno }} </td>
                              </tr>

                              <tr>
                                @php
                                $type = '';
                                    if( $epayment->state == 1){
                                      $type = 'ค่าตรวจประเมิน';
                                    }else if( $epayment->state == 2){
                                      $type = 'ค่าตรวจธรรมเนียมใบรับรอง';
                                    }

                                @endphp
                                 <th class="text-right">ประเภทค่าใช้จ่าย :</th>
                                <td> {{ $type }} </td>
                              </tr>

                              <tr>
                                @php
                                $certify = '';
                                    if( $epayment->certify == 1){
                                      $certify = 'ห้องปฏิบัติการ';
                                    }else if( $epayment->certify == 2){
                                      $certify = 'หน่วยตรวจ';
                                    }else if( $epayment->certify == 3){
                                      $certify = 'หน่วยรับรอง';
                                    }

                                @endphp
                                <th class="text-right">การรับรอง :</th>
                                <td> {{ $certify }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">เลขที่ใบแจ้งชำระ/Bill No. :</th>
                                <td> {{ $epayment->billNo }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">Ref.1 :</th>
                                <td> {{ $epayment->Ref_1 }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">Ref.2 :</th>
                                <td> {{ $epayment->Ref_2 }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">วันที่แจ้งชำระ :</th>
                                <td> {{ !empty($epayment->invoiceStartDate)?HP::DateThai($epayment->invoiceStartDate):null }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">กำหนดชำระ :</th>
                                <td> {{ !empty($epayment->invoiceEndDate)?HP::DateThai($epayment->invoiceEndDate):null }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">จำนวนเงิน :</th>
                                <td> {{ $epayment->amount }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">ใบแจ้งชำระ :</th>
                                <td> {{ '-' }} </td>
                              </tr>

                              <tr>
                                @php
                                $state = '';
                                    if( $state == 1){
                                      $state = 'แจ้งชำระค่าธรรมเนียม';
                                    }else if( $epayment->state == 2){
                                      $state = 'ชำระค่าธรรมเนียมเรียบร้อย';
                                    }
                                @endphp
                                <th class="text-right">สถานะ :</th>
                                <td> {{ $state }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">ระหัสใบเสร็จรับเงิน :</th>
                                <td> {{ $epayment->vatid }} </td>
                              </tr>

                              <tr>
                                <th class="text-right">จำนวนเงินที่ชำระ :</th>
                                <td> {{ ($epayment->amount).(' ('.$epayment->allAmountTH.')') }} </td>
                              </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
