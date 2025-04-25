@extends('layouts.master')
@section('content')

  <div class="container-fluid">
      <!-- .row -->
      <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="panel panel-success">
                  <div class="panel-heading">บันทึกข้อมูลความคิดเห็นเรียบร้อยแล้ว</div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body text-center">
                            <p>ขอขอบคุณ สำหรับความคิดเห็นต่อการทบทวนมาตรฐานนี้</p>
                            <br>
                            <br>
                            <br>
                            <button class="btn btn-success" onclick="location='{{ url('/home') }}';">
                              <i class="fa fa-home"></i> กลับหน้าหลัก
                            </button>
                        </div>
                        <div class="panel-footer"> </div>
                    </div>
              </div>
          </div>

        </div>
  </div>

@endsection
