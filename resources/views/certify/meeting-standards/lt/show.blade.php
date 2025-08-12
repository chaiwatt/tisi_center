@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title">บันทึกผลการประชุม</h3>
                <hr>

                {{-- 
                    - ฟอร์มสำหรับอัปเดตข้อมูล จะส่งไปที่ route update
                    - ใช้ @method('PATCH') เพื่อบอก Laravel ว่าเป็นการส่งแบบ PATCH/PUT
                --}}
                <form method="POST" 
                      action="{{ route('certify.meeting-standards.lt.update', ['id' => $meetingstandard->id]) }}" 
                      class="form-horizontal" 
                      id="form-meetingstandard"
                      enctype="multipart/form-data">
                    
                    {{-- @method('POST') --}}
                    @csrf

                    {{-- เรียกใช้ฟอร์มกลางที่สร้างไว้ --}}
                    @include ('certify.meeting-standards.lt.form-edit')

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
