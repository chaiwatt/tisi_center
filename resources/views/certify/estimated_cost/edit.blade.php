{{-- work on Certify\EstimatedCostController --}}
@extends('layouts.master')
@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/summernote/summernote.css')}}" rel="stylesheet" type="text/css" />
    <style>
                .modal-xl {
            width: 80%; /* กำหนดความกว้างตามที่คุณต้องการ */
            max-width: none; /* ยกเลิกค่า max-width เริ่มต้น */
        }
        .modal-xxl {
            width: 90%; /* กำหนดความกว้างตามที่คุณต้องการ */
            max-width: none; /* ยกเลิกค่า max-width เริ่มต้น */
        }
    </style>
@endpush


@section('content')

    <div class="container-fluid" id="app_estimated_cost">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบการประมาณการค่าใช้จ่าย</h3>
                    @can('view-'.str_slug('auditor'))
                        <a class="btn btn-success pull-right" href="{{ route('estimated_cost.index', ['app' => $app ? $app->id : '']) }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::open(['url' => route('estimated_cost.update', ['cost' => $ec, 'app' => $app ? $app->id : '']), 'method'=>'put','id'=>'cost_form', 'class' => 'form-horizontal', 'files' => true]) !!}
           <div class="row form-group">
               <div class="col-md-12">
                <div class="white-box" style="border: 2px solid #e5ebec;">
                    <legend><h3>การประมาณการค่าใช้จ่าย</h3></legend>

                        <div class="row">
                                <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label class="col-md-4 text-right"><span class="text-danger">*</span> เลขคำขอ : </label>
                                        <div class="col-md-8">

                                            <input type="text" class="form-control" value="{{ $ec->applicant->app_no ?? null}}" disabled >
                                            <input type="hidden" name="app_no" value="{{ $ec->applicant->app_no ?? null}}" >
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="col-md-2 text-right">หน่วยงาน : </label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" value="{{ $ec->applicant->BelongsInformation->name ?? null}}" name="department" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-8"> </div>
                                    <div class="col-md-4 text-right">
                                        <button type="button" class="btn btn-success btn-sm" id="addCostInput"><i class="icon-plus"></i> เพิ่ม</button>
                                    </div>
                                    <div class="col-sm-12 m-t-15">
                                        <table class="table color-bordered-table primary-bordered-table">
                                            <thead>
                                            <tr>
                                                <th class="text-center" width="2%">#</th>
                                                <th class="text-center" width="38%">รายละเอียด</th>
                                                <th class="text-center" width="20%">จำนวนเงิน</th>
                                                <th class="text-center" width="10%">จำนวนวัน</th>
                                                <th class="text-center" width="20%">รวม (บาท)</th>
                                                <th class="text-center" width="5%">ลบ</th>
                                            </tr>
                                            </thead>
                                            <tbody id="table-body">
                                                @foreach($cost_item as $item)
                                                    <tr>
                                                        <td  class="text-center">
                                                            1
                                                        </td>
                                                        <td>
                                                            {!! Form::select('detail[desc][]',
                                                             App\Models\Bcertify\StatusAuditor::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id'),
                                                             $item->desc ?? null,
                                                             ['class' => 'form-control select2 desc',
                                                              'required'=>true,
                                                             'placeholder'=>'- เลือกรายละเอียดการประมาณการค่าใช้จ่าย -']); !!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('detail[cost][]', number_format($item->amount,2) ?? null,  ['class' => 'form-control input_number cost_rate  text-right','required'=>true])!!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('detail[nod][]', $item->amount_date ?? null,  ['class' => 'form-control amount_date  text-right','required'=>true])!!}
                                                        </td>
                                                        <td>
                                                            {!! Form::text('number[]',  number_format(($item->amount_date *  $item->amount),2)  ?? null ,  ['class' => 'form-control number  text-right','readonly'=>true])!!}
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <footer>
                                                <tr>
                                                    <td colspan="4" class="text-right">รวม</td>
                                                    <td>
                                                        {!! Form::text('costs_total',
                                                            null,
                                                            ['class' => 'form-control text-right costs_total',
                                                                'id'=>'costs_total',
                                                                'disabled'=>true
                                                            ])
                                                        !!}
                                                    </td>
                                                    <td>
                                                         บาท
                                                    </td>
                                                </tr>
                                            </footer>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>


            <div class="clearfix"></div>
            <input type="hidden" id="lab_addresses_input" name="lab_addresses" />
            <input type="hidden" id="lab_main_address_input" name="lab_main_address" />
            <div class="row form-group">
                <div class="col-md-12">
                    <div class="white-box" style="border: 2px solid #e5ebec;" >
                        <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope) </h3></legend>
                        <div class="row">
                            <div class="col-md-12 text-left" id="scope_wrapper">
                                {{-- @if ($labCalRequest->count() != 0)
                                        <a type="button" href="{{route('certify.generate_pdf_lab_cal_scope',['id' => $certi_lab->id])}}" class="btn btn-info" target="_blank"><b>ดาวน์โหลดขอบข่าย</b> </a>
                                    @elseif($labTestRequest->count() != 0)
                                        <a type="button" href="{{route('certify.generate_pdf_lab_test_scope',['id' => $certi_lab->id])}}" class="btn btn-info" target="_blank"><b>ดาวน์โหลดขอบข่าย</b> </a>
                                @endif --}}

                                @if($ec->attachs !== null && (count(json_decode($ec->attachs)) > 0) )
                                        @php
                                            $attachs = json_decode($ec->attachs);
                                        @endphp
                                        @foreach($attachs as $key => $item)
                                            <div class="form-group ">
  
                                                <div class="col-md-12 text-light">
                                                    <a href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->file_client_name) ? $item->file_client_name :   basename($item->attachs) ))}}" target="_blank">
                                                        {!! HP::FileExtension($item->attachs)  ?? '' !!}
                                                        {{ !empty($item->file_client_name) ? $item->file_client_name :   basename($item->attachs)}}
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @if ($certi_lab_attach_all61->count() > 0)
                                            @php
                                                $latestFile = $certi_lab_attach_all61->sortByDesc('created_at')->first(); // สมมติว่าใช้ 'created_at' แทนวันที่
                                            @endphp

                                            @if ($latestFile && $latestFile->file)
                                                <div class="col-md-12 form-group">
                                                    <a href="{{ url('certify/check/file_client/'.$latestFile->file.'/'.( !is_null($latestFile->file_client_name) ? $latestFile->file_client_name : basename($latestFile->file) )) }}" target="_blank">
                                                        {!! HP::FileExtension($latestFile->file) ?? '' !!}
                                                        {{ !empty($latestFile->file_client_name) ? $latestFile->file_client_name : basename($latestFile->file) }}
                                                    </a>
                                                </div>
                                            @endif
                                        @elseif($certi_lab_attach_all62->count() > 0)
                                            @php
                                                $latestFile62 = $certi_lab_attach_all62->sortByDesc('created_at')->first(); // สมมติว่าใช้ 'created_at' แทนวันที่
                                            @endphp

                                            @if ($latestFile62 && $latestFile62->file)
                                                <div class="col-md-12 form-group">
                                                    <a href="{{ url('certify/check/file_client/'.$latestFile62->file.'/'.( !is_null($latestFile62->file_client_name) ? $latestFile62->file_client_name : basename($latestFile62->file) )) }}" target="_blank">
                                                        {!! HP::FileExtension($latestFile62->file) ?? '' !!}
                                                        {{ !empty($latestFile62->file_client_name) ? $latestFile62->file_client_name : basename($latestFile62->file) }}
                                                    </a>
                                                </div>
                                            @endif            
                                        @endif

                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>

                    <div class="clearfix"></div>
                    <div class="row form-group" hidden>
                        <div class="col-md-12">
                            <div class="white-box" style="border: 2px solid #e5ebec;">
                            <legend><h3>ขอบข่ายที่ขอรับการรับรอง (Scope)</h3></legend>
                                
                                @if($ec->attachs != '' && (count(json_decode($ec->attachs)) > 0) )
                                @php
                                        $attachs = json_decode($ec->attachs);
                                 @endphp
                                     <div id="attach-box">
                                        <div class="form-group other_attach_item">
                                            <div class="col-md-4  text-light">
                                                <label for="" class="col-md-12 label_attach text-light  control-label"> <span class="text-danger">*</span> กรุณาแนบไฟล์ Scope</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                    <div class="form-control" data-trigger="fileinput">
                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-addon btn btn-default btn-file">
                                                        <span class="fileinput-new">เลือกไฟล์</span>
                                                        <span class="fileinput-exists">เปลี่ยน</span>
                                                        <input type="file" name="attachs[]" class="attachs check_max_size_file">
                                                    </span>
                                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                                </div>
                                            </div>
                                            <div class="col-md-2" >
                                                <button type="button" class="btn btn-sm btn-success attach-add"  id="attach-add">
                                                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                </button>
                                                <span class="text-danger attach-span">(.pdf)</span>
                                                <div class="button_remove"></div>
                                            </div>
                                        </div>
                                    </div>
                                @foreach($attachs as $key => $item)
                                   <div class="form-group ">
                                       <div class="col-md-4  text-light">
                                       </div>
                                        <div class="col-md-6 text-light">
                                            <a href="{{url('certify/check/file_client/'.$item->attachs.'/'.( !empty($item->file_client_name) ? $item->file_client_name :   basename($item->attachs) ))}}" target="_blank">
                                                {!! HP::FileExtension($item->attachs)  ?? '' !!}
                                                {{ !empty($item->file_client_name) ? $item->file_client_name :   basename($item->attachs)}}
                                            </a>
                                        </div>
                                        <div class="col-md-1 text-left">
                                            <button class="btn btn-danger btn-sm " type="button" onclick="deleteFlie({{ $key +1  }})">
                                                <i class="icon-close"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                    <div id="attach-box">
                                        <div class="form-group other_attach_item">
                                            <div class="col-md-4  text-light">
                                                <label for="" class="col-md-12 label_attach text-light  control-label"> <span class="text-danger">*</span> กรุณาแนบไฟล์ Scope</label>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                    <div class="form-control" data-trigger="fileinput">
                                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                        <span class="fileinput-filename"></span>
                                                    </div>
                                                    <span class="input-group-addon btn btn-default btn-file">
                                                        <span class="fileinput-new">เลือกไฟล์</span>
                                                        <span class="fileinput-exists">เปลี่ยน</span>
                                                        <input type="file" name="attachs[]" class="attachs check_max_size_file">
                                                    </span>
                                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                                </div>
                                            </div>
                                            <div class="col-md-2" >
                                                <button type="button" class="btn btn-sm btn-success attach-add"  id="attach-add">
                                                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                                                </button>
                                                <span class="text-danger attach-span">(.pdf)</span>
                                                <div class="button_remove"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                 <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>


@if(count($ec->CertificateHistorys) > 0  && !is_null($ec->date))
<div class="row form-group">
    <div class="col-md-12">
     <div class="white-box" style="border: 2px solid #e5ebec;">
         <legend><h3>เหตุผล / หมายเหตุ ขอแก้ไข</h3></legend>


<div class="row">
    <div class="col-md-12">
         <div class="panel block4">
            <div class="panel-group" id="accordion">
               <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                         <a data-toggle="collapse" data-parent="#accordion" href="#collapse"> <dd> เหตุผล / หมายเหตุ ขอแก้ไข</dd>  </a>
                    </h4>
                     </div>

<div id="collapse" class="panel-collapse collapse in">
 <br>
 @foreach($ec->CertificateHistorys->reverse() as $key1 => $item)
    <div class="row form-group">
        <div class="col-md-1"></div>
            <div class="col-md-10">
                <div class="white-box" style="border: 2px solid #e5ebec;">
                    <legend>
                        <h3>
                           @if($item->check_status == 1 && $item->status_scope == 1)
                           @php
                               $back = true; // กลับหน้า index
                           @endphp
                            <i class="fa fa-check-square" style="color:rgb(8, 180, 2);font-size:30px;" aria-hidden="true"></i>
                            @elseif($item->check_status == null && $item->status_scope == null)
                            <i class="fa fa-paper-plane" style="color:rgb(4, 0, 255);font-size:30px;" aria-hidden="true"></i>
                           @else
                            <i class="fa fa-exclamation-triangle" style="color:rgb(229, 255, 0); background-color: red;font-size:30px;" aria-hidden="true"></i>
                           @endif
                           ครั้งที่ {{ $key1 +1}}
                       </h3>
                    </legend>


 @if(!is_null($item->details_table))
@php
    $details_table =json_decode($item->details_table);
@endphp
<h4>1. จำนวนวันที่ใช้ตรวจประเมินทั้งหมด <span>{{ $item->MaxAmountDate  ?? '-' }}</span> วัน</h4>
<h4>2. ค่าใช้จ่ายในการตรวจประเมินทั้งหมด <span>{{ $item->SumAmount ?? '-' }}</span> บาท </h4>
<div class="container-fluid">
    <table class="table table-bordered" id="myTable_labTest">
        <thead class="bg-primary">
        <tr>
            <th class="text-center text-white" width="2%">ลำดับ</th>
            <th class="text-center text-white" width="38%">รายละเอียด</th>
            <th class="text-center text-white" width="20%">จำนวนเงิน (บาท)</th>
            <th class="text-center text-white" width="20%">จำนวนวัน (วัน)</th>
            <th class="text-center text-white" width="20%">รวม (บาท)</th>
        </tr>
        </thead>
        <tbody id="costItem">
            @foreach($details_table as $key => $item2)
                @php
                $amount_date = !empty($item2->amount_date) ? $item2->amount_date : 0 ;
                $amount = !empty($item2->amount) ? $item2->amount : 0 ;
                $sum =   $amount*$amount_date;
                $details =  App\Models\Bcertify\StatusAuditor::where('id',$item2->desc)->first();
                @endphp
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ !is_null($details) ? $details->title : null  }}</td>
                    <td class="text-right">{{ number_format($amount, 2) }}</td>
                    <td class="text-right">{{ $amount_date }}</td>
                    <td class="text-right">{{ number_format($sum, 2) ?? '-'}}</td>
                </tr>
            @endforeach
        </tbody>
        <footer>
            <tr>
                <td colspan="4" class="text-right">รวม</td>
                <td class="text-right">
                     {{ $item->SumAmount ?? '-' }}
                </td>
            </tr>
        </footer>
    </table>
</div>
@endif

@if ($item->scope_group !== null)
{{-- <div class="row" style="margin-bottom: 20px !important">
    <div class="col-md-3 text-right">
        ขอบข่าย
     
    </div>
    <div class="col-md-9">
        <a  type="button" class="btn btn-info btn-scope-group text-white" data-group="{{$item->scope_group}}" data-created_at="" data-certi_lab="{{$certi_lab->id}}" ><i class="fa fa-file-o"></i> ขอบข่ายdd</a>
    </div>
</div> --}}

@endif

@if(!is_null($item->attachs))
@php
$attachs = json_decode($item->attachs);
@endphp
<div class="row">
<div class="col-md-3 text-right">
<p class="text-nowrap">หลักฐาน Scope:</p>
</div>
<div class="col-md-9">
@foreach($attachs as $scope)
        <p>
            <a href="{{url('certify/check/file_client/'.$scope->attachs.'/'.( !empty($scope->file_client_name) ? $scope->file_client_name :  basename($scope->attachs) ))}}" target="_blank">
                {!! HP::FileExtension($scope->attachs)  ?? '' !!}
                {{  !empty($scope->file_client_name) ? $scope->file_client_name : basename($scope->attachs)}}
            </a>
        </p>
    @endforeach
</div>

</div>

@endif

@if(!is_null($item->created_at))
<div class="row">
<div class="col-md-3 text-right">
    <p class="text-nowrap">วันที่บันทึก</p>
</div>
<div class="col-md-9">
    {{ @HP::DateThai($item->created_at) ?? '-' }}
</div>
</div>
@endif

@if(!is_null($item->check_status) && !is_null($item->status_scope))
<legend><h3>เหตุผล / หมายเหตุ ขอแก้ไข</h3></legend>
    @php
    $details = json_decode($item->details);
    @endphp

    <div class="row">
       <div class="col-md-3 text-right">
                <p class="text-nowrap">เห็นชอบกับค่าใช่จ่าย</p>
        </div>
        <div class="col-md-9">
            <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($item->check_status == 1 ) ? 'checked' : ' '  }}>  &nbsp;ยืนยัน &nbsp;</label>
            <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($item->check_status == 2 ) ? 'checked' : ' '  }}>  &nbsp;แก้ไข &nbsp;</label>
        </div>
    </div>

    @if(isset($details->remark) && $item->check_status == 2)
        <div class="row">
        <div class="col-md-3 text-right">
        <p class="text-nowrap">หมายเหตุ</p>
        </div>
        <div class="col-md-9">
           {{ @$details->remark ?? ''}}
        </div>
        </div>
    @endif

     @if(!is_null($item->attachs_file))
        @php
        $attachs_file = json_decode($item->attachs_file);
        @endphp
        <div class="row">
        <div class="col-md-3 text-right">
        <p class="text-nowrap">หลักฐาน:</p>
        </div>
        <div class="col-md-9">
        @foreach($attachs_file as $files)
            <p>
                @if(isset($files->file))
                {{  @$files->file_desc  }}
                <a href="{{url('certify/check/file_client/'.$files->file.'/'.( !empty($files->file_client_name) ? $files->file_client_name :  basename($files->file) ))}}" target="_blank">
                    {!! HP::FileExtension($files->file)  ?? '' !!}
                    {{  !empty($files->file_client_name) ? $files->file_client_name : basename($files->file)}}
                </a>
                @endif
            </p>
        @endforeach
        </div>
        </div>
    @endif

    {{-- <div class="row">
       <div class="col-md-3 text-right">
           <p class="text-nowrap">เห็นชอบกับ Scope</p>
        </div>
        <div class="col-md-9">
            <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-green" {{ ($item->status_scope == 1 ) ? 'checked' : ' '  }}>  &nbsp;ยืนยัน Scope &nbsp;</label>
            <label>   <input type="radio" class="check check-readonly" data-radio="iradio_square-red" {{ ($item->status_scope == 2 ) ? 'checked' : ' '  }}>  &nbsp; แก้ไข Scope &nbsp;</label>
        </div>
    </div> --}}

    @if(isset($details->remark_scope) && $item->status_scope == 2)
        <div class="row">
        <div class="col-md-3 text-right">
        <p class="text-nowrap">หมายเหตุ</p>
        </div>
        <div class="col-md-9">
           {{ @$details->remark_scope ?? ''}}
        </div>
        </div>
    @endif

    @if(!is_null($item->evidence))
    @php
    $evidence = json_decode($item->evidence);
    @endphp
    <div class="row">
    <div class="col-md-3 text-right">
    <p class="text-nowrap">หลักฐาน:</p>
    </div>
    <div class="col-md-9">
    @foreach($evidence as $files)
        <p>
            @if(isset($files->attach_files))
              {{  @$files->file_desc_text  }}
             <a href="{{url('certify/check/file_client/'.$files->attach_files.'/'.( !empty($files->file_client_name) ? $files->file_client_name : basename($files->attach_files) ))}}" target="_blank">
                {!! HP::FileExtension($files->attach_files)  ?? '' !!}
                {{  !empty($files->file_client_name) ? $files->file_client_name :  basename($files->attach_files)}}
            </a>
            @endif
        </p>
    @endforeach
    </div>
    </div>
    @endif

    @if(!is_null($item->date))
    <div class="row">
    <div class="col-md-3 text-right">
        <p class="text-nowrap">วันที่บันทึก</p>
    </div>
    <div class="col-md-9">
        {{ @HP::DateThai($item->date) ?? '-' }}
    </div>
    </div>
    @endif

 @endif

            </div>
        </div>
    <div class="col-md-1"></div>
    </div>
@endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endif


@if(isset($back))
    <div class="clearfix"></div>
        <a  href="{{ url("certify/estimated_cost") }}"  class="btn btn-default btn-lg btn-block">
            <i class="fa fa-rotate-left"></i>
                 <b>กลับ</b>
         </a>
@else
   <div id="status_btn"></div>
    <div class="form-group">
         <div class="col-md-18 text-center">
            <input type="checkbox" id="vehicle" name="vehicle" value="1" checked>
            <label for="vehicle1">ส่ง e-mail แจ้งผู้ประกอบการเพื่อยืนยันข้อมูล</label>
             <br>
            <button v-if="isShowDraft"  type="submit" class="btn btn-success" name="draft" value="0"   ><i class="fa fa-file-o"></i> ฉบับร่าง</button>


            <button class="btn btn-primary" type="submit" id="form-save" onclick="submit_form('1');return false">
                <i class="fa fa-paper-plane"></i>
                 บันทึก
            </button>

            <a href="{{ url('certify/estimated_cost') }}" class="btn btn-default"><i class="fa fa-rotate-left"></i> ยกเลิก</a>
        </div>
    </div>
 @endif



 {!! Form::close() !!}


                </div>
            </div>
        </div>
    </div>

    @include ('certify.extend-modal.lab.cal')
@endsection


@push('js')


<script>
    let labCalRequest
    let labTestRequest
    let labRequestMain 
    let labRequestBranchs 
    let labRequestType = "test"

    $(document).ready(function () {

        // ตัวแปร labCalRequest และ labTestRequest ที่ได้รับค่าจาก PHP
        let labCalRequest = @json($labCalRequest ?? []);
        let labTestRequest = @json($labTestRequest ?? []);

        // ตรวจสอบความยาวของ labTestRequest
        // console.log('Lab Test Request Length:', labTestRequest.length);

        // หาก labTestRequest ว่าง หรือไม่มีค่า ใช้ labCalRequest แทน
        if (labTestRequest.length > 0) {
            labRequestType = "test"
            console.log('LabTestRequest มีข้อมูล:', labTestRequest);
            labRequestMain = labTestRequest.filter(request => request.type === "1")[0];
            labRequestBranchs = labTestRequest.filter(request => request.type === "2");
        } else if (labCalRequest.length > 0) {
            labRequestType = "cal"
            console.log('LabCalRequest มีข้อมูล:', labCalRequest);
            labRequestMain = labCalRequest.filter(request => request.type === "1")[0];
            labRequestBranchs = labCalRequest.filter(request => request.type === "2");
        } else {
            labRequestType = "old_version"
        }

    });
</script>

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('js/app.js') }}"></script>


    <script src="{{ asset('plugins/components/summernote/summernote.js') }}"></script>
    <script src="{{ asset('plugins/components/summernote/summernote-ext-specialchars.js') }}"></script>

    <script src="{{asset('plugins/components/sweet-alert2/sweetalert2.all.min.js')}}"></script>

    {{-- <script src="{{asset('assets/js/lab/cal/scope.js?v=1.0')}}"></script> --}}

    <script src="{{asset('assets/js/lab/labscope_manager.js?v=1.0')}}"></script>

    <script>
        var labCalScopeTransactions;
        var branchLabAdresses;
        var certi_lab;
        var certificateHistorys;
        var labCalScopeTransactionGroups;

        $(document).ready(function () {
            labCalScopeTransactions = @json($labCalScopeTransactions ?? []);
            branchLabAdresses = @json($branchLabAdresses ?? []);
            certi_lab = @json($certi_lab ?? null);
            certificateHistorys = @json($ec->CertificateHistorys ?? null);
            labCalScopeTransactionGroups = @json($labCalScopeTransactionGroups ?? null);


            // console.log(labCalScopeTransactionGroups);
             
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
            //เพิ่มแถว
            $('#addCostInput').click(function(event) {
                var data_list = $('.desc').find('option[value!=""]:not(:selected):not(:disabled)').length;
                    if(data_list == 0){
                        Swal.fire('หมอรายการรายละเอียดประมาณค่าใช้จ่าย !!')
                        return false;
                }
              //Clone
              $('#table-body').children('tr:first()').clone().appendTo('#table-body');
              //Clear value
                var row = $('#table-body').children('tr:last()');
                row.find('select.select2').val('');
                row.find('select.select2').prev().remove();
                row.find('select.select2').removeAttr('style');
                row.find('select.select2').select2();
                row.find('input[type="text"]').val('');
              ResetTableNumber();
              IsInputNumber();
              IsNumber();
              cost_rate();
              data_list_disabled();
            });

        });

           //ลบแถว
           $('body').on('click', '.remove-row', function(){
              $(this).parent().parent().remove();
              ResetTableNumber();
              TotalValue();
              data_list_disabled();
            });

            function cost_rate() {
             $('.cost_rate,.amount_date').keyup(function(event) {
             var row = $(this).parent().parent();
             var cost_rate =   row.find('.cost_rate').val();
             var amount_date =   row.find('.amount_date').val();

                if(cost_rate != '' && amount_date != ''){
                    var sum = RemoveCommas(cost_rate) * amount_date;
                    row.find('.number').val(addCommas(sum.toFixed(2), 2));
                }else{
                    row.find('.number').val('');
                }
                TotalValue();
             });

             $('.cost_rate,.amount_date').change(function(event) {
             var row = $(this).parent().parent();
             var cost_rate =   row.find('.cost_rate').val();
             var amount_date =   row.find('.amount_date').val();

                if(cost_rate != '' && amount_date != ''){
                    var sum = RemoveCommas(cost_rate) * amount_date;
                    row.find('.number').val(addCommas(sum.toFixed(2), 2));
                }else{
                    row.find('.number').val('');
                }
                TotalValue();
             });
         }
            ResetTableNumber();
            TotalValue();
            IsInputNumber();
            IsNumber();
            cost_rate();
            data_list_disabled();

            function ResetTableNumber(){
                var rows = $('#table-body').children(); //แถวทั้งหมด
                (rows.length==1)?$('.remove-row').hide():$('.remove-row').show();
                rows.each(function(index, el) {
                    //เลขรัน
                    $(el).children().first().html(index+1);
                });
             }
             function  TotalValue() {
            var rows = $('#table-body').children(); //แถวทั้งหมด
            var total_all = 0.00;
            rows.each(function(index, el) {
                if($(el).children().find("input.number").val() != ''){
                    var number = parseFloat(RemoveCommas($(el).children().find("input.number").val()));
                    total_all  += number;
                }
            });
            $('#costs_total').val(addCommas(total_all.toFixed(2), 2));
           }

           function RemoveCommas(str) {
                  var res = str.replace(/[^\d\.\-\ ]/g, '');
                   return   res;
             }

            function  addCommas(nStr, decimal){
                    var tmp='';
                    var zero = '0';

                    nStr += '';
                    x = nStr.split('.');

                    if((x.length-1) >= 1){//ถ้ามีทศนิยม
                        if(x[1].length > decimal){//ถ้าหากหลักของทศนิยมเกินที่กำหนดไว้ ตัดให้เหลือเท่าที่กำหนดไว้
                            x[1] = x[1].substring(0, decimal);
                        }else if(x[1].length < decimal){//ถ้าหากหลักของทศนิยมน้อยกว่าที่กำหนดไว้ เพิ่ม 0
                            x[1] = x[1] + zero.repeat(decimal-x[1].length);
                        }
                        tmp = '.'+x[1];
                    }else{//ถ้าไม่มีทศนิยม
                        if(parseInt(decimal)>0){//ถ้ามีการกำหนดให้มี ทศนิยม
                                tmp = '.'+zero.repeat(decimal);
                            }
                    }
                    x1 = x[0];
                    var rgx = /(\d+)(\d{3})/;
                    while (rgx.test(x1)) {
                        x1 = x1.replace(rgx, '$1' + ',' + '$2');
                    }
                    return x1+tmp;
          }
          function IsNumber() {
            // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
                    $(".amount_date").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                    }
                    });
          }


          function IsInputNumber() {
                    // ฟังก์ชั่นสำหรับค้นและแทนที่ทั้งหมด
                    String.prototype.replaceAll = function(search, replacement) {
                    var target = this;
                    return target.replace(new RegExp(search, 'g'), replacement);
                    };

                    var formatMoney = function(inum){ // ฟังก์ชันสำหรับแปลงค่าตัวเลขให้อยู่ในรูปแบบ เงิน
                    var s_inum=new String(inum);
                    var num2=s_inum.split(".");
                    var n_inum="";
                    if(num2[0]!=undefined){
                    var l_inum=num2[0].length;
                    for(i=0;i<l_inum;i++){
                    if(parseInt(l_inum-i)%3==0){
                    if(i==0){
                    n_inum+=s_inum.charAt(i);
                    }else{
                    n_inum+=","+s_inum.charAt(i);
                    }
                    }else{
                    n_inum+=s_inum.charAt(i);
                    }
                    }
                    }else{
                    n_inum=inum;
                    }
                    if(num2[1]!=undefined){
                    n_inum+="."+num2[1];
                    }
                    return n_inum;
                    }
                    // อนุญาติให้กรอกได้เฉพาะตัวเลข 0-9 จุด และคอมม่า
                    $(".input_number").on("keypress",function(e){
                    var eKey = e.which || e.keyCode;
                    if((eKey<48 || eKey>57) && eKey!=46 && eKey!=44){
                    return false;
                    }
                    });

                    // ถ้ามีการเปลี่ยนแปลง textbox ที่มี css class ชื่อ css_input1 ใดๆ
                    $(".input_number").on("change",function(){
                    var thisVal=$(this).val(); // เก็บค่าที่เปลี่ยนแปลงไว้ในตัวแปร
                            if(thisVal != ''){
                                if(thisVal.replace(",","")){ // ถ้ามีคอมม่า (,)
                            thisVal=thisVal.replaceAll(",",""); // แทนค่าคอมม่าเป้นค่าว่างหรือก็คือลบคอมม่า
                            thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }else{ // ถ้าไม่มีคอมม่า
                            thisVal = parseFloat(thisVal); // แปลงเป็นรูปแบบตัวเลข
                            }
                            thisVal=thisVal.toFixed(2);// แปลงค่าที่กรอกเป้นทศนิยม 2 ตำแหน่ง
                            $(this).data("number",thisVal); // นำค่าที่จัดรูปแบบไม่มีคอมม่าเก็บใน data-number
                            $(this).val(formatMoney(thisVal));// จัดรูปแบบกลับมีคอมม่าแล้วแสดงใน textbox นั้น
                            }else{
                                $(this).val('');
                            }
                    });
          }
          function data_list_disabled(){
                $('.desc').children('option').prop('disabled',false);
                $('.desc').each(function(index , item){
                    var data_list = $(item).val();
                    $('.desc').children('option[value="'+data_list+'"]:not(:selected):not([value=""])').prop('disabled',true);
                });
            }


            function getUniqueCalMainBranches(lab_addresses_json, lab_main_address_json) {
                var resultArray = [];

                // สร้าง array ของ branch IDs และ main facilities
                var branches = ['pl_2_1_branch', 'pl_2_2_branch', 'pl_2_3_branch', 'pl_2_4_branch', 'pl_2_5_branch'];
                var main_facilities = ['pl_2_1_main', 'pl_2_2_main', 'pl_2_3_main', 'pl_2_4_main', 'pl_2_5_main'];

                // ฟังก์ชันที่ใช้ในการวน loop และดึงค่า cal_main_branch
                function extractCalBranches(jsonData, keysArray) {
                    jsonData.forEach(function(address) {
                        if (address.lab_types) {
                            keysArray.forEach(function(keyId) {
                                if (Array.isArray(address.lab_types[keyId]) && address.lab_types[keyId].length > 0) {
                                    address.lab_types[keyId].forEach(function(item) {
                                        if (item.cal_main_branch) {
                                            resultArray.push(item.cal_main_branch);
                                        }
                                    });
                                }
                            });
                        }
                    });
                }

                function extractCalMain(jsonData, keysArray) {
                        if (jsonData.lab_types) {
                            keysArray.forEach(function(keyId) {
                                if (Array.isArray(jsonData.lab_types[keyId]) && jsonData.lab_types[keyId].length > 0) {
                                    jsonData.lab_types[keyId].forEach(function(item) {
                                        if (item.cal_main_branch) {
                                            resultArray.push(item.cal_main_branch);
                                        }
                                    });
                                }
                            });
                        }
                }

                extractCalBranches(lab_addresses_json, branches);
                extractCalMain(lab_main_address_json, main_facilities);

                resultArray = Array.from(new Set(resultArray)).map(function(value) {
                    return parseInt(value, 10);
                });


                return resultArray;
        }

        function renderHiddenInputs(uniqueCalMainBranches) {
            // ล้างค่าใน <div id="unique-cal-main-branche"> ก่อน
            $('#unique-cal-main-branche').empty();

            // วนลูปสร้าง <input type="hidden"> สำหรับแต่ละค่าใน uniqueCalMainBranches
            uniqueCalMainBranches.forEach(function(value) {
                // สร้าง input element
                var input = $('<input>').attr({
                    type: 'hidden',
                    name: 'uniqueCalMainBranches[branch_id][]',
                    value: value
                });

                // เพิ่ม input ลงใน <div id="unique-cal-main-branche">
                $('#unique-cal-main-branche').append(input);
            });
        }

        function validateLabAddressesArray(labAddressesArray) {
            // Loop through each object in the labAddressesArray
            for (let i = 0; i < labAddressesArray.length; i++) {
                let labTypes = labAddressesArray[i].lab_types;
                let validArrayFound = false;

                // Loop through each key in lab_types within the current object
                for (let key in labTypes) {
                    // Check if the current key's value is an array
                    if (Array.isArray(labTypes[key])) {
                        // If it's an array, check if it contains at least one item
                        if (labTypes[key].length > 0) {
                            validArrayFound = true; // Found a valid array
                        }
                    } else if (labTypes[key] === 1) {
                        // If any value is exactly 1 (and not an array), return false immediately
                        return false;
                    }
                }

                // If no valid array was found for this labTypes, return false
                if (!validArrayFound) {
                    return false;
                }
            }

            // If all objects passed validation, return true
            return true;
        }

        function validateLabTypes(labTypes) {
            // Variable to check if any valid array is found
            let validArrayFound = false;

            // Loop through each key in labTypes
            for (let key in labTypes) {
                // Check if the current key's value is an array
                if (Array.isArray(labTypes[key])) {
                    // If it's an array, check if it contains at least one item
                    if (labTypes[key].length > 0) {
                        validArrayFound = true; // Found a valid array
                    }
                } else if (labTypes[key] === 1) {
                    // If any value is exactly 1 (and not an array), return false
                    return false;
                }
            }

            // If no valid array was found, return false
            return validArrayFound;
        }

        function validateAddressesAndMainScope() {
            var correctBranchFormat = false;
            var correctMainFormat = false;
            // ดึงข้อมูลจาก session storage เมื่อเอกสารถูกโหลด
            lab_addresses_array = JSON.parse(sessionStorage.getItem('lab_addresses_array')) || [];

            // ตรวจสอบว่า lab_addresses_array มีค่าและไม่ว่าง
            if (Array.isArray(lab_addresses_array) && lab_addresses_array.length > 0) {
                // ถ้ามีข้อมูล เรียกฟังก์ชัน validateLabAddressesArray
                if (validateLabAddressesArray(lab_addresses_array)) {
                    console.log("ข้อมูลสาขาถูกต้อง");
                    correctBranchFormat = true;
                } else {
                    console.log("ข้อมูลสาขาไม่ถูกต้อง");
                    // console.log(lab_addresses_array);
                    correctBranchFormat = false;
                }
            } else {
                // ถ้าไม่มีข้อมูล ไม่ต้องตรวจสอบเพิ่มเติม
                console.log("ไม่มีข้อมูลใน lab_addresses_array");
                correctBranchFormat = true;
            }

            // ดึงข้อมูลสำนักงานใหญ่
            var lab_main_address = JSON.parse(sessionStorage.getItem('lab_main_address')) || { lab_types: {} };

            // ตรวจสอบข้อมูลสำนักงานใหญ่
            if (validateLabTypes(lab_main_address.lab_types)) {
                console.log("ข้อมูลสำนักงานใหญ่ถูกต้อง");
                correctMainFormat = true;
            } else {
                console.log("ข้อมูลสำนักงานใหญ่ไม่ถูกต้อง");
                correctMainFormat = false;
            }

            if (correctBranchFormat && correctMainFormat) {
                return true;
            } else {
                return false;
            }
        }

        
    $(document).on('click', '.btn-scope-group', function(e) {
        e.preventDefault();
        // var selectedValue = $('input[name="lab_ability"]:checked').val();
        const _token = $('input[name="_token"]').val();
        var certi_lab_id = $(this).data('certi_lab');
        var created_at = $(this).data('created_at');
        var group = $(this).data('group');

        // แยกวันที่และเวลาจาก created_at
        var dateTimeParts = created_at.split(' '); // แยกเป็น ['2024-09-12', '13:04:34']
        var dateParts = dateTimeParts[0].split('-'); // แยกเป็น ['2024', '09', '12']
        var timePart = dateTimeParts[1]; // ได้ '13:04:34'

        // แปลงเป็นปี พ.ศ. โดยบวก 543 กับปี ค.ศ.
        var year = parseInt(dateParts[0]) + 543;
        var month = dateParts[1];
        var day = dateParts[2];

        // สร้างรูปแบบ dd/mm/yyyy HH:mm:ss (พ.ศ.)
        var formattedDateTime = "";

        if(created_at !== ""){
            formattedDateTime = 'วันที่ ' + day + '/' + month + '/' + year + ' เวลา ' + timePart;
        }
        

        $('#created_at').html(formattedDateTime);
        // 

        $.ajax({
            url:"{{route('api.get_scope')}}",
            method:"POST",
            data:{
                _token:_token,
                certi_lab_id:certi_lab_id,
                group:group,
            },
            success:function (result){
                console.log(result);
                const labCalScopeMainTransactions = result.labCalScopeTransactions.filter(item => item.branch_lab_adress_id === null);
                var lab_main_address_api = {
                    lab_type: 'main',
                    branch_lab_adress_id: undefined,
                    checkbox_main: '1',
                    address_number_add: "",
                    village_no_add: "",
                    address_city_add: "",
                    address_city_text_add: "",
                    address_district_add: "",
                    sub_district_add: "",
                    postcode_add: "",
                    lab_address_no_eng_add: "",
                    lab_province_text_eng_add: "",
                    lab_province_eng_add: "",
                    lab_amphur_eng_add: "",
                    lab_district_eng_add: "",
                    lab_moo_eng_add: "",
                    lab_soi_eng_add: "",
                    lab_street_eng_add: "",
                    lab_types: createLabTypesFromServer(labCalScopeMainTransactions,null,"main"), // เรียกใช้ฟังก์ชันเพื่อสร้าง lab_types
                    address_soi_add: "",
                    address_street_add: ""
                };

                console.log('lab_main_address_api');
                console.log(lab_main_address_api);

                const labCalScopeBranchTransactions  = result.labCalScopeTransactions.filter(item => item.branch_lab_adress_id !== null);
                const lab_addresses_array_api = [];
                
                result.branchLabAdresses.forEach(branchItem => {
                    // console.log(branchItem);
                    const lab_branch_address_server = {
                        lab_type: 'branch',
                        checkbox_main: '1',
                        branch_lab_adress_id: branchItem.id,
                        // thai
                        address_number_add_modal: branchItem.addr_no || "",
                        village_no_add_modal: branchItem.addr_moo || "",
                        soi_add_modal: branchItem.addr_soi || "",
                        road_add_modal: branchItem.addr_road || "",
                        
                        // จังหวัด
                        address_city_add_modal: branchItem.province.PROVINCE_ID || "",
                        address_city_text_add_modal: branchItem.province.PROVINCE_NAME || "",
                        // อำเภอ
                        address_district_add_modal: branchItem.amphur.AMPHUR_NAME || "",
                        address_district_add_modal_id: branchItem.amphur.AMPHUR_ID || "",
                        // ตำบล
                        sub_district_add_modal: branchItem.district.DISTRICT_NAME || "",
                        sub_district_add_modal_id: branchItem.district.DISTRICT_ID || "",
                        // รหัสไปรษณีย์
                        postcode_add_modal: branchItem.postal || "",

                        // eng
                        lab_address_no_eng_add_modal: branchItem.addr_no || "",
                        lab_moo_eng_add_modal: branchItem.addr_moo_en || "",
                        lab_soi_eng_add_modal: branchItem.addr_soi_en || "",
                        lab_street_eng_add_modal: branchItem.addr_road_en || "",

                        lab_province_eng_add_modal: branchItem.province.PROVINCE_ID || "",
                        // อำเภอ
                        lab_amphur_eng_add_modal: branchItem.amphur.AMPHUR_NAME_EN || "",
                        // ตำบล
                        lab_district_eng_add_modal: branchItem.district.DISTRICT_NAME_EN || "",
                        
                        lab_types: createLabTypesFromServer(labCalScopeBranchTransactions, branchItem.id, "branch"), // สำหรับสาขา
                    };

                    lab_addresses_array_api.push(lab_branch_address_server);
                            
                });

                console.log('lab_addresses_array_api');
                console.log(lab_addresses_array_api);

                $('#show_cal_scope_wrapper').empty();

                renderLabTypesMainTransactionsModal(lab_main_address_api.lab_types,'#show_cal_scope_wrapper');
                renderLabTypesBranchTransactionsModal(result.branchLabAdresses, lab_addresses_array_api,'#show_cal_scope_wrapper');
                $('#modal-show-cal-scope').modal('show');

            }
        });

        

    });



        function submit_form(status) {
            
            if(validateAddressesAndMainScope() == false){
                alert('รูปแบบขอบข่ายไม่ถูกต้อง โปรดแก้ไข');
                return
            }

            var lab_addresses_array = JSON.stringify(JSON.parse(sessionStorage.getItem('lab_addresses_array')) || []);
            var lab_main_address = JSON.stringify(JSON.parse(sessionStorage.getItem('lab_main_address')) || {});

            // console.log(JSON.parse(sessionStorage.getItem('lab_addresses_array')));
            // console.log(JSON.parse(sessionStorage.getItem('lab_main_address')) || {});

          
            // เรียกใช้ฟังก์ชัน
            var lab_addresses_json = JSON.parse(sessionStorage.getItem('lab_addresses_array')) || [];
            var lab_main_address_json = JSON.parse(sessionStorage.getItem('lab_main_address')) || {};

     
            var uniqueCalMainBranches = getUniqueCalMainBranches(lab_addresses_json, lab_main_address_json);

            // console.log(uniqueCalMainBranches);
            renderHiddenInputs(uniqueCalMainBranches);

            // ใส่ค่าสตริง JSON ลงใน input fields แบบซ่อน
            $('#lab_addresses_input').val(lab_addresses_array);
            $('#lab_main_address_input').val(lab_main_address);


            Swal.fire({
                    title: 'ยืนยันการทำรายการ !',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.value) {
                            $('#status_btn').html('<input type="text" name="draft" value="' + status + '" hidden>');
                            $('#cost_form').submit();
                        }
                    })
            }
                //Validate
                $('#cost_form').parsley().on('field:validated', function() {
                        var ok = $('.parsley-error').length === 0;
                        $('.bs-callout-info').toggleClass('hidden', !ok);
                        $('.bs-callout-warning').toggleClass('hidden', ok);
                        })
                        .on('form:submit', function() {
                            // Text
                            $.LoadingOverlay("show", {
                                image       : "",
                                text        : "กำลังบันทึก กรุณารอสักครู่..."
                            });
                        return true; // Don't submit form for this demo
                });


    </script>
    <script>
        $(document).ready(function () {

            $('.check-readonly').prop('disabled', true);//checkbox ความคิดเห็น
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css('margin-top', '8px');//checkbox ความคิดเห็น
            //เพิ่มไฟล์แนบ
            check_max_size_file();
            $('#attach-add').click(function(event) {
                $('.other_attach_item:first').clone().appendTo('#attach-box');
                $('.other_attach_item:last').find('input').val('');
                $('.other_attach_item:last').find('a.fileinput-exists').click();
                $('.other_attach_item:last').find('a.view-attach').remove();
                $('.other_attach_item:last').find('.label_attach').remove();
                $('.other_attach_item:last').find('.attach-span').remove();
                $('.other_attach_item:last').find('button.attach-add').remove();
                $('.other_attach_item:last').find('.button_remove').html('<button class="btn btn-danger btn-sm attach-remove" type="button"> <i class="icon-close"></i>  </button>');
                // ShowHideRemoveBtn94();
                AttachFile();
                check_max_size_file();
            });

            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().parent().remove();
                // ShowHideRemoveBtn94();
            });
            AttachFile();
            // ShowHideRemoveBtn94();
        });
        function  AttachFile(){
            $('.attachs').change( function () {
                    var fileExtension = ['pdf'];
                    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1  && $(this).val() != '') {
                        Swal.fire(
                        'ไม่ใช่หลักฐานประเภทไฟล์ที่อนุญาต .pdf',
                        '',
                        'info'
                        )
                    this.value = '';
                    return false;
                    }
                });
        }

        function  deleteFlie(no){
            Swal.fire({
                    title: 'ยื่นยันการลบไฟล์แนบ !',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'บันทึก',
                    cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.value) {
                            let id = "{{  $ec->id ?? null }}";
                           $.ajax({
                                url: "{!! url('/certify/estimated_cost/delete_file') !!}" + "/" + id + "/" + no
                            }).done(function( object ) {
                                if(object == 'delete_file'){
                                    $(this).parent().parent().remove();
                                    window.location.assign("{{route('estimated_cost.edit', ['ec' => $ec, 'app' => $app ? $app->id : ''])}}");
                                }else{
                                    alert('ข้อมูลผิดพลาด');
                                }
                            });
                        }
                    })
        }

    </script>

{{-- <script src="{{asset('assets/js/lab/cal/scope.js?v=1.0')}}"></script> --}}

@endpush
