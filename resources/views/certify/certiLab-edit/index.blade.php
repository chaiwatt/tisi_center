@extends('layouts.master')

@push('css')
  <link rel="stylesheet" href="{{ asset('plugins/components/summernote/summernote.css') }}" />
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3>แก้ไขรายละเอียดแนบท้ายใบรับรองห้องปฎิบัติการทดสอบ</h3>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="app_no" class="control-label">เลขที่คำขอ: </label>
                                <input type="text" class="form-control text-center" readonly value="{{ $certi_lab->app_no }}">
                            </div>
                        </div>

                        {!! Form::open(['url' => route('show.certificate.applicant.update', ['certilab' => $certi_lab->id]), 'class' => 'form-horizontal', 'method' => 'post']) !!}

                        <table class="table table-bordered" id="myTable_labTest">
                            <thead class="bg-primary">
                            <tr>
                                <th class="text-center text-white col-xs-1">ลำดับ</th>
                                <th class="text-center text-white col-xs-2">สาขาการทดสอบ</th>
                                <th class="text-center text-white col-xs-3">รายการทดสอบ</th>
                                <th class="text-center text-white col-xs-3">วิธีการทดสอบ</th>
                            </tr>
                            </thead>
                            <tbody id="labtest_tbody">
                                @if ($certi_lab && !empty($certi_lab))
                                    @if ($certi_lab->certi_test_scope->count() > 0)
                                        @foreach ($certi_lab->certi_test_scope as $scope)
                                            <tr>
                                                <td class="text-center" style="vertical-align:top">{{$loop->iteration}}</td>
                                                <td class="text-center" style="vertical-align:top">
                                                  <div>
                                                    {!! Form::select('branch_id[]', $test_branchs, $scope->branch_id, ['placeholder' => '-เลือกสาขาการทดสอบ-', 'class'=>'form-control', 'required'=>true]) !!}
                                                    {!! Form::textarea('test_detail[]', $scope->test_detail, ['placeholder' => 'รายละเอียดทดสอบ', 'class'=>'form-control', 'rows'=>3]) !!}
                                                  </div>
                                                </td>
                                                <td class="text-center" style="vertical-align:top">
                                                    <textarea class="form-control input-editer" name="test_list[]">{{ $scope->test_list }}</textarea>
                                                </td>
                                                <td class="text-center" style="vertical-align:top">
                                                    <textarea class="form-control input-editer" name="test_method[]">{{ $scope->test_method }}</textarea>
                                                    <button type="button" class="btn btn-danger btn-sm pull-right remove-item"> <i class="fa fa-close"></i> ลบ</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-success pull-right" id="add-item">
                            <i class="fa fa-plus"></i> เพิ่ม
                        </button>

                        <div class="clearfix"></div>
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary" id="add-item">
                                <i class="fa fa-save"></i> บันทึก
                            </button>

                            <a class="btn btn-default" href="{{ url('certify/check_certificate') }}">
                                <i class="fa fa-save"></i> ยกเลิก
                            </a>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')

  <script src="{{ asset('plugins/components/summernote/summernote.js') }}"></script>
  <script src="{{ asset('plugins/components/summernote/summernote-ext-specialchars.js') }}"></script>
  <script type="text/javascript">

      $(document).ready(function() {

        //เพิ่มรายการ
        $('#add-item').click(function(event) {

            $('#labtest_tbody').children(':first').clone().appendTo('#labtest_tbody');

            var last = $('#labtest_tbody').children(':last');

            //รีเซตตัวเลือกสาขาการทดสอบ
            $(last).find('select').val('');
            $(last).find('select').prev().remove();
            $(last).find('select').show();
            $(last).find('select').select2();

            //รีเซต textarea
            $(last).find('[name^="test_detail"]').val('');

            //รีเซต editor รายการทดสอบ
            $(last).find('.input-editer').val('');
            $(last).find('.input-editer').show();
            $(last).find('.input-editer').next().remove();
            creatSummernote($(last).find('.input-editer'));

            resetOrder();
        });

        //ลบรายการ
        $(document).on('click', '.remove-item', function(){
            $(this).parent().parent().remove();
            resetOrder();
        });

        resetOrder();
        creatSummernote('.input-editer');

      });

      function resetOrder(){//รีเซตตัวเลข และซ่อน-แสดงปุ่มลบ

          $('#labtest_tbody').children('tr').each(function(index, el) {
              $(el).find('td:first').text(index+1);
          });

          if($('#labtest_tbody').children('tr').length > 1){
            $('.remove-item').show();
          }else{
            $('.remove-item').hide();
          }

      }

      function creatSummernote(el){
          $(el).summernote({
              toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript', 'specialchars']],
                ['fontsize', ['fontsize']],
                ['color', ['color']]
              ]
          });
      }

  </script>

@endpush
