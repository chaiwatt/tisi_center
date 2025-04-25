<div class="white-box">

    {{-- <div class="row">
        <div class="col-sm-12">
            <div class="form-group  pull-right">

                <button type="button" class="btn btn-primary add_certicb_file_all" data-toggle="modal" data-target="#exampleModalExport">เพิ่มไฟล์แนบท้าย</button>
            </div>
        </div>
    </div> --}}


    <div class="row">
        <div class="col-sm-12">

            <div class="form-group {{ $errors->has('certi_no') ? 'has-error' : ''}}">

                <div class="table-responsive repeater-file">

                    <table class="table color-bordered-table info-bordered-table" id="myTable">
                        <thead>
                            <tr>
                                <th width="1%" class="text-center">#</th>
                                <th width="8%" class="text-center">เลขที่คำขอ</th>
                                <th width="20%" class="text-center">ไฟล์แนบท้าย</th>
                                <th width="10%" class="text-center">วันที่ออกให้</th>
                                <th width="10%" class="text-center">วันที่หมดอายุ</th>
                                <th width="10%" class="text-center">สถานะ</th>
                                <th width="10%" class="text-center">วันที่บันทึก</th>
                                {{-- <th width="7%" class="text-center" width="100px">จัดการ</th> --}}
                            </tr>
                        </thead>
                        <tbody data-repeater-list="detail">
                            
                            @if (!empty($cert_labs_file_all) && $cert_labs_file_all->count() > 0)
                                @foreach ($cert_labs_file_all as $key => $certilab_file)
                                    <tr id="deleteFlie{{$certilab_file->id}}">
                                        <td class="no-attach">
                                            {{$loop->iteration}}
                                           
                                        </td>
                                        <td class="text-center">
                                            {{$certilab_file->app_no ?? '-'}}

                                            @php
                                            $purpose = '';
                                            if(!empty($certilab_file->app_no)){
                                                $purpose_id =   App\Models\Certify\Applicant\CertiLab::where('app_no',$certilab_file->app_no)->value('purpose_type');
                                                // {{$certilab_file->CertiLabTo}}
                                                // if(!empty($purpose_id) &&  array_key_exists($purpose_id,$purposes)  ){
                                                if($certilab_file->ref_table == "app_certi_tracking")
                                                {
                                                    $purpose = '<p class="text-muted"><i>ตรวจติดตาม</i></p>';
                                                }else{
                                                    $purpose = '<p class="text-muted"><i>'.$certilab_file->CertiLabTo->purposeType->name.'</i></p>';
                                                }
                                                    
                                                // }
                                            }
                                        @endphp
                                       {!! $purpose !!}


                                            <input type="hidden" value="{{$certilab_file->id}}" class="certificate_edit_row" name="id"/>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-center">
                                                @if(!is_null($certilab_file->attach))
                                                    <a href="{!! HP::getFileStorage($attach_path.$certilab_file->attach) !!}" class="attach" target="_blank">
                                                        {!! HP::FileExtension($certilab_file->attach) ?? '' !!}
                                                    </a>
                                                @endif
                                                @if(!is_null($certilab_file->attach_pdf))
                     
                                                    <a href="{!! HP::getFileStorage($attach_path.$certilab_file->attach_pdf) !!}" class="attach_pdf"  target="_blank">
                                                        {!! HP::FileExtension($certilab_file->attach_pdf) ?? '' !!}
                                                    </a>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="text-center ">{{ HP::DateThai($certilab_file->start_date) ?? '-' }}</td>
                                        <td class="text-center ">{{ HP::DateThai($certilab_file->end_date) ?? '-' }}</td>
                                        <td class="text-center ">
                                            <div class="checkbox">
                                                {!! Form::checkbox('state', $certilab_file->id , !empty($certilab_file->state) && $certilab_file->state == '1' ? true : false , ['class' => 'js-switch', 'data-color'=>'#13dafe', 'data-certilab_file_id'=>$certilab_file->id]) !!}
                                            </div>
                                        </td>
                                        <td class="text-center created_at">{{ HP::DateThai($certilab_file->created_at  ) }}</td>
                                        {{-- <td class="text-center">
                                            
                                            @if ($certilab_file->state == 1)
                                                <button class="hide_attach btn btn-warning btn-xs edit_modal" type="button"  data-id="{{$certilab_file->id}}"  data-app_no="{{$certilab_file->app_no}}">
                                                             <i class="fa fa-pencil-square-o"></i>
                                                </button>
                                            @else
                                                <button class="hide_attach btn btn-danger btn-xs del-attach" type="button"  data-id="{{$certilab_file->id}}" data-app_no="{{$certilab_file->app_no}}">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </td> --}}
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                    <div id="div_delete_flie"></div>
                    <div class="pagination-wrapper">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {



            $('#status').change(function() {
                if ($(this).val() == 3) {
                    $('#export_file').show();
                    $('#attachs').prop('required', true);
                } else {
                    $('#export_file').hide();
                    $('#attachs').prop('required', false);
                }
            });
            $('#status').change();


            // $('#myTable tbody .js-switch').each(function() {
            //     // ดึงค่า data-certilab_file_id จาก element ปัจจุบัน
            //     var certilab_file_id = $(this).data('certilab_file_id');

            //     // ตรวจสอบสถานะของ switch (checked หรือไม่)
            //     var state = $(this).is(':checked') ? 1 : 0;

            //     // แสดงค่า certilab_file_id และ state ใน console
            //     console.log('certilab_file_id:', certilab_file_id, 'state:', state);
            // });

            //เลือกสถานะ เปิด-ปิด
            $('#myTable tbody').on('change', '.js-switch', function(){
                var certilab_file_id =  $(this).data('certilab_file_id');

                // สร้างอาร์เรย์เก็บข้อมูลทั้งหมด
                var switches = [];

                // วนลูปอ่านค่าจาก .js-switch ทุกตัวใน #myTable tbody
                $('#myTable tbody .js-switch').each(function() {
                    var certilab_file_id = $(this).data('certilab_file_id'); // ดึงค่า certilab_file_id
                    var state = $(this).is(':checked') ? 1 : 0; // ดึงสถานะ (checked หรือไม่)

                    // เก็บค่าที่อ่านได้ในรูปของ object
                    switches.push({
                        certilab_file_id: certilab_file_id,
                        state: state
                    });
                });

                console.log(switches);

                
                console.log(certilab_file_id);
                var state = $("input[name=state]:checked").val()==1?1:0;
                if(checkNone(certilab_file_id)){
                    $.ajax({
                        method: "POST",
                            url: "{{ url('certify/certificate-export-lab/update_status') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "certilab_file_id": certilab_file_id,
                                "state": state,
                                "switches": switches,
                        },
                        success : function (msg){
                            if (msg == "success") {
                                $.toast({
                                    heading: 'Success!',
                                    position: 'top-center',
                                    text: 'อัพเดท active ไฟล์สำเร็จ',
                                    loaderBg: '#70b7d6',
                                    icon: 'success',
                                    hideAfter: 1000,
                                    stack: 6
                                });
                            } 
                        }
                    });
                }  
            });

            $('.add_certicb_file_all').click(function (e) { 
           
                var app_no = $('#app_no').val(); 

                if(app_no != '' ){
                    $('#modal_app_no').val(app_no);
                }else{
                    $('#exampleModalExport').modal('hide');
                    alert('กรุณาเลือก เลขที่คำขอ');
                }
                
            });

 
           $("body").on("click", ".edit_modal", function() {
                   var rows  =   $(this).parent().parent();
                    var id = $(this).data('id');
                    var app_no = $(this).data('app_no');
                    $('#edit_modal_app_no').val(app_no);
                    $('#edit_modal_app_no').attr("data-id", id);
                    $('#EditModalExport').modal('show');

                    var start_date = $(rows).find(".start_date").val();
                    if(checkNone(start_date)){
                        $('#edit_modal_start_date').val(start_date);
                    }

                    var end_date = $(rows).find(".end_date").val();
                    if(checkNone(end_date)){
                        $('#edit_modal_end_date').val(end_date);
                    }
            });


             
           $("body").on("click", ".del-attach", function() {
                    var id = $(this).data('id');
                    var app_no = $(this).data('app_no');
                    Swal.fire({
                        icon: 'error'
                        , title: 'ยืนยันการลบแถวและไฟล์แนบ !'
                        , showCancelButton: true
                        , confirmButtonColor: '#3085d6'
                        , cancelButtonColor: '#d33'
                        , confirmButtonText: 'บันทึก'
                        , cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.value) {
                            $('#div_delete_flie').append('<input type="hidden" name="delete_flie[]" value="' +  id + '" >');
                              $('#myTable tbody').find('#deleteFlie' + id).remove();
                              resetAttachmentNo();
                            // $.ajax({
                            //     url: "{!! url('certify/certificate-export-lab/delete_file') !!}" + "/" + id
                            // }).done(function(object) {
                            //     if (object == 'true') {
                            //         $('#myTable tbody').find('#deleteFlie' + id).remove();
                            //     } else {
                            //         Swal.fire('ข้อมูลผิดพลาด');
                            //     }
                            // });

                        }
                    })
                  
            });

        });


       

 
    </script>


@endpush
