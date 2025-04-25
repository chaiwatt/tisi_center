<!--form Modal -->
<div class="modal fade text-left" tabindex="10" id="AddUserForm" role="dialog" aria-labelledby="AddUserFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ScopeFormLabel">รายชื่อเจ้าหน้าที่</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="bx bx-x"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box" >
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-9">
                                                    {!! Form::label('filter_search', 'คำค้น:', ['class' => 'col-md-2 control-label label-filter']) !!}
                                                <div class="col-md-10 form-group">
                                                    {!! Form::text('filter_search', null, ['class' => 'form-control', 'id' => 'filter_search', 'placeholder'=>'ค้นหาจาก ชื่อ-สกุล/ตำแหน่ง']); !!}
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group pull-left">
                                                    <button type="button" class="btn btn-info waves-effect waves-light" style="margin-bottom: -1px;" id="btn_search">ค้นหา</button>
                                                </div>
                                                <div class="form-group pull-left m-l-15">
                                                    <button type="button" class="btn btn-warning waves-effect waves-light" id="btn_clean">
                                                        ล้าง
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                {!! Form::select('filter_department', App\Models\Besurv\Department::pluck('depart_name', 'did'), null, ['class' => 'form-control', 'placeholder'=>'-เลือกกอง-', 'id' =>'filter_department']); !!}
                                            </div>

                                            <div class="col-md-6 form-group">
                                                {!! Form::select('filter_sub_department_id',
                                                App\Models\Basic\SubDepartment::orderbyRaw('CONVERT(sub_departname USING tis620)')->pluck('sub_departname','sub_id'),
                                                null,
                                                ['class' => 'form-control select2',
                                                'placeholder'=>'- เลือกกลุ่มงาน -',
                                                'id' =>'filter_sub_department_id']); !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped" id="myTable_add_user">
                                        <thead>
                                            <tr>
                                                <th width="1%"><input type="checkbox" id="checkall"></th>
                                                <th width="1%" class="text-center">No.</th>
                                                <th width="15%" class="text-center">รายชื่อเจ้าหน้าที่</th>
                                                <th width="15%" class="text-center">ตำเเหน่ง</th>
                                                <th width="20%" class="text-center">กอง</th>
                                                <th width="20%" class="text-center">กลุ่มงาน</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" data-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i><span class="d-none d-sm-block">ยกเลิก</span></button>
                        <button type="button" class="btn btn-primary ml-1" data-dismiss="modal" id="btn_add_user"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">เลือก</span></button>
                    </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@push('js')
<script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
<script>

     $(function () {

var table = $('#myTable_add_user').DataTable({
    processing: true,
    serverSide: true,
    searching: false,
    autoWidth: false,
    lengthChange: false,
    ajax: {
        url: '{!! url('/bsection5/workgroup-ib/data_user_register') !!}',
        data: function (d) {
            d.filter_search = $('#filter_search').val();
            d.filter_department = $('#filter_department').val();
            d.filter_sub_department_id = $('#filter_sub_department_id').val();
        }
    },
    columns: [
        { data: 'checkbox', searchable: false, orderable: false},
        { data: 'DT_Row_Index', searchable: false, orderable: false},
        { data: 'fullname', name: 'fullname' },
        { data: 'position', name: 'position' },
        { data: 'department', name: 'department' },
        { data: 'subdepart', name: 'subdepart' },
    ],
    columnDefs: [
        { className: "text-center", targets:[0,-1] },
    ],
    fnDrawCallback: function() {

        $(".js-switch").each(function() {
            new Switchery($(this)[0], { size: 'small' });
        });
    }
});

    $('#filter_department').change(function(event) {

        $('#filter_sub_department_id').children('option[value!=""]').remove();

        $.ajax({
            url: "{{ url('basic/sub-department/get_json_by_department') }}/"+$(this).val(),
        }).success(function (res) {

            $.each(res, function(index, item) {
                $('#filter_sub_department_id').append('<option value="'+item.sub_id+'">'+item.sub_departname+'</option>');
            });
            $('#filter_sub_department_id').trigger('change.select2');
        });

    });


$('#checkall').on('click', function(e) {
    if($(this).is(':checked',true)){
    $(".item_user_checkbox").prop('checked', true);
    } else {
    $(".item_user_checkbox").prop('checked',false);
    }
});


$('#btn_search').click(function () {
    table.draw();
});

$('#btn_clean').click(function () {
    $('#filter_status,#filter_search').val('');
    $('#filter_sub_department_id,#filter_department').val('').change();
    table.draw();
});


$("#btn_add_user").on("click" , function () {
    var rows = $('#tbody-add-user').children();//แถวทั้งหมด
    var item_user_checkbox =  $('.item_user_checkbox:checked').length;

        if(item_user_checkbox > 0  ){
            var  _tr = '';
             //   $('#tbody-add-user').html('');
               $('.item_user_checkbox:checked').each(function(index, element){
                _tr+= '<tr>';
                _tr += '<td class="text-center">'+(index+1)+'</td>'; //ลำดับ
                _tr+= '<td>';
                _tr+=  '<p>'+$(element).data('fullname')+'</p>' ;
                _tr+= '<input type="hidden" name="user_reg_id[]" value="'+ $(element).val() +'">';
                _tr+= '</td>';
                _tr+= '<td>';
                _tr+=  '<p>'+$(element).data('position')+'</p>' ;
                _tr+= '</td>';
                _tr+= '<td>';
                _tr+=  '<p>'+$(element).data('department')+'</p>' ;
                _tr+= '</td>';
                _tr+= '<td>';
                _tr+=  '<p>'+$(element).data('subdepart')+'</p>' ;
                _tr+= '</td>';
                _tr+= '<td class="text-center font-medium-1">';
                _tr+= '<button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 btn_remove_branch "><i class="fa fa-trash"></i></i></button>';
                _tr+= '</td>';
                _tr+= '</tr>';
            });

            $('#tbody-add-user').append(_tr);
        }else{
            alert('กรุณาเลือกเจ้าหน้าที่ !');
        }
        ResetTableScope();
});

ResetTableScope();

$("body").on("click", ".btn_remove_branch", function() {
    $(this).parent().parent().remove();
    ResetTableScope();
});


});

    function ResetTableScope(){
        var rows = $('#tbody-add-user').children(); //แถวทั้งหมด
            rows.each(function(index, el) {
            //เลขรัน
            $(el).children().first().html(index+1);
            });
    }

</script>
@endpush
