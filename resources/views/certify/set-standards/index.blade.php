{{-- SetStandardsController --}}
@extends('layouts.master')

@push('css')
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
<style>
.pointer {cursor: pointer;}
  .form-check-inline.mr-5 {
        margin-right: 2rem; /* Increased spacing after the first radio button */
    }
    .form-check-inline.ml-3 {
        margin-left: 1.5rem; /* Added spacing before the second radio button */
    }
</style>
@endpush


@section('content')

<div class="modal fade text-left" tabindex="10" id="AddStdAgreement" role="dialog" aria-labelledby="AddStdAgreementLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="AddStdAgreementLabel">เห็นชอบ</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- ช่องค้นหา -->
                                    <div class="row">
                                        <label for="agrement_detail" class="col-md-2 control-label label-filter">รายละเอียด:</label>
                                        <div class="col-md-10 form-group">
                                            <textarea name="agrement_detail" id="agrement_detail" class="form-control" rows="4" ></textarea>
                                        </div>
                                    </div>
                                <div class="form-group row align-items-center">
                                    <label class="col-md-2 control-label mb-0"></label>
                                    <div class="col-md-9">
                                        <label class="form-check-inline mb-0 mr-4">
                                            <input class="form-check-input " type="radio" name="agreement" id="agreement_1" value="1" data-id="#agreement" data-radio="iradio_square-green" checked>
                                            เห็นชอบ  &nbsp;&nbsp;&nbsp;  
                                        </label>
                                        <label class="form-check-inline mb-0">
                                            <input class="form-check-input " type="radio" name="agreement" id="agreement_2" value="2" data-id="#agreement" data-radio="iradio_square-green">
                                            ไม่เห็นชอบ
                                        </label>
                                    </div>
                                </div>

                                </div> <!-- col-md-12 -->
                            </div> <!-- row -->
                        </div> <!-- white-box -->
                    </div> <!-- col-md-12 -->
                </div> <!-- row -->

                <div class="modal-footer" id="button_wrapper">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">ยกเลิก</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" id="btn_add_std">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">บันทึก</span>
                    </button>
                </div>
            </div> <!-- modal-body -->
        </div> <!-- modal-content -->
    </div> <!-- modal-dialog -->
</div> <!-- modal -->


<div class="modal fade text-left" tabindex="10" id="StandardCircularDoc" role="dialog" aria-labelledby="StandardCircularDocLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="StandardCircularDocLabel">เวียนมาตรฐาน</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- ช่องค้นหา -->

                                    <div class="form-group row align-items-center">
                                        <label class="col-md-3 control-label mb-0">เวียนมาตรฐาน</label>
                                        <div class="col-md-9">
                                            <label class="form-check-inline mb-0 mr-4">
                                                <input class="form-check-input " type="radio" name="standard_circular_doc" id="standard_circular_doc_1" value="1" data-id="#standard_circular_doc" data-radio="iradio_square-green" checked>
                                                ไม่เวียนมาตรฐาน  &nbsp;&nbsp;&nbsp;  
                                            </label>
                                            <label class="form-check-inline mb-0">
                                                <input class="form-check-input " type="radio" name="standard_circular_doc" id="standard_circular_doc_2" value="2" data-id="#standard_circular_doc" data-radio="iradio_square-green">
                                                เวียนมาตรฐาน
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row" id="standard_circular_doc_detail_wrapper">
                                        <label for="standard_circular_doc_detail" class="col-md-2 control-label label-filter">รายละเอียด:</label>
                                        <div class="col-md-10 form-group">
                                            <textarea name="standard_circular_doc_detail" id="standard_circular_doc_detail" class="form-control" rows="4" ></textarea>
                                        </div>
                                    </div>
                                </div> <!-- col-md-12 -->
                            </div> <!-- row -->
                        </div> <!-- white-box -->
                    </div> <!-- col-md-12 -->
                </div> <!-- row -->

                <div class="modal-footer" id="button_circular_doc_wrapper">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">ยกเลิก</span>
                    </button>
                    <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" id="btn_add_circular_doc">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">บันทึก</span>
                    </button>
                </div>
            </div> <!-- modal-body -->
        </div> <!-- modal-content -->
    </div> <!-- modal-dialog -->
</div> <!-- modal -->


    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">กำหนดมาตรฐานการตรวจสอบและรับรอง #STD</h3>

                    <div class="pull-right">


                    </div>

                    <div class="clearfix"></div>
                    <hr>

                {!! Form::open(['route' => 'set-standards.search_data_list', 'method' => 'GET', 'id' => 'search_form']) !!}
                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="{{ $errors->has('filter_search') ? 'has-error' : '' }}">
                                {!! Form::label('filter_search', 'คำค้น :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('filter_search', request('filter_search'), ['id' => 'filter_search', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="{{ $errors->has('filter_year') ? 'has-error' : '' }}">
                                {!! Form::label('filter_year', 'ร่างแผนปี :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('filter_year', HP::Years(), request('filter_year'), ['class' => 'form-control', 'id' => 'filter_year', 'placeholder' => '-- เลือกร่างแผนปี --']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="{{ $errors->has('filter_standard_type') ? 'has-error' : '' }}">
                                {!! Form::label('filter_standard_type', 'ประเภท :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('filter_standard_type', App\Models\Bcertify\Standardtype::orderByRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), request('filter_standard_type'), ['class' => 'form-control', 'id' => 'filter_standard_type', 'placeholder' => '-- เลือกประเภท --']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="{{ $errors->has('filter_method_id') ? 'has-error' : '' }}">
                                {!! Form::label('filter_method_id', 'วิธีการ :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('filter_method_id', App\Models\Basic\Method::orderByRaw('CONVERT(title USING tis620)')->pluck('title', 'id'), request('filter_method_id'), ['class' => 'form-control', 'id' => 'filter_method_id', 'placeholder' => '-- เลือกวิธีการ --']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 form-group">
                            <div class="{{ $errors->has('filter_status') ? 'has-error' : '' }}">
                                {!! Form::label('filter_status', 'สถานะ :', ['class' => 'col-md-3 control-label text-right']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('filter_status', [
                                        '-1' => 'รอกำหนดมาตรฐาน',
                                        '1' => 'อยู่ระหว่างดำเนินการ',
                                        '2' => 'อยู่ระหว่างการประชุม',
                                        '3' => 'อยู่ระหว่างสรุปรายงานการประชุม',
                                        '4' => 'อยู่ระหว่างจัดทำมาตรฐาน',
                                        '5' => 'สรุปวาระการประชุมเรียบร้อย'
                                    ], request('filter_status'), ['class' => 'form-control', 'id' => 'filter_status', 'placeholder' => '-- เลือกสถานะ --']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-2">
                            <div class="pull-left">
                                <button type="submit" class="btn btn-info waves-effect waves-light" id="button_search" style="margin-bottom: -1px;">ค้นหา</button>
                            </div>
                            <div class="pull-left m-l-15">
                                <a href="{{ route('set-standards.search_data_list') }}" class="btn btn-warning waves-effect waves-light" id="filter_clear">ล้าง</a>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
    
                <div class="clearfix"></div>
                <div class="row">
                        <div class="col-md-12">
                            {{-- <table class="table table-striped" id="myTable">
                                <thead>
                                <tr>
                                    <th width="1%" >#</th>
                                    <th  width="1%" ><input type="checkbox" id="checkall"></th>
                                    <th width="10%" >รหัสโครงการ</th>
                                    <th width="25%" >ชื่อมาตรฐาน</th>
                                    <th width="15%" >ประเภท</th>
                                    <th width="10%" >วิธีการ</th>
                                    <th width="10%" >บรรจุแผนปี</th>
                                    <th width="10%" >ระยะเวลาจัดทำ</th>
                                    <th width="15%" >สถานะ</th>
                                    <th width="10%" >จัดการ</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table> --}}

                            @php
                                $role = App\Role::where('name', 'ลท สำหรับ e-standard')->first();
                            @endphp

                             <table class="table table-striped" id="myTable">
                                <thead>
                                    <tr>
                                        <th width="1%" >#</th>
                                        {{-- <th width="1%"></th> --}}
                                        {{-- <th width="15%" >รหัสโครงการ</th> --}}
                                           <th width="10%" >บรรจุแผนปี</th>
                                           <th width="10%" >รหัสมาตรฐาน</th>
                                        <th width="20%" >ชื่อมาตรฐาน</th>
                                        <th width="20%" >ประเภทข้อเสนอ</th>
                                        {{-- <th width="10%" >วิธีการ</th> --}}
                                     
                                        <th width="10%" >ระยะเวลาจัดทำ</th>
                                        <th width="10%" >สถานะ</th>
                                        <th width="20%" style="text-align: right">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $filteredStandards = $setStandards->filter(function ($item) {
                                            // ใช้ optional() เพื่อให้โค้ดไม่ error
                                            // ถ้า estandard_plan_to หรือ estandard_offers_to เป็น null
                                            return !empty(optional($item->estandard_plan_to)->estandard_offers_to->proposer_type);
                                        });
                                    @endphp

                           
                            

                                    @foreach($filteredStandards as $index => $item)
                                        <tr>
                                            <td >{{ $setStandards->firstItem() + $index }}</td>
                                             <td >{{ $item->TisYear ?? '' }}</td>
                                             <td >{{ str_replace('Req', 'CSD', $item->estandard_plan_to->estandard_offers_to->refno ?? '') }}</td>
                                            <td >{{ $item->estandard_plan_to->estandard_offers_to->standard_name ?? '' }} <br> {{ $item->estandard_plan_to->estandard_offers_to->standard_name_en ?? '' }}</td>
                                            <td >@switch($item->estandard_plan_to->estandard_offers_to->proposer_type ?? '')
                                                @case('sdo_advanced')
                                                    SDO ขั้นสูง
                                                        @break

                                                    @case('sdo_basic_or_non_sdo')
                                                        SDO ขั้นต้น หรือหน่วยงานที่ไม่ใช่ SDO
                                                        @break
                                                @endswitch
                                                
                                                @if(!empty($item->estandard_plan_to->estandard_offers_to->proposer_type))
                                                    {{-- {{ $item->estandard_plan_to->estandard_offers_to->proposer_type }} --}}
                                                @endif
                                            </td>
                                           
                                            <td >{{ $item->Period ? $item->Period . ' เดือน' : '' }}</td>
                                            <td >{{ $item->StatusText ?? '' }}</td>
                                            <td style="text-align: right">
                                                
                                                <div style="display: inline-flex; align-items: center; gap: 5px;">
                                                    @if ($item->estandard_plan_to->estandard_offers_to->proposer_type == "sdo_advanced")


                                                       {{-- ขั้นสูง ลท อย่ามาทำที่นี่ --}}
                                                        {{-- @if(auth()->user()->roles->contains('id', $role->id))
                                                              @if ($item->mainAppointmentMeetingApproved->count() == 0)
                                                                    <a href="{{route('certify.appointed-lt-committee.create')}}" title="จัดการประชุมคณะกำหนด" class="btn btn-warning btn-xs" style="display: inline-block;">
                                                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                                    </a>
                                                                @else  
                                                                    <a href="{{url('/certify/set-standards/'.$item->id.'/edit')}}" title="จัดการประชุมคณะกำหนด" class="btn {{ $item->status_id == 5 ? 'btn-info' : 'btn-warning' }}  btn-xs" style="display: inline-block;">
                                                                        <i class="fa fa-pencil-square-o"></i>
                                                                    </a>
                                                                @endif
                                                        @endif --}}

                                                        @if(auth()->user()->roles->contains('id', $role->id))
                                                              {{-- @if ($item->mainAppointmentMeetingApproved->count() == 0) --}}
                                                              @if ($item->status_id != 5)
                                                              {{-- certify.appointed-lt-committee.create --}}
                                                                    {{-- <a href="{{route('certify.appointed-academic-sub-committee.create')}}" title="จัดการประชุมคณะกำหนด" class="btn btn-warning btn-xs" style="display: inline-block;"> --}}
                                                                    <a href="{{route('certify.appointed-lt-committee.create')}}" title="จัดการประชุมคณะกำหนด" class="btn btn-warning btn-xs" style="display: inline-block;">
                                                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                                    </a>
                                                                @else  
                                                                    <a href="{{url('/certify/set-standards/'.$item->id.'/edit')}}" title="จัดการประชุมคณะกำหนด" class="btn {{ $item->status_id == 5 ? 'btn-info' : 'btn-warning' }}  btn-xs" style="display: inline-block;">
                                                                        <i class="fa fa-pencil-square-o"></i>
                                                                    </a>
                                                                @endif

                                                        @else
                                                              @if ($item->status_id != 5)
                                                                   
                                                                   <span class="badge bg-warning text-dark">อยู่ระหว่างลท ดำเนินการ</span>
                                                                @else  
                                                                    <a href="{{url('/certify/set-standards/'.$item->id.'/edit')}}" title="จัดการประชุมคณะกำหนด" class="btn {{ $item->status_id == 5 ? 'btn-info' : 'btn-warning' }}  btn-xs" style="display: inline-block;">
                                                                        <i class="fa fa-pencil-square-o"></i>
                                                                    </a>
                                                                @endif

                                                        @endif

                                                            


                                                    {{-- @elseif($item->estandard_plan_to->method_to->id == 3) --}}
                                                    @elseif($item->estandard_plan_to->estandard_offers_to->proposer_type == "sdo_basic_or_non_sdo")
                                                    
                                                       {{-- @php
                                                           dd($role->name);
                                                       @endphp --}}
                                                       {{-- {{$role->name}} --}}
                                                        @if(!auth()->user()->roles->contains('id', $role->id))
                                                        {{-- ฉันไม่ใช่ลท --}}
                                                            @if ($item->subAppointmentMeetingApproved->count() == 0)
                                                                    <a href="{{route('certify.appointed-academic-sub-committee.create',['id' => $item->estandard_plan_to->estandard_offers_to->id])}}" title="หนังสือเชิญประชุมและนัดหมายประชุม" class="btn btn-warning btn-xs" style="display: inline-block;">
                                                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                                    </a>
                                                                @else  
                                                                {{-- certify.meeting-standards.create --}}
                                                                    <a href="{{url('/certify/set-standards/'.$item->id.'/edit_sub_appointment')}}" title="หนังสือเชิญประชุมและนัดหมายประชุม" class="btn {{ $item->status_sub_appointment_id == 5 ? 'btn-info' : 'btn-warning' }}  btn-xs" style="display: inline-block;">
                                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                    </a>

                                                                    {{-- <a href="{{route('certify.meeting-standards.create',['id' => $item->estandard_plan_to->estandard_offers_to->id])}}" title="หนังสือเชิญประชุมและนัดหมายประชุม" class="btn btn-warning btn-xs" style="display: inline-block;">
                                                                        <i class="fa fa-pencil" aria-hidden="true"></i> CC
                                                                    </a> --}}
                                                            @endif
                                                        @endif            

                                              
                                                        {{-- @php
                                                            dd($item);
                                                        @endphp --}}
                                                        {{-- {{$item->status_sub_appointment_id }} --}}
                                                        
                                                        @if ($item->status_sub_appointment_id == 5)
                                                            @if(!auth()->user()->roles->contains('id', $role->id))
                                                                <a href="javascript:void(0)" 
                                                                        title="เห็นชอบมติการประชุม" 
                                                                        class="btn {{ $item->agreement_status != null ? 'btn-info' : 'btn-warning' }} btn-xs btn-agreement" 
                                                                        style="display: inline-block;" 
                                                                        
                                                                        data-id="{{$item->id}}">
                                                                        <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                           

                                                            @if ($item->agreement_status == 1)

                                                                @if(!auth()->user()->roles->contains('id', $role->id))
                                                                    {{-- <a href="javascript:void(0)" data-id="{{$item->id}}" title="เวียนมาตรฐาน"  class="btn {{ $item->standard_circular_doc_status != null ? 'btn-info' : 'btn-warning' }} btn-xs  btn-standard-circular-doc" style="display: inline-block;">
                                                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                                                    </a> --}}

                                                                    @if ($item->standard_circular_doc_status != null)
                                                                        {{-- กรณีที่ standard_circular_doc_status มีค่า (ไม่เป็น null) --}}
                                                                        <a href="javascript:void(0)" data-id="{{$item->id}}" title="เวียนมาตรฐาน" 
                                                                        class="btn btn-info btn-xs btn-standard-circular-doc" 
                                                                        style="display: inline-block;">
                                                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                                                        </a>

                                                                        @if ($item->finished == 1)
                                                                            <a href="{{url('/certify/set-standards/'.$item->id.'/edit')}}" title="จัดการประชุมคณะกำหนด" class="btn {{ $item->status_id == 5 ? 'btn-info' : 'btn-warning' }}  btn-xs" style="display: inline-block;">
                                                                                <i class="fa fa-pencil-square-o"></i>
                                                                            </a>
                                                                        @else
                                                                            <span class="badge bg-warning text-dark">อยู่ระหว่างลท ดำเนินการ</span>
                                                                        @endif
                                                                        
                                                                    @else
                                                                        {{-- กรณีที่ standard_circular_doc_status เป็น null --}}
                                                                        <a href="javascript:void(0)" data-id="{{$item->id}}" title="เวียนมาตรฐาน" 
                                                                        class="btn btn-warning btn-xs btn-standard-circular-doc" 
                                                                        style="display: inline-block;">
                                                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                                                        </a>

                                                                        {{-- bbb --}}
                                                                    @endif
                                                                     
                                                                @endif
                                                                
                                                               
                                                                @if(auth()->user()->roles->contains('id', $role->id))  
                                                               
                                                                    @if ($item->standard_circular_doc_status != null)
                                                                     
                                                                    {{-- @php
                                                                        dd($item->mainAppointmentMeetingApproved->count())
                                                                    @endphp --}}
                                                                    {{-- {{$item->mainAppointmentMeetingApproved->count()}} --}}
                                                                        @if ($item->mainAppointmentMeetingApproved->count() == 0)
                                                                            <a href="{{route('certify.appointed-academic-sub-committee.create')}}" title="จัดการประชุมคณะกำหนด" class="btn btn-warning btn-xs" style="display: inline-block;">
                                                                                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                                            </a>
                                                                        @else  
                                                                            <a href="{{url('/certify/set-standards/'.$item->id.'/edit')}}" title="จัดการประชุมคณะกำหนด" class="btn {{ $item->status_id == 5 ? 'btn-info' : 'btn-warning' }}  btn-xs" style="display: inline-block;">
                                                                                <i class="fa fa-pencil-square-o"></i>
                                                                            </a>
                                                                        @endif

                                                                        @else 
                                                                       
                                                                    @endif
                                                                @else
                                                               

                                                                @endif         
                                                            @endif 
                                                        @endif
                                                          
                                                      

                                                    @endif
                                               
                                                    {{-- {{$item->estandard_plan_to->method_to->id}} --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination Links -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div id="myTable_length">
                                    <span class="totalrec" style="color:green;">
                                        <b>(ทั้งหมด {{ $setStandards->total() }} รายการ)</b>
                                    </span>
                                </div>
                                <div>
                                    {{-- {{ $setStandards->links() }} --}}
                                </div>
                            </div>

                    </div>
                </div>
           
                </div>
            </div>
        </div>
    </div>
@endsection



@push('js')
<script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
 

    <script>
        $(document).ready(function () {

// 

                         // Override the click event for the "เห็นชอบ" link to capture data-id
    $('.btn-agreement').on('click', function(e) {
        e.preventDefault(); // Prevent default Bootstrap modal trigger
        var dataId = $(this).data('id'); // Get the data-id attribute

            $.ajax({
            type: "POST",
            url: "{{ url('/certify/standard-drafts/get-agreement') }}",
            data: {
                _token: "{{ csrf_token() }}",
                id: dataId
            },
            success: function(data) {
                // console.log(data);
                // Set the agreement_detail in the textarea
                $('#agrement_detail').val(data.agreement_detail);
                // Set radio button based on agreement_status
                if (data.agreement_status === "1") {
                    $('#agreement_1').prop('checked', true); // Check "เห็นชอบ"
                    $('#agreement_2').prop('checked', false);
                } else if (data.agreement_status === "2" || data.agreement_status === "3") {
                    $('#agreement_2').prop('checked', true); // Check "ไม่เห็นชอบ"
                    $('#agreement_1').prop('checked', false);
                }

                  // Show or hide button_wrapper based on agreement_status
                if (data.agreement_status == null ) {
                    $('#button_wrapper').show();
                       $('#AddStdAgreement').find('input, textarea').prop('disabled', false);
                } else {
                    $('#button_wrapper').hide();
                      $('#AddStdAgreement').find('input, textarea').prop('disabled', true);
                }
                // Store data-id in the modal
                $('#AddStdAgreement').data('id', dataId);
                // Show the modal
                $('#AddStdAgreement').modal('show');
            },
            error: function(error) {
                console.error("Error fetching agreement data", error);
            }
        });

        $('#AddStdAgreement').data('id', dataId);
    });                                                 


    $('.btn-standard-circular-doc').on('click', function(e) {
        e.preventDefault(); // Prevent default Bootstrap modal trigger
        var dataId = $(this).data('id'); // Get the data-id attribute

            $.ajax({
                type: "POST",
                url: "{{ url('/certify/standard-drafts/get-agreement') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: dataId
                },
                success: function(data) {
                    console.log(data);
                   
                    $('#standard_circular_doc_detail').val(data.standard_circular_doc_details);
                
                    if (data.standard_circular_doc_status === "1") {
                        $('#standard_circular_doc_1').prop('checked', true); 
                        $('#standard_circular_doc_2').prop('checked', false);
                    } else if (data.standard_circular_doc_status === "2") {
                        $('#standard_circular_doc_2').prop('checked', true); 
                        $('#standard_circular_doc_1').prop('checked', false);
                    }

                
                    if (data.standard_circular_doc_status == null ) {
                        $('#button_circular_doc_wrapper').show();
                        $('#StandardCircularDoc').find('input, textarea').prop('disabled', false);
                    } else {
                        $('#button_circular_doc_wrapper').hide();
                        $('#StandardCircularDoc').find('input, textarea').prop('disabled', true);
                    }
                 
                    $('#StandardCircularDoc').data('id', dataId);
                     toggleCircularDocDetail();
              
                    $('#StandardCircularDoc').modal('show');
                },
                error: function(error) {
                    console.error("Error fetching agreement data", error);
                }
            });

        $('#StandardCircularDoc').data('id', dataId);
    });                                                 



        function toggleCircularDocDetail() {
            var selectedValue = $('input[name="standard_circular_doc"]:checked').val();
            if (selectedValue === "2") {
                $('#standard_circular_doc_detail_wrapper').show();
            } else {
                $('#standard_circular_doc_detail_wrapper').hide();
            }
        }

        // Set initial state (hide by default)
        $('#standard_circular_doc_detail_wrapper').hide();

        // Run toggle on radio button change
        $('input[name="standard_circular_doc"]').on('change', function() {
            toggleCircularDocDetail();
        });

        // Run toggle on modal show to handle initial state
        $('#AddStdAgreement').on('show.bs.modal', function() {
            toggleCircularDocDetail();
        });

        // Route::POST('certify/standard-drafts/update-standard-circular-doc', 'Certify\\SetStandardsController@updateStandardCircularDoc');

        $('#btn_add_circular_doc').on('click', function() {
            // Get the selected radio button value
            // var agreementValue = $('input[name="agreement"]:checked').val();
            var details = $('#standard_circular_doc_detail').val();
             var dataId = $('#StandardCircularDoc').data('id'); // Retrieve data-id
              var circularDocValue = $('input[name="standard_circular_doc"]:checked').val();

              console.log(circularDocValue,dataId,details)
                $.ajax({
                        type:"POST",
                        url:  "{{ url('/certify/standard-drafts/update-standard-circular-doc') }}",
                        data:{
                            _token: "{{ csrf_token() }}",
                            id: dataId,
                            circularDocValue: circularDocValue,
                            details: details,
                        },
                        success:function(data){
                            setTimeout(function() {
                                location.reload();
                            }, 1000); 
                        }
                });
        });

        $('#btn_add_std').on('click', function() {
            // Get the selected radio button value
            var agreementValue = $('input[name="agreement"]:checked').val();
            var details = $('#agrement_detail').val();
             var dataId = $('#AddStdAgreement').data('id'); // Retrieve data-id
// console.log(dataId);
            //  return;

            if (agreementValue === "1") {
                // If "เห็นชอบ" is selected
                // certify/standard-drafts/accept-standard
                console.log("เห็นชอบ selected with details: " + details);
                    $.ajax({
                            type:"POST",
                            url:  "{{ url('/certify/standard-drafts/accept-standard') }}",
                            data:{
                                _token: "{{ csrf_token() }}",
                                id: dataId,
                                details: details,
                            },
                            success:function(data){
                                setTimeout(function() {
                                    location.reload();
                                }, 1000); // รีโหลดหลังจาก 1 วินาที
                            }
                    });
                // Add your logic for "เห็นชอบ" here, e.g., submit form or AJAX call
            } else if (agreementValue === "2") {
                // If "ไม่เห็นชอบ" is selected, show Swal with three buttons
                Swal.fire({
                    icon: 'error',
                    title: 'ต้องการยกเลิกทำมาตรฐานหรือไม่ !',
                    html: `
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <button id="swal-new-standard" class="swal2-confirm swal2-styled" style="background-color: #28a745; font-size: 1.5rem; padding: 12px 24px; width: 98%;">ทำมาตรฐานใหม่</button>
                            <button id="swal-cancel-standard" class="swal2-confirm swal2-styled" style="background-color: #3085d6; font-size: 1.5rem; padding: 12px 24px; width: 98%;">ยกเลิกทำมาตรฐาน</button>
                            <button id="swal-cancel" class="swal2-cancel swal2-styled" style="background-color: #ffc107; font-size: 1.5rem; padding: 12px 24px; width: 98%;">ยกเลิก</button>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: () => {
                        // Attach event listeners to custom buttons
                        document.getElementById('swal-new-standard').addEventListener('click', () => {
                            Swal.close();
                            console.log("ทำมาตรฐานใหม่ confirmed with details: " + details);

                                $.ajax({
                                        type:"POST",
                                        url:  "{{ url('/certify/standard-drafts/renew-standard') }}",
                                        data:{
                                            _token: "{{ csrf_token() }}",
                                            id: dataId,
                                            details: details,
                                        },
                                        success:function(data){
                                                setTimeout(function() {
                                                    location.reload();
                                                }, 1000); // รีโหลดหลังจาก 1 วินาที       
                                        }
                                });
                            // Add your logic for "ทำมาตรฐานใหม่" here, e.g., reset form or redirect
                        });
                        document.getElementById('swal-cancel-standard').addEventListener('click', () => {
                            Swal.close();
                            console.log("ยกเลิกทำมาตรฐาน confirmed with details: " + details);

                                $.ajax({
                                        type:"POST",
                                        url:  "{{ url('/certify/standard-drafts/end-standard') }}",
                                        data:{
                                            _token: "{{ csrf_token() }}",
                                            id: dataId,
                                            details: details,
                                        },
                                        success:function(data){
                                            setTimeout(function() {
                                                location.reload();
                                            }, 1000); // รีโหลดหลังจาก 1 วินาที
                                        }
                                });
                            // Add your logic for "ยกเลิกทำมาตรฐาน" here, e.g., AJAX call to delete
                        });
                        document.getElementById('swal-cancel').addEventListener('click', () => {
                            Swal.close();
                            console.log("Action cancelled");
                            // Logic for when "ยกเลิก" is clicked
                        });
                    }
                });
            }
        });
            //ช่วงวันที่
            $('.date-range').datepicker({
              toggleActive: true,
              language:'th-th',
              format: 'dd/mm/yyyy',
            });

            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
            
            // var table = $('#myTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     searching: false,
            //     ajax: {
            //         url: '{!! url('/certify/set-standards/data_list') !!}',
            //         data: function (d) {
            //             d.filter_search = $('#filter_search').val();
            //             d.filter_year = $('#filter_year').val();
            //             d.filter_standard_type = $('#filter_standard_type').val();
            //             d.filter_method_id = $('#filter_method_id').val();
            //             d.filter_status = $('#filter_status').val();
            //         }
            //     },
            //     columns: [
            //         { data: 'DT_Row_Index', searchable: false, orderable: false},
            //         { data: 'checkbox', searchable: false, orderable: false},
            //         { data: 'projectid', name: 'projectid' },
            //         { data: 'tis_name', name: 'tis_name' }, 
            //         { data: 'std_type', name: 'std_type' },
            //         { data: 'method_id', name: 'method_id' },
            //         { data: 'tis_year', name: 'tis_year' },
            //         { data: 'period', name: 'period' },
            //         { data: 'status', name: 'status' },
            //         { data: 'action', name: 'action' },
            //     ],
            //     columnDefs: [
            //         { className: "text-center", targets:[0,-1] }
            //     ],
            //     fnDrawCallback: function() {
            //         $('#myTable_length').find('.totalrec').remove();
            //         var el = ' <span class=" totalrec" style="color:green;"><b>(ทั้งหมด '+ Comma(table.page.info().recordsTotal) +' รายการ)</b></span>';
            //         $('#myTable_length').append(el);

            //         $('#myTable tbody').find('.dataTables_empty').addClass('text-center');
            //     }
            // });



            $( "#button_search" ).click(function() {
                 table.draw();
            });

            $( "#filter_clear" ).click(function() {
                $('#filter_search').val('');
                $('#filter_year').val('').select2();
                $('#filter_standard_type').val('').select2();
                $('#filter_method_id').val('').select2();
                $('#filter_status').val('').select2();
                table.draw();
           });
 
           $('#checkall').change(function (event) {

                if ($(this).prop('checked')) {//เลือกทั้งหมด
                    $('#myTable').find('input.item_checkbox').prop('checked', true);
                } else {
                    $('#myTable').find('input.item_checkbox').prop('checked', false);
                }

         });

 

            $(document).on('click', '#bulk_delete', function(){

                var id = [];
                $('.item_checkbox:checked').each(function(index, element){
                    id.push($(element).val());
                });

                if(id.length > 0){

                    if (confirm("ยืนยันการลบข้อมูล " + id.length + " แถว นี้ ?")) {
                        $.ajax({
                                type:"POST",
                                url:  "{{ url('/certify/send-certificates/delete') }}",
                                data:{
                                    _token: "{{ csrf_token() }}",
                                    id: id
                                },
                                success:function(data){
                                    table.draw();
                                    $.toast({
                                        heading: 'Success!',
                                        position: 'top-center',
                                        text: 'ลบสำเร็จ !',
                                        loaderBg: '#70b7d6',
                                        icon: 'success',
                                        hideAfter: 3000,
                                        stack: 6
                                    });
                                    $('#checkall').prop('checked', false);
                                }
                        });
                    }

                }else{
                    alert("โปรดเลือกอย่างน้อย 1 รายการ");
                }
            });

 
 



        });

        
        function confirm_delete() {
                    return confirm("ยืนยันการลบข้อมูล?");
        }

      
        function Comma(Num)
        {
            Num += '';
            Num = Num.replace(/,/g, '');

            x = Num.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1))
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
            return x1 + x2;
        }

    </script>

@endpush
