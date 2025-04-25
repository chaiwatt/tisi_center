<div class="white-box">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group  pull-right">

                <button type="button" class="btn btn-primary add_certiib_file_all" data-toggle="modal" data-target="#exampleModalExport">เพิ่มไฟล์แนบท้าย</button>
            </div>
        </div>
    </div>


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
                                <th width="7%" class="text-center" width="100px">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody data-repeater-list="detail">
                            @if ($certiib_file_all && count($certiib_file_all) > 0)
                                @foreach ($certiib_file_all as $certiib_file)
                                <tr id="deleteFlie{{$certiib_file->id}}">
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-center">
                                            {{$certiib_file->app_no ?? '-'}}
                                            <input type="hidden" value="{{$certiib_file->id}}" class="certificate_edit_row" name="id"/>
                                            <input type="hidden" value="{{ HP::revertDate($certiib_file->start_date) ?? '-' }}" class="start_date"/>
                                            <input type="hidden" value="{{ HP::revertDate($certiib_file->end_date) ?? '-' }}" class="end_date"/>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-center">
                                                @if(!is_null($certiib_file->attach))
                                                    <a href="{!! HP::getFileStorage($attach_path.$certiib_file->attach) !!}" class="attach"  target="_blank">
                                                        {!! HP::FileExtension($certiib_file->attach) ?? '' !!}
                                                    </a>
                                                @endif
                                                @if(!is_null($certiib_file->attach_pdf))
                                                    <a href="{!! HP::getFileStorage($attach_path.$certiib_file->attach_pdf) !!}"  class="attach_pdf" target="_blank">
                                                        {!! HP::FileExtension($certiib_file->attach_pdf) ?? '' !!}
                                                    </a>
                                                @endif
                                            </p>
                                        </td>
                                        <td class="text-center">{{ HP::DateThai($certiib_file->start_date) ?? '-' }}</td>
                                        <td class="text-center">{{ HP::DateThai($certiib_file->end_date) ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="checkbox">
                                                {!! Form::checkbox('state', '1', !empty($certiib_file->state) && $certiib_file->state == '1' ? true : false , ['class' => 'js-switch', 'data-color'=>'#13dafe', 'data-certiib_file_id'=>$certiib_file->id]) !!}
                                            </div> 
                                        </td>
                                        <td class="text-center created_at">{{ HP::DateThai($certiib_file->created_at) }}</td>
                                        <td class="text-center">
                                            @if ($certiib_file->state == 1)
                                                <button class="hide_attach btn btn-warning btn-xs edit_modal" type="button"  data-id="{{$certiib_file->id}}"  data-app_no="{{$certiib_file->app_no}}">
                                                             <i class="fa fa-pencil-square-o"></i>
                                                </button>
                                            @else
                                                <button class="hide_attach btn btn-danger btn-xs del-attach" type="button"  data-id="{{$certiib_file->id}}" data-app_no="{{$certiib_file->app_no}}">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </td>
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

            $('#status').change(function(){ 
                if($(this).val() == 3){
                    $('#export_file').show();                
                    $('#attachs').prop('required',true);
                }else{
                    $('#export_file').hide();
                    $('#attachs').prop('required',false);
                }
            });
            $('#status').change();

            //เลือกสถานะ เปิด-ปิด
            $('#myTable tbody').on('change', '.js-switch', function(){

                var certiib_file_id =  $(this).data('certiib_file_id');
                var state = $("input[name=state]:checked").val()==1?1:0;
                
                $.ajax({
                    method: "POST",
                        url: "{{ url('certify/certificate-export-ib/update_status') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "certiib_file_id": certiib_file_id,
                            "state": state
                    },
                    success : function (msg){
                        if (msg == "success") {
                            $.toast({
                                heading: 'Success!',
                                position: 'top-center',
                                text: 'บันทึกสำเร็จ',
                                loaderBg: '#70b7d6',
                                icon: 'success',
                                hideAfter: 3000,
                                stack: 6
                            });
                            location.reload(); 
                        } 
                    }
                });
            });

            $('.add_certiib_file_all').click(function (e) { 
           
                var app_no =   $('#app_no').val();

                if(app_no != '' ){
                    $('#modal_app_no').val(app_no);
                }else{
                    $('#exampleModalExport').modal('hide');
                    alert('กรุณาเลือก เลขที่คำขอ');
                }
                
            });

            resetAttachmentNo();

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
                            //   $.ajax({
                            //         url: "{!! url('certify/certificate-export-ib/delete-file') !!}" + "/" +  id
                            //     }).done(function( object ) {
                            //         $('.del-attach[value="'+id+'"]').parent().parent().remove();
                            //         resetAttachmentNo();

                            //         location.reload(); 
                            //     });

                        }
                    })
                  
            });



        });

 
    </script>
@endpush