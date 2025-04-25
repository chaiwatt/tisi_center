{{-- @extends('layouts.master')

@section('content')
<div class="container-fluid">
  <!-- .row -->
  <div class="row">
    <div class="col-sm-12">
      <div class="white-box">
        <h3 class="box-title pull-left">รายละเอียดการแจ้งข้อมูลอื่นๆ {{ $other->id }}</h3>
        @can('view-'.str_slug('other'))
        <a class="btn btn-success pull-right" href="{{ url('/esurv/other') }}">
          <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
        </a>
        @endcan
        <div class="clearfix"></div>

        <div class="table-responsive">
          <table class="table table">
            <tbody>
              <tr>
                <th class="col-md-4">ID</th>
                <td class="col-md-8">{{ $other->id }}</td>
              </tr>
              <tr>
                <th> เรื่อง </th>
                <td> {{ $other->title }} </td>
              </tr>
              <tr>
                <th> ประเภทการแจ้ง </th>
                <td> {{ HP::OtherTypes()[$other->inform_type] }} </td>
              </tr>
              <tr>
                <th> รายละเอียด </th>
                <td> {{ $other->detail }} </td>
              </tr>
              <tr>
                <th class="text-top"> มาตรฐาน </th>
                <td>

                  <ul class="list-group">
                    @foreach ($other->tis_list as $key=>$tis)
                      <li class="list-group-item">{{ $tis->tis->tb3_Tisno.' '.$tis->tis->tb3_TisThainame }}</li>
                    @endforeach
                  </ul>

                </td>
              </tr>
              <tr>
                <th class="text-top"> ไฟล์แนบ </th>
                <td>

                  <ul class="list-group">
                    @foreach ($attachs as $attach)
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $attach->file_note }}
                        @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                          <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="btn btn-info btn-sm pull-right" style="margin-top: -5px;">
                            <i class="fa fa-search"></i>
                          </a>
                        @endif
                      </li>
                    @endforeach
                  </ul>

                </td>
              </tr>
              <tr>
                <th> 	สถานะ  </th>
                <td> 
                  @php
                  $status_css = ['1'=>'label-info', '2'=>'label-success', '3'=>'label-danger'];
                  $status_receive  =['1' => 'รอดำเนินการ', '2' => 'อยู่ระหว่างดำเนินการ', '3' => 'ปิดเรื่อง'];
                  @endphp
                  @if(array_key_exists($other->state,$status_css) && array_key_exists($other->state,$status_receive))
                     <span class="label {{ $status_css[$other->state] }}">
                        <b>{{ $status_receive[$other->state] }}</b>
                    </span>
                  @else 
                     <span class="label label-info">
                        <b>รอดำเนินการ</b>
                    </span>
                  @endif
                </td>
              </tr>
              <tr>
                <th> 	ความคิดเห็นเพิ่มเติม  </th>
                <td> 
                  {{ @$other->remake }} 
                </td>
              </tr>
              <tr>
                <th> ชื่อผู้บันทึก </th>
                <td> {{ $other->applicant_name }} </td>
              </tr>
              <tr>
                <th> เบอร์โทร </th>
                <td> {{ $other->tel }} </td>
              </tr>
              <tr>
                <th> อีเมล </th>
                <td> {{ $other->email }} </td>
              </tr>
              <tr>
                <th> ผู้สร้าง </th>
                <td> {{ $other->createdName }} </td>
              </tr>
              <tr>
                <th> วันเวลาที่สร้าง </th>
                <td> {{ HP::DateTimeThai($other->created_at) }} </td>
              </tr>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection --}}
@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ดูการแจ้งข้อมูลอื่นๆ #{{ $other->id }}</h3>
                    @can('view-'.str_slug('other'))
                        <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
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

                    {!! Form::model($other, [
                        'method' => 'PATCH',
                        'url' => ['/esurv/other', $other->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}
                    <div id="box_readonly">
                    @include ('esurv.other.form')
                    <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                      {!! Form::label('', 'เบอร์โทร:', ['class' => 'col-md-4 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('', !empty($other->user_updated->reg_phone) ?  $other->user_updated->reg_phone : null, ['class' => 'form-control','disabled'=>true]) !!}
                          {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>
                    <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                      {!! Form::label('', 'E-mail:', ['class' => 'col-md-4 control-label']) !!}
                      <div class="col-md-6">
                          {!! Form::text('', !empty($other->user_updated->reg_email) ?  $other->user_updated->reg_email : null, ['class' => 'form-control','disabled'=>true]) !!}
                          {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                      </div>
                    </div>
                     </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js') 
    <script>
        jQuery(document).ready(function() {
           // จัดการข้อมูลในกล่องคำขอ false
            $('#box_readonly').find('button[type="submit"]').remove();
            $('#box_readonly').find('.icon-close').parent().remove();
            $('#box_readonly').find('.fa-copy').parent().remove();
            $('#box_readonly').find('.list_attach').hide();
            $('#box_readonly').find('input').prop('disabled', true);
            $('#box_readonly').find('input').prop('disabled', true);
            $('#box_readonly').find('textarea').prop('disabled', true); 
             $('#box_readonly').find('select').prop('disabled', true);
             $('#box_readonly').find('.bootstrap-tagsinput').prop('disabled', true);
             $('#box_readonly').find('span.tag').children('span[data-role="remove"]').remove();
             $('#box_readonly').find('button').prop('disabled', true);
             $('#box_readonly').find('button').remove();
             $('#box_readonly').find('button').remove();
            $('body').on('click', '.attach-remove', function() {
                $(this).parent().parent().parent().find('input[type=hidden]').val('');
                $(this).parent().remove();
            });
        });
    </script>
     
@endpush