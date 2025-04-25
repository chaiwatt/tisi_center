@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงาน Pay In</h3>
                    @can('view-'.str_slug('cerreport-epayments'))
                        <a class="btn btn-success pull-right" href="{{  app('url')->previous() }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="col-md-3 text-right">เลขที่คำขอ : </p>
                            <p class="col-md-9"> {!! !empty($pay_in->app_no)?  $pay_in->app_no:''  !!} </p>
                        </div>
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">ชื่อผู้ประกอบการ : </p>
                          <p class="col-md-9"> {!! !empty($pay_in->name)?  $pay_in->name:''  !!} </p>
                        </div>
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">เลขประจำตัวผู้เสียภาษี : </p>
                          <p class="col-md-9"> {!! !empty($pay_in->tax_id)?  $pay_in->tax_id:''  !!} </p>
                        </div>
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">เงื่อนไขการชำระเงิน : </p>
                          <p class="col-md-9"> {!! !empty($pay_in->ConditionalTypeName)?  $pay_in->ConditionalTypeName:''  !!} </p>
                        </div>
                        <div class="col-sm-12">
                          <p class="col-md-3 text-right">วันที่แจ้งชำระ : </p>
                          <p class="col-md-9"> {!!  !empty($pay_in->start_date)?HP::DateThai($pay_in->start_date):null !!} </p>
                        </div>
                        @if (!empty($pay_in->auditors_name))
                        <div class="col-sm-12">
                            <p class="col-md-3 text-right">คณะผู้ตรวจประเมิน : </p>
                            <p class="col-md-9"> {!!   $pay_in->auditors_name  !!} </p>
                        </div>
                        @endif
                        
                    </div>
                    
                        <div class="clearfix"></div> 
                  
                </div>
            </div>
        </div>
    </div>

@endsection
